<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

use App\User;
use App\Models\Profile;
use App\Models\WorkerProfile;
use App\Models\UserRating;
use App\Models\UserRequest;

class UserController extends Controller
{
   public function index(Request $request)
   {
        if ($request->query('search')) {
            $search = $request->query('search');
            $user= User::where('name', 'LIKE', "%{$search}%")->get();
        } else if ($request->query('type')=='stats') {
            $user = User::whereUser_uuid(Auth::user()->user_uuid)->first();
            $profile = WorkerProfile::whereUser_uuid($user->user_uuid)->first();
            $userRequest= UserRequest::whereRecepient_uuid($user->user_uuid)->whereStatus_id(3)->get();
            $workerRating = UserRating::whereWorker_uuid($user->user_uuid)->get();
            if(count($workerRating) == 0){
                $user->setAttribute('rating', 0);
                $user->setAttribute('reviewers', 0);
            }else{
                $rating = $workerRating->avg('rating');
                $user->setAttribute('rating', $rating);
                $user->setAttribute('reviewers', count($workerRating));
            }
            $user->setAttribute('totalVisits', count($userRequest));
            $user->setAttribute('profile', $profile);
        } else {
            $user= User::orderBy('name', 'DESC')->get();
        }
        return response()->json($user);
       
   }
   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request
    * @return \Illuminate\Http\Response
    */

    public function store(Request $request)
    {
        if($request->query('type')=='image'){
            $rules = [
                'file' => 'image:jpeg,jpg,png|required|file',
                'id' => 'required',
                'name' => 'required',
    
            ];
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json($validator, 422);
            } else {
                if ($request->file('file')->isValid()) {
                    $extension = $request->file('file')->getClientOriginalExtension();
                    $fileName = rand(11111, 99999).'.'.$extension;
                    $request->file('file')->storeAs('pictures', $fileName);
                }
                $user = User::whereId($request->id)->first();
                $user->image = $fileName;
                try {
                    $user->save();
    
                    return response()->json($user);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
                }
            }
        }else{
            $rules = [
                'email'    => 'required',
                'fname'=> 'required',
                'lname'=> 'required',
            ];
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json($validator, 422);
            } else {
                $user = User::find($request->id);
                $user->email = $request->email;
                $user->first_name = $request->fname;
                $user->last_name = $request->lname;
                try {
                    $user->save();
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
                }
                if($request->bio){
                    $profile = WorkerProfile::whereUser_uuid($user->user_uuid)->first();
                    if(is_null($profile)){
                        $profile = new WorkerProfile;
                        $profile->user_uuid = $user->user_uuid;
                    }
                    $profile->bio = $request->bio;
                    $profile->gender_id = $request->gender_id;
                    $profile->id_number = $request->id_number;
                    $profile->worker_category_id = $request->worker_category_id;
                    $profile->worker_sub_category_id = $request->worker_sub_category_id;
                    $profile->licence_number = $request->licence_number;
                    $profile->date_licence_renewal = $request->date_licence_renewal;
                    $profile->qualification = $request->qualification;
                    $profile->specialization = $request->specialization;
                    $profile->residence = $request->residence;
                    $profile->experience_years = $request->experience_years;
                    $profile->profile_pic = $request->profile_pic;
                    try {
                        $profile->save();
                    } catch (\Illuminate\Database\QueryException $e) {
                        return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
                    }
                }
                return response()->json($user);
            }
        }
       
   }
       
   /**
    * Display the specified resource.
    *
    * @param  int  id
    * @return \Illuminate\Http\Response
    */
  
         
    /**
     * Display the specified resource.
     *
     * @param  int  id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::whereId($id)->with('profile')->first();
        return response()->json($user);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  request
     * @param  int  id
     * @return \Illuminate\Http\Response
     */
    
    public function update(Request $request, $id)
    {
        if($request->type == 'offline'){
            $user = User::whereId($id)->first();
            $profile = WorkerProfile::whereUser_uuid($user->user_uuid)->first();

            $profile->active = 0;

            try {
                $profile->save();
                return response()->json($profile);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        } else if ($request->type == 'online'){
            $user = User::whereId($id)->first();
            $profile = WorkerProfile::whereUser_uuid($user->user_uuid)->first();

            $profile->active = 1;

            try {
                $profile->save();
                return response()->json($profile);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        
        }else{
            $rules = [
                'name' => 'required',
            ];
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json($validator, 422);
            } else {
                $user = User::findOrFail($id);
                $user->name = $request->input('name');
                $user->email = $request->input('email');
                $user->role = $request->input('role');
                $user->active = $request->input('active');
                $user->password = $request->input('password');

                try {
                    $user->save();
                    return response()->json($user);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
                }
            }
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json($user, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}