<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use App\Notifications\NewServiceRequest;

use App\Models\UserDevice;
use App\Models\UserRequest;
use App\Models\Facility;
use App\User;
use Auth;
use DB;

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
            $userRequest = UserRequest::whereRequester_uuid(Auth::user()->user_uuid)->whereStatus_id(1)->with('recipient.healthWorkerProfile.workerCategory', 'recipient.healthWorkerProfile.workerSubCategory', 'status')->paginate(10);
        } else if ($request->query('type') == 'individualHistorical'){

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

            $user->setAttribute('distance', $angle * $earthRadius);
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
                $query->where('worker_category_id', $request->category);
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

                $user->setAttribute('distance', $angle * $earthRadius);
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

                $user->setAttribute('distance', $angle * $earthRadius);
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
