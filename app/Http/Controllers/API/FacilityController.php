<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Facility;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        if ($request->query('search')) {
            $search = $request->query('search');
            $Facility = Facility::where('name', 'LIKE', "%{$search}%")->get();
        } else {
            $Facility = Facility::with('user.profile')->orderBy('name', 'DESC')->get();
        }
        return response()->json($Facility);
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
        ];
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator, 422);
        } else {
            $Facility = new Facility;
            $Facility->user_id = $request->input('user_id');
            $Facility->name = $request->input('name');
            $Facility->type = $request->input('type');
            $Facility->level_id = $request->input('level_id');
            $Facility->license_number = $request->input('license_number');
            

            try {
                $Facility->save();
                return response()->json($Facility);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
    }
    public function countFacility(Request $request)
    {
        
       $Facility = Facility::count();
       

       return response()->json(     $Facility);
   }

   
    /**
     * Display the specified resource.
     *
     * @param  int  id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Facility = Facility::whereId($id)->first();
        return response()->json($Facility);
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
           
        ];
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator, 422);
        } else {
            $Facility = Facility::findOrFail($id);
            $Facility->user_id = $request->input('user_id');
            $Facility->name = $request->input('name');
            $Facility->type = $request->input('type');
            $Facility->level_id = $request->input('level_id');
            $Facility->license_number = $request->input('license_number');

            try {
                $Facility->save();
                return response()->json($Facility);
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
            $Facility = Facility::findOrFail($id);
            $Facility->delete();
            return response()->json($Facility, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}