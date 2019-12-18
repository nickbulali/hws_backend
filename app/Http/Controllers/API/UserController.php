<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

use App\User;
use App\Models\Profile;
class UserController extends Controller
{
   public function index(Request $request)
   {

     if ($request->query('search')) {
            $search = $request->query('search');
            $user= User::where('name', 'LIKE', "%{$search}%")->get();
        } else {
            $user= User::orderBy('name', 'DESC')->get();
        }
        return response()->json($user);
       
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
           'role_id' => 'required',
           'email'    => 'required',
           'password' => 'required',
           'fname'=> 'required',
           'lname'=> 'required',
           'phone_number'=> 'required',
       ];
       $validator = \Validator::make($request->all(), $rules);
       if ($validator->fails()) {
           return response()->json($validator, 422);
       } else {
           $user = new User;
           $user->email = $request->input('email');
           $user->role_id = $request->input('role_id');
           $user->active = 1;
           $user->password = bcrypt($request->input('password'));
           //$user->remember_token = $request->input('remember_token');
           try {
            $user->save();
            $profile = new Profile;
            $profile->user_id = $user->id;
            $profile->fname = $request->input('fname');
            $profile->lname = $request->input('lname');
            $profile->phone_number = $request->input('phone_number');
            
                    $profile->save();
                return response()->json($user);
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
  
         
    /**
     * Display the specified resource.
     *
     * @param  int  id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::whereId($id)->with('profile')->first();
        return response()->json($user);
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
            $user = User::findOrFail($id);
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->role = $request->input('role');
            $user->active = $request->input('active');
            $user->password = $request->input('password');

            try {
                $user->save();
                return response()->json($user);
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
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json($user, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}