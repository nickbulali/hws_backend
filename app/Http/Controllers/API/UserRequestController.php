<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use App\Notifications\NewServiceRequest;
use App\Notifications\AcceptServiceRequest;
use App\Notifications\RejectServiceRequest;
use App\Notifications\CompleteServiceRequest;
use App\Notifications\CancelServiceRequest;
use App\Notifications\ServiceRequestStarted;

use App\Models\UserDevice;
use App\Models\UserRequest;
use App\Models\UserFavourite;
use App\Models\UserRating;
use App\Models\Facility;
use App\Models\HospitalList;
use App\Models\UserRequestsDuration;
use App\Models\WorkerPackage;
use App\User;
use Auth;
use DB;
use Carbon\Carbon;

class UserRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->query('type') == 'individualUpcoming') {
            $userRequest = UserRequest::whereRequester_uuid(Auth::user()->user_uuid)->where(function ($query) {
                $query->whereStatus_id(1)->orWhere('status_id', 2)->orWhere('status_id', 5);
            })->with('recipient.healthWorkerProfile.workerCategory', 'recipient.healthWorkerProfile.workerSubCategory', 'status')->orderBy('created_at', 'desc')->paginate(10);
        } else if ($request->query('type') == 'individualHistorical'){
            $userRequest = UserRequest::whereRequester_uuid(Auth::user()->user_uuid)->where(function ($query) {
                $query->whereStatus_id(3)->orWhere('status_id', 4);
            })->with('recipient.healthWorkerProfile.workerCategory', 'recipient.healthWorkerProfile.workerSubCategory', 'status')->orderBy('created_at', 'desc')->paginate(10);
        } else if ($request->query('type') == 'workerUpcoming'){
            $userRequest = UserRequest::whereRecepient_uuid(Auth::user()->user_uuid)->where(function ($query) {
                $query->whereStatus_id(1)->orWhere('status_id', 2)->orWhere('status_id', 5);
            })->with('requester', 'status')->orderBy('created_at', 'desc')->paginate(10);
        } else if ($request->query('type') == 'workerHistorical'){
            $userRequest = UserRequest::whereRecepient_uuid(Auth::user()->user_uuid)->where(function ($query) {
                $query->whereStatus_id(3)->orWhere('status_id', 4);
            })->with('requester', 'status')->orderBy('created_at', 'desc')->paginate(10);
        } else if ($request->query('type') == 'workerStarted'){
            $userRequest = UserRequest::whereRecepient_uuid(Auth::user()->user_uuid)->where(function ($query) {
                $query->whereStatus_id(5);
            })->with('requester', 'status')->orderBy('created_at', 'desc')->paginate(10);
        } else if ($request->query('type') == 'hospitalList'){
            $userRequest = HospitalList::whereRequester_uuid(Auth::user()->user_uuid)->with('recipient', 'healthWorkerProfile.workerCategory', 'healthWorkerProfile.workerSubCategory')->orderBy('created_at', 'asc')->get();
        }

        foreach($userRequest as $user){
            $userDevice = UserDevice::whereUser_uuid($user->recepient_uuid)->first();

            $earthRadius = 6371;
            $latFrom = deg2rad($user->latitude);
            $lonFrom = deg2rad($user->longitude);
            $latTo = deg2rad($userDevice->latitude);
            $lonTo = deg2rad($userDevice->longitude);

            $latDelta = $latTo - $latFrom;
            $lonDelta = $lonTo - $lonFrom;

            $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

            $workerRating = UserRating::whereWorker_uuid($user->recepient_uuid)->get();
            $userRated = UserRating::whereClient_uuid(Auth::user()->user_uuid)->whereWorker_uuid($user->recepient_uuid)->first();
            $userFavourite = UserFavourite::whereClient_uuid(Auth::user()->user_uuid)->whereWorker_uuid($user->recepient_uuid)->first();
           
            if(count($workerRating) == 0){
                $user->setAttribute('rating', 0);
                $user->setAttribute('reviewers', 0);
            }else{
                $rating = $workerRating->avg('rating');
                $user->setAttribute('rating', $rating);
                $user->setAttribute('reviewers', count($workerRating));
            }
            if(!is_null($userRated)){
                $user->setAttribute('hasRated', 1);
            }else{
                $user->setAttribute('hasRated', 0);
            }
            if(!is_null($userFavourite)){
                $user->setAttribute('isFavourite', 1);
            }else{
                $user->setAttribute('isFavourite', 0);
            }

            $user->setAttribute('distance', $angle * $earthRadius);
            $user->setAttribute('workerLocation', [$userDevice->latitude, $userDevice->longitude]);

            if($user->status_id == 5){
                $userRequestsDuration = UserRequestsDuration::whereUser_request_id($user->id)->first();
                $dt = Carbon::parse($userRequestsDuration->start_time); 
                $user->setAttribute('total_duration', $dt->diffForHumans());
            }

            if($user->status_id == 3){
                $userRequestsDuration = UserRequestsDuration::whereUser_request_id($user->id)->first();
                $user->setAttribute('start_time', $userRequestsDuration->start_time);
                $user->setAttribute('end_time', $userRequestsDuration->end_time);
                $user->setAttribute('bill', $userRequestsDuration->bill);
                $user->setAttribute('bill_status', $userRequestsDuration->status);
            }
        }

        return response()->json($userRequest);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->query('type') == 'new') {
            $userRequest = User::whereHas('healthWorkerProfile', function ($query) use ($request) {
                $query->where('worker_category_id', $request->category)->whereActive(1);
            })->whereHas('device', function ($query) use ($request) {
                $query->select(DB::raw('*, ( 6367 * acos( cos( radians('.$request->location['lat'].') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$request->location['lng'].') ) + sin( radians('.$request->location['lat'].') ) * sin( radians( latitude ) ) ) ) AS distance'))
                ->having('distance', '<', 50)
                ->orderBy('distance');

            })
            
            ->with('healthWorkerProfile.workerCategory', 'healthWorkerProfile.workerSubCategory')->orderBy('first_name', 'asc')
                ->paginate(10);

            foreach($userRequest as $user){
                $userDevice = UserDevice::whereUser_uuid($user->user_uuid)->first();

                $earthRadius = 6371;
                $latFrom = deg2rad($request->location['lat']);
                $lonFrom = deg2rad($request->location['lng']);
                $latTo = deg2rad($userDevice->latitude);
                $lonTo = deg2rad($userDevice->longitude);

                $latDelta = $latTo - $latFrom;
                $lonDelta = $lonTo - $lonFrom;

                $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

                $workerRating = UserRating::whereWorker_uuid($user->user_uuid)->get();
                $userRated = UserRating::whereClient_uuid(Auth::user()->user_uuid)->whereWorker_uuid($user->user_uuid)->first();
                
                if(count($workerRating) == 0){
                    $user->setAttribute('rating', 0);
                    $user->setAttribute('reviewers', 0);
                }else{
                    $rating = $workerRating->avg('rating');
                    $user->setAttribute('rating', $rating);
                    $user->setAttribute('reviewers', count($workerRating));
                }

                $user->setAttribute('distance', $angle * $earthRadius);

                $workerPackage= WorkerPackage::whereWorker_category_id($request->category)->first();
                $user->setAttribute('rate', $workerPackage->amount);

            }
        } else if ($request->query('type') == 'filter') { 
            
            $userRequest = User::whereHas('healthWorkerProfile', function ($query) use ($request) {
                if(!is_null($request->genderId)){
                    $query->whereGender_id($request->genderId);
                }
                if(!is_null($request->subGroup)){
                    $query->whereWorker_sub_category_id($request->subGroup);
                }
                $query->where('worker_category_id', $request->category);
            })->whereHas('device', function ($query) use ($request) {
                $query->select(DB::raw('*, ( 6367 * acos( cos( radians('.$request->location['lat'].') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$request->location['lng'].') ) + sin( radians('.$request->location['lat'].') ) * sin( radians( latitude ) ) ) ) AS distance'))
                ->having('distance', '<', $request->distance)
                ->orderBy('distance');
            })
            
            ->with('healthWorkerProfile.workerCategory', 'healthWorkerProfile.workerSubCategory')->orderBy('first_name', 'asc')
                ->paginate(10);
            
            foreach($userRequest as $user){
                $userDevice = UserDevice::whereUser_uuid($user->user_uuid)->first();

                $earthRadius = 6371;
                $latFrom = deg2rad($request->location['lat']);
                $lonFrom = deg2rad($request->location['lng']);
                $latTo = deg2rad($userDevice->latitude);
                $lonTo = deg2rad($userDevice->longitude);

                $latDelta = $latTo - $latFrom;
                $lonDelta = $lonTo - $lonFrom;

                $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

                $workerRating = UserRating::whereWorker_uuid($user->user_uuid)->get();
                $userRated = UserRating::whereClient_uuid(Auth::user()->user_uuid)->whereWorker_uuid($user->user_uuid)->first();
                
                if(count($workerRating) == 0){
                    $user->setAttribute('rating', 0);
                    $user->setAttribute('reviewers', 0);
                }else{
                    $rating = $workerRating->avg('rating');
                    $user->setAttribute('rating', $rating);
                    $user->setAttribute('reviewers', count($workerRating));
                }

                $user->setAttribute('distance', $angle * $earthRadius);

                $workerPackage= WorkerPackage::whereWorker_category_id($request->category)->first();
                $user->setAttribute('rate', $workerPackage->amount);
            }
        } else if ($request->query('type') == 'hospitalList'){
            $rules = [
                'location' => 'required',
                'dateFrom' => 'required',
                'dateTo' => 'required',
                'from' => 'required',
                'to' => 'required',
                'category' => 'required',
                'workerId' => 'required',
            ];
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json($validator, 422);
            } else {
                $recepient = User::whereId($request->workerId)->first();
                $listCheck = HospitalList::whereRequester_uuid(Auth::user()->user_uuid)->whereRecepient_uuid($recepient->user_uuid)->first();
                if(is_null($listCheck)){
                    $userRequest = new HospitalList;
                    $userRequest->requester_uuid = Auth::user()->user_uuid;
                    $userRequest->recepient_uuid = $recepient->user_uuid;
                    $userRequest->longitude = $request->location['lng'];
                    $userRequest->latitude = $request->location['lat'];
                    $userRequest->from_date = $request->dateFrom;
                    $userRequest->to_date = $request->dateTo;
                    $userRequest->from = $request->from;
                    $userRequest->to = $request->to;
                    $userRequest->categiry_id = $request->category;          
        
                    try {
                        $userRequest->save();
                    } catch (\Illuminate\Database\QueryException $e) {
                        return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
                    }
                }else{
                    $userRequest = 'exists';
                }
                
            }
        } else if ($request->query('type') == 'hospitalComplete'){
            $workers = HospitalList::whereRequester_uuid(Auth::user()->user_uuid)->get();
            foreach($workers as $worker){
                $userRequest = new UserRequest;
                $userRequest->requester_uuid = Auth::user()->user_uuid;
                $userRequest->recepient_uuid = $worker->recepient_uuid;
                $userRequest->longitude = $worker->longitude;
                $userRequest->latitude = $worker->latitude;
                $userRequest->from_date = $worker->from_date;
                $userRequest->to_date = $worker->to_date;
                $userRequest->from = $worker->from;
                $userRequest->to = $worker->to;
                $userRequest->categiry_id = $worker->categiry_id;
                $userRequest->status_id = 1;                
    
                try {
                    $userRequest->save();
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
                }

                $user = auth('api')->user();
                $recepient = User::whereUser_uuid($worker->recepient_uuid)->first();
                $recipientModel = User::find($recepient->id);
                $recipientModel->notify(new NewServiceRequest($user, $userRequest));
                
                $userDevice = UserDevice::whereUser_uuid($recepient->user_uuid)->first();
                if(!is_null($userDevice)){
                    $client = new Client(); //GuzzleHttp\Client
                    $headers = [
                        'Authorization' => 'key=AAAAYafBYz8:APA91bG42hm3weq_NaqP4AU-p_KCAhgHd4HkNpTlbvCKO1u6ePKHqCu7uZ1Cip0M_UVhxWQRcGU_bARCWsSDkYWkhNmQcNTtyNnZqJyo70HvGj3R6WRISUVAV0oKXHWJelT4HcSDhrbK',        
                        'Content-Type'        => 'application/json',
                    ];
                    $notification = [
                        "body" => Auth::user()->first_name." ".Auth::user()->last_name,
                        "title" => "Individual Service Request"
                    ];
                    $response = $client->request('POST', 'https://fcm.googleapis.com/fcm/send', [
                        'headers' => $headers,
                        'json' => 
                        [
                            "to" => $userDevice->firebase_token,
                            "collapse_key" => "type_a",
                            "notification" => $notification
                        ]
                    ]);
                }
                $worker->delete();
            }
        } else if ($request->query('type') == 'complete'){
            $rules = [
                'location' => 'required',
                'from' => 'required',
                'to' => 'required',
                'category' => 'required',
                'workerId' => 'required',
            ];
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json($validator, 422);
            } else {
                $recepient = User::whereId($request->workerId)->first();

                $userRequest = new UserRequest;
                $userRequest->requester_uuid = Auth::user()->user_uuid;
                $userRequest->recepient_uuid = $recepient->user_uuid;
                $userRequest->longitude = $request->location['lng'];
                $userRequest->latitude = $request->location['lat'];
                $userRequest->from_date = $request->dateFrom;
                $userRequest->to_date = $request->dateTo;
                $userRequest->from = $request->from;
                $userRequest->to = $request->to;
                $userRequest->categiry_id = $request->category;
                $userRequest->status_id = 1;                
    
                try {
                    $userRequest->save();
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
                }

                $user = auth('api')->user();
                $recipientModel = User::find($request->workerId);
                $recipientModel->notify(new NewServiceRequest($user, $userRequest));
                
                $recepient = User::whereId($request->workerId)->first();
                $userDevice = UserDevice::whereUser_uuid($recepient->user_uuid)->first();
                if(!is_null($userDevice)){
                    $client = new Client(); //GuzzleHttp\Client
                    $headers = [
                        'Authorization' => 'key=AAAAYafBYz8:APA91bG42hm3weq_NaqP4AU-p_KCAhgHd4HkNpTlbvCKO1u6ePKHqCu7uZ1Cip0M_UVhxWQRcGU_bARCWsSDkYWkhNmQcNTtyNnZqJyo70HvGj3R6WRISUVAV0oKXHWJelT4HcSDhrbK',        
                        'Content-Type'        => 'application/json',
                    ];
                    $notification = [
                        "body" => Auth::user()->first_name." ".Auth::user()->last_name,
                        "title" => "Individual Service Request"
                    ];
                    $response = $client->request('POST', 'https://fcm.googleapis.com/fcm/send', [
                        'headers' => $headers,
                        'json' => 
                        [
                            "to" => $userDevice->firebase_token,
                            "collapse_key" => "type_a",
                            "notification" => $notification
                        ]
                    ]);
                }

            }
        }

        return response()->json($userRequest);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->type == 'accept'){
            $userRequest = UserRequest::whereId($id)->first();
            $userRequest->status_id = 2;
        } else if($request->type == 'reject'){
            $userRequest = UserRequest::whereId($id)->first();
            $userRequest->status_id = 4;
        } else if($request->type == 'complete'){
            $userRequest = UserRequest::whereId($id)->first();
            $userRequest->status_id = 3;

            $userRequestsDurations = UserRequestsDuration::whereUser_request_id($id)->first();
            $userRequestsDurations->end_time = Carbon::now();

            try {
                $userRequestsDurations->save();
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }

            $started = Carbon::parse($userRequestsDurations->start_time);
            $ended = Carbon::parse($userRequestsDurations->end_time);

            $totalTime = $started->diffInMinutes($ended);
            $totalTime = $totalTime/60;

            $rate= WorkerPackage::whereWorker_category_id($userRequest->categiry_id)->first();
            $userRequestsDurations->bill = $rate->amount * $totalTime;

            try {
                $userRequestsDurations->save();
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }


        } else if($request->type == 'start'){
            $userRequest = UserRequest::whereId($id)->first();
            $userRequest->status_id = 5;

            $userRequestsDurations = new UserRequestsDuration;
            $userRequestsDurations->start_time = Carbon::now();
            $userRequestsDurations->user_request_id = $id;
            
            try {
                $userRequestsDurations->save();
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }

        } else if($request->type == 'cancel'){
            $userRequest = UserRequest::whereId($id)->first();
            $userRequest->status_id = 4;
        }

        try {
            $userRequest->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }

        if($request->type == 'accept'){
            $user = auth('api')->user();
            $requester = User::whereUser_uuid($userRequest->requester_uuid)->first();
            $requesterModel = User::find($requester->id);
            $requesterModel->notify(new AcceptServiceRequest($user, $userRequest));

            $notification = [
                "body" => Auth::user()->first_name." ".Auth::user()->last_name,
                "title" => "Service Request Accepted"
            ];
        } else if($request->type == 'reject'){
            $user = auth('api')->user();
            $requester = User::whereUser_uuid($userRequest->requester_uuid)->first();
            $requesterModel = User::find($requester->id);
            $requesterModel->notify(new RejectServiceRequest($user, $userRequest));

            $notification = [
                "body" => Auth::user()->first_name." ".Auth::user()->last_name,
                "title" => "Service Request Rejected"
            ];
        } else if($request->type == 'complete'){
            $user = auth('api')->user();
            $requester = User::whereUser_uuid($userRequest->requester_uuid)->first();
            $requesterModel = User::find($requester->id);
            $requesterModel->notify(new CompleteServiceRequest($user, $userRequest));

            $notification = [
                "body" => Auth::user()->first_name." ".Auth::user()->last_name,
                "title" => "Service Request Completed"
            ];
        } else if($request->type == 'start'){
            $user = auth('api')->user();
            $requester = User::whereUser_uuid($userRequest->requester_uuid)->first();
            $requesterModel = User::find($requester->id);
            $requesterModel->notify(new ServiceRequestStarted($user, $userRequest));

            $notification = [
                "body" => Auth::user()->first_name." ".Auth::user()->last_name,
                "title" => "Service Request Started"
            ];
        }
        
        else if($request->type == 'cancel'){
            $user = auth('api')->user();
            $requester = User::whereUser_uuid($userRequest->requester_uuid)->first();
            $requesterModel = User::find($requester->id);
            $requesterModel->notify(new CancelServiceRequest($user, $userRequest));

            $notification = [
                "body" => Auth::user()->first_name." ".Auth::user()->last_name,
                "title" => "Service Request Cancelled"
            ];
        }

        $userDevice = UserDevice::whereUser_uuid($userRequest->requester_uuid)->first();
        if(!is_null($userDevice) && !is_null($userDevice->firebase_token)){
            $client = new Client(); //GuzzleHttp\Client
            $headers = [
                'Authorization' => 'key=AAAAYafBYz8:APA91bG42hm3weq_NaqP4AU-p_KCAhgHd4HkNpTlbvCKO1u6ePKHqCu7uZ1Cip0M_UVhxWQRcGU_bARCWsSDkYWkhNmQcNTtyNnZqJyo70HvGj3R6WRISUVAV0oKXHWJelT4HcSDhrbK',        
                'Content-Type'        => 'application/json',
            ];
            $response = $client->request('POST', 'https://fcm.googleapis.com/fcm/send', [
                'headers' => $headers,
                'json' => 
                [
                    "to" => $userDevice->firebase_token,
                    "collapse_key" => "type_a",
                    "notification" => $notification
                ]
            ]);

        }
        return response()->json($userRequest);
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
