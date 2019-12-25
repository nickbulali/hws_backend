<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\WorkerSubCategory;

class WorkerSubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $workerSubCategory = WorkerSubCategory::orderBy('name', 'DESC')->get();
        
        return response()->json($workerSubCategory);
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
            $workerSubCategory = new WorkerSubCategory;
            $workerSubCategory->name = $request->input('name');

            try {
                $workerSubCategory->save();
                return response()->json($workerSubCategory);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['workerSubCategory' => 'error', 'message' => $e->getMessage()]);
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
        $workerSubCategory = WorkerSubCategory::whereId($id)->first();
        return response()->json($workerSubCategory);
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
            $workerSubCategory = WorkerSubCategory::findOrFail($id);
            $workerSubCategory->name = $request->input('name');

            try {
                $workerSubCategory->save();
                return response()->json($workerSubCategory);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['workerSubCategory' => 'error', 'message' => $e->getMessage()]);
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
            $workerSubCategory = WorkerSubCategory::findOrFail($id);
            //$workerSubCategory->delete();
            return response()->json($workerSubCategory, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['workerSubCategory' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
