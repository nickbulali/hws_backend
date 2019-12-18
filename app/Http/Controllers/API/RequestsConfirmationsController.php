<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\RequestConfirmations;

class RequestConfirmationsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->query('search')) {
            $search = $request->query('search');
            $requestconfirmation = RequestConfirmations::where('name', 'LIKE', "%{$search}%")->get();
        } else {
            $requestconfirmation = RequestConfirmations::orderBy('name', 'DESC')->get();
        }
        return response()->json($requestconfirmation);
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
            'name' => 'required',
        ];
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator, 422);
        } else {
            $requestconfirmation = new RequestConfirmations;
    
            $requestconfirmation->user_id = $request->input('user_id');
            $requestconfirmation->status_id = $request->input('status_id');

            try {
                $requestconfirmation->save();
                return response()->json($requestconfirmation);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
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
        $requestconfirmation = RequestConfirmations::whereId($id)->first();
        return response()->json($requestconfirmation);
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
            $requestconfirmation = RequestConfirmations::findOrFail($id);
    
            $requestconfirmation->user_id = $request->input('user_id');
            $requestconfirmation->status_id = $request->input('status_id');

            try {
                $requestconfirmation->save();
                return response()->json($requestconfirmation);
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
            $requestconfirmation = RequestConfirmations::findOrFail($id);
            $requestconfirmation->delete();
            return response()->json($requestconfirmation, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
