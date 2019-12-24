<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\WorkerCategory;

class WorkerCategoryController extends Controller
{
    public function index(Request $request)
    {
        $workerCategory = WorkerCategory::with('workerSubCategory')->orderBy('name', 'DESC')->get();
        
        return response()->json($workerCategory);
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
            $workerCategory = new WorkerCategory;
            $workerCategory->name = $request->input('name');

            try {
                $workerCategory->save();
                return response()->json($workerCategory);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['workerCategory' => 'error', 'message' => $e->getMessage()]);
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
        $workerCategory = WorkerCategory::whereId($id)->first();
        return response()->json($workerCategory);
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
            $workerCategory = WorkerCategory::findOrFail($id);
            $workerCategory->name = $request->input('name');

            try {
                $workerCategory->save();
                return response()->json($workerCategory);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['workerCategory' => 'error', 'message' => $e->getMessage()]);
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
            $workerCategory = WorkerCategory::findOrFail($id);
            //$workerCategory->delete();
            return response()->json($workerCategory, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['workerCategory' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
