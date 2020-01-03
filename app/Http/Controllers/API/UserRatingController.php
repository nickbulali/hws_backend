<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\UserRating;
use App\User;
use Auth;

class UserRatingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->query('search')) {
            $search = $request->query('search');
            $userRating = UserRating::where('name', 'LIKE', "%{$search}%")->get();
        } else {
            $userRating = UserRating::orderBy('name', 'DESC')->get();
        }
        return response()->json($userRating);
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
            
            $userRating = new UserRating;
            $userRating->client_uuid = Auth::user()->user_uuid;
            $userRating->worker_uuid = $worker->user_uuid;
            $userRating->rating = $request->userRating;
            $userRating->comment = $request->userComment;

            try {
                $userRating->save();
                return response()->json($userRating);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['userRating' => 'error', 'message' => $e->getMessage()]);
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
        $userRating = UserRating::whereId($id)->first();
        return response()->json($userRating);
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
            $userRating = UserRating::findOrFail($id);
            $userRating->name = $request->input('name');

            try {
                $userRating->save();
                return response()->json($userRating);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['userRating' => 'error', 'message' => $e->getMessage()]);
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
            $userRating = UserRating::findOrFail($id);
            $userRating->delete();
            return response()->json($userRating, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['userRating' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
