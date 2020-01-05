<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\UserDevice;
use App\Models\UserFavourite;
use App\Models\UserRating;
use App\Models\UserRequest;
use App\User;
use Auth;

class UserFavouriteController extends Controller
{
    public function index(Request $request)
    {
        if ($request->query('search')) {
            $search = $request->query('search');
            $userFavourite = UserFavourite::where('name', 'LIKE', "%{$search}%")->get();
        } else {
            $userFavourite = UserFavourite::whereClient_uuid(Auth::user()->user_uuid)->with('worker.healthWorkerProfile.workerCategory', 'worker.healthWorkerProfile.workerSubCategory')->orderBy('created_at', 'desc')->paginate(10);
        
            foreach($userFavourite as $favourite){
                $userDevice = UserDevice::whereUser_uuid($favourite->worker_uuid)->first();
                $myDevice = UserDevice::whereUser_uuid(Auth::user()->user_uuid)->first();

                $earthRadius = 6371;
                $latFrom = deg2rad($myDevice->latitude);
                $lonFrom = deg2rad($myDevice->longitude);
                $latTo = deg2rad($userDevice->latitude);
                $lonTo = deg2rad($userDevice->longitude);

                $latDelta = $latTo - $latFrom;
                $lonDelta = $lonTo - $lonFrom;

                $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

                $workerRating = UserRating::whereWorker_uuid($favourite->worker_uuid)->get();
                $totalVisits = UserRequest::whereRequester_uuid(Auth::user()->user_uuid)->whereRecepient_uuid($favourite->worker_uuid)->whereStatus_id(3)->count();
                $lastVisit = UserRequest::whereRequester_uuid(Auth::user()->user_uuid)->whereRecepient_uuid($favourite->worker_uuid)->whereStatus_id(3)->orderBy('created_at', 'desc')->first();
                if(count($workerRating) == 0){
                    $favourite->setAttribute('rating', 0);
                    $favourite->setAttribute('reviewers', 0);
                }else{
                    $rating = $workerRating->avg('rating');
                    $favourite->setAttribute('rating', $rating);
                    $favourite->setAttribute('reviewers', count($workerRating));
                }

                $favourite->setAttribute('distance', $angle * $earthRadius);
                $favourite->setAttribute('totalVisits', $totalVisits);
                $favourite->setAttribute('lastVisit', $lastVisit);
                $favourite->setAttribute('workerLocation', [$userDevice->latitude, $userDevice->longitude]);
            }
        }
        return response()->json($userFavourite);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            //'rating' => 'required',
        ];
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator, 422);
        } else {
            $worker = User::whereId($request->workerId)->first();
            
            $userFavourite = new UserFavourite;
            $userFavourite->client_uuid = Auth::user()->user_uuid;
            $userFavourite->worker_uuid = $worker->user_uuid;

            try {
                $userFavourite->save();
                return response()->json($userFavourite);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['userFavourite' => 'error', 'message' => $e->getMessage()]);
            }
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userFavourite = UserFavourite::whereId($id)->first();
        return response()->json($userFavourite);
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
        $rules = [
            'name' => 'required',
        ];
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator, 422);
        } else {
            $userFavourite = UserFavourite::findOrFail($id);
            $userFavourite->name = $request->input('name');

            try {
                $userFavourite->save();
                return response()->json($userFavourite);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['userFavourite' => 'error', 'message' => $e->getMessage()]);
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
            $userFavourite = UserFavourite::findOrFail($id);
            $userFavourite->delete();
            return response()->json($userFavourite, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['userFavourite' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
