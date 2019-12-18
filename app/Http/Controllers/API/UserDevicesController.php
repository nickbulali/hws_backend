<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\UserDevices;
use Auth;

class UserDevicesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->query('search')) {
            $search = $request->query('search');
            $token = UserDevices::where('name', 'LIKE', "%{$search}%")->get();
        } else {
            $token = UserDevices::orderBy('name', 'DESC')->get();
        }
        return response()->json($token);
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
            //'name' => 'required',
        ];
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator, 422);
        } else {
            $user = UserDevices::whereUser_id(Auth::user()->id)->first();
            if (!is_null($user)){
                $token = UserDevices::findOrFail($user->id);
                $token->token = $request->input('fcmToken');

                try {
                    $token->save();
                    return response()->json($token);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
                }
            }else{
                $token = new UserDevices;
                $token->token = $request->input('fcmToken');
                $token->user_id = Auth::user()->id;

                try {
                    $token->save();
                    return response()->json($token);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
                }
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
        $token = UserDevices::whereId($id)->first();
        return response()->json($token);
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
            $token = UserDevices::findOrFail($id);
            $token->name = $request->input('name');

            try {
                $token->save();
                return response()->json($token);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
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
            $token = UserDevices::findOrFail($id);
            $token->delete();
            return response()->json($token, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
