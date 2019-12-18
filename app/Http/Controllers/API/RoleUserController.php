<?php
namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\User;
use App\Models\Role;
use App\Models\RoleUser;
use Illuminate\Http\Request;
class RoleUserController extends Controller
{
    public function index()
    {
        $usersRoles = RoleUser::all();
        return response()->json($usersRoles);
    }
    public function attach(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'role_id' => 'required',
        ];
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator, 422);
        } else {
            $user = User::find($request->input('user_id'));
            $role = Role::find($request->input('role_id'));
            try {
                $roleUser = RoleUser::create([
                    'user_id' => $request->input('user_id'),
                    'role_id' => $request->input('role_id'),
                ]);
                return response()->json($roleUser);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
    }
    public function detach(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'role_id' => 'required',
        ];
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator, 422);
        } else {
            $role = Role::find($request->input('role_id'));
            $user = User::find($request->input('user_id'));
            try {
                $user->detachRole($role);
                return response()->json(['message' => 'Item Successfully deleted']);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
    }
}
