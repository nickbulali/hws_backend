<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;


use App\Models\Facility;
use App\User;
use App\Models\Profile;
use App\Models\WorkerProfile;
use App\Models\UserRating;
use App\Models\UserRequest;
use Carbon\Carbon;
use App\Notifications\SignupActivate;
use DB;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function Allusers(Request $request)
    {
        if ($request->query('search')) {
            $search = $request->query('search');
           $user = User::where('first_name', 'LIKE', "%{$search}%")->get();
        } else {
           $user = User::orderBy('id', 'DESC')->get();
        }
        return response()->json( $user);
    }
    
      public function Ratings(Request $request)
    {
        if ($request->query('search')) {
            $search = $request->query('search');
           $UserRating = UserRating::where('first_name', 'LIKE', "%{$search}%")->get();
        } else {
           $UserRating = UserRating::with('worker','client')->orderBy('id', 'DESC')->get();
        }
        return response()->json( $UserRating);
    }
    
    
    
    
    
        
    public function healthworkers(Request $request)
    {
        if ($request->query('search')) {
            $search = $request->query('search');
           $worker = WorkerProfile::where('name', 'LIKE', "%{$search}%")->get();
        } else {
            $worker = WorkerProfile::with('users','workerCategory', 'workerSubCategory')->orderBy('id', 'DESC')->get();
        }
        return response()->json( $worker);
    }



      public function DailyRequest(Request $request)
    {
        if ($request->query('search')) {
            $search = $request->query('search');
           $dailyRequests = UserRequest::where('name', 'LIKE', "%{$search}%")->get();
        } else {
           $dailyRequests = UserRequest::with('recipient.healthWorkerProfile.workerCategory', 'recipient.healthWorkerProfile.workerSubCategory', 'status','requester')->where('created_at', '=', Carbon::today()->toDateString())->orderBy('id', 'DESC')->get();
        }
        return response()->json($dailyRequests);
    }

    
    
    
    
    
    
    public function Allrequests(Request $request)
    {
        if ($request->query('search')) {
            $search = $request->query('search');
           $userRequests = UserRequest::where('name', 'LIKE', "%{$search}%")->get();
        } else {
           $userRequests = UserRequest::with('recipient.healthWorkerProfile.workerCategory', 'recipient.healthWorkerProfile.workerSubCategory', 'status','requester')->orderBy('id', 'DESC')->get();
        }
        return response()->json($userRequests);
    }


    public function countUsers()
    {


        $countUsers = User::count();

        return response()->json($countUsers);
        //
    }
    
  public function countFacilities()
    {


        $countFacilities = Facility::count();

        return response()->json($countFacilities);
        //
    }
    
    
    
        public function pending()
    {


        $pending = UserRequest::where('status_id','=',1)->count();

        return response()->json($pending);
        //
    }

    public function rejected()
    {


        $rejected = UserRequest::where('status_id','=',4)->count();

        return response()->json($rejected);
        //
    }



         public function complete()
    {


        $complete = UserRequest::where('status_id','=',3)->count();

        return response()->json($complete);
        //
    }

    
        public function countDoctors()
    {


        $countDoctors = WorkerProfile::where('worker_category_id','=',1)->count();

        return response()->json($countDoctors);
        //
    }

  public function countNurse()
    {


        $countNurse = WorkerProfile::where('worker_category_id','=',2)->count();

        return response()->json($countNurse);
        //
    }
  public function countClinician()
    {


       $countClinician = WorkerProfile::where('worker_category_id','=',3)->count();

        return response()->json($countClinician);
        //
    }


  public function countPharmacist()
    {


        $countPharmacist = WorkerProfile::where('worker_category_id','=',4)->count();

        return response()->json($countPharmacist);
        //
    }
    
    
    public function testemail(){
        
       $user = new User();
       $user->email = 'georgetmuchiri@yahoo.com'; 

         $user->notify(new SignupActivate($user));
         
         return response()->json('Email has been sent');
        
        
    }
    
    
     public function Monthlyrequest(Request $request)
  {
    $Monthlyrequest = UserRequest::with('category')->select('categiry_id',
            DB::raw('count(id) as Total'),
            DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
            DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
    )->groupBy('months','categiry_id', 'monthKey')->orderBy('months', 'ASC')->get();

     return response()->json($Monthlyrequest);
  }



      public function Workers(Request $request)
  {
    $Monthlyrequest = UserRequest::with('category')->select('categiry_id',
            DB::raw('count(id) as Total'),
            DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
            DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
    )->groupBy('months','categiry_id', 'monthKey')->orderBy('months', 'ASC')->get();

     return response()->json($Monthlyrequest);
  }



 //Average User Ratings
 public function Average(Request $request)
 {
   $Monthlyrevenue = UserRating::select('worker_uuid',
     DB::raw('avg(rating) as Rating'),
     DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
     DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
   )->groupBy('months','worker_uuid', 'monthKey')->orderBy('months', 'ASC')->get();

   return response()->json($Monthlyrevenue);
 }
   

  public function Weeklyrequest(Request $request)
  {
    $Weeklyrequest = UserRequest::with('store')->select('retailer_id',
            DB::raw('count(id) as Total'),
            DB::raw("DATE_FORMAT(created_at,' %D %M %Y') as months"),
            DB::raw("DATE_FORMAT(created_at,'%d') as monthKey")
    )->groupBy('months','retailer_id', 'monthKey')->orderBy('months', 'ASC')->get();

     return response()->json($Weeklyrequest);
  }



  public function Frequency(Request $request)
  {
    $frequency = UserRequest::with('requester')->select('requester_uuid',
      DB::raw('count(id) as Total'))->groupBy('requester_uuid')->distinct()->get();

      // SELECT , sum(quantity) as Total FROM `stock_order_request_issues` group by stock_order_product_id
     return response()->json($frequency);
    
   }




   public function verifyWorker($id){
   
   
    try {
           $verify = WorkerProfile::where('id_number','=',$id)->first();
           $verify->verified=1;
           $verify->save();
              
      return response()->json($verify);
       } catch (\Illuminate\Database\QueryException $e) {
           return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
       }
                 
   
   
}


    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
