<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\SignupActivate;
use App\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Validator;
use Redirect;
use Response;

class APIController extends Controller
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $user = User::create([
            'first_name' => request('fname'),
            'last_name' => request('lname'),
            'phone_no' => request('phone_no'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'activation_token' => str_random(60)
        ]);

        $role = Role::find($request->account_type);
        $user->attachRole($role);

        $user->notify(new SignupActivate($user));

        return response()->json([
            'message' => 'Account created',
            'user' => $user,
            'status' => 200,
        ]);
    }

    public function login()
    {
        // Check if a user with the specified email exists
        $user = User::whereEmail(request('username'))->first();

        if (! $user) {

            //flash('Wrong email or password')->error();
            return response()->json([
                'message' => 'Wrong email or password',
                'status' => 422,
            ], 422);
        }
        /*
         If a user with the email was found - check if the specified password
         belongs to this user
        */
        if (! \Hash::check(request('password'), $user->password)) {
            return response()->json([
                'message' => 'Wrong email or password',
                'status' => 422,
            ], 422);
        }

        if ($user->active != 1) {
            return response()->json([
                'message' => 'Please activate your account',
                'status' => 422,
            ], 422);
        }

        $secret = \DB::table('oauth_clients')
            ->where('id', env('PASSWORD_CLIENT_ID'))
            ->first()->secret;

        // Send an internal API request to get an access token
        $data = [
            'grant_type' => 'password',
            'client_id' => env('PASSWORD_CLIENT_ID'),
            'client_secret' => $secret,
            'username' => request('username'),
            'password' => request('password'),
        ];

        $request = Request::create('/oauth/token', 'POST', $data);

        $response = app()->handle($request);

        if ($response->getStatusCode() != 200) {
            return response()->json([
                'message' => 'Wrong email or password',
                'status' => 422,
            ], 422);
        }

        // Get the data from the response
        $data = json_decode($response->getContent());

        // Format the final response in a desirable format
        return response()->json([
            'token' => $data->access_token,
            'user' => $user,
            'status' => 200,
        ]);
    }

    public function logout()
    {
        $accessToken = auth()->user()->token();

        $refreshToken = \DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true,
            ]);

        $accessToken->revoke();

        return response()->json(['status' => 200]);
    }

    public function getUser()
    {
        return auth()->user()->load('roles.permissions');
    }

    public function signupActivate($token)
    {
        $user = User::where('activation_token', $token)->first();
        if (!$user) {
            return response()->json([
                'message' => 'This activation token is invalid.'
            ], 404);
        }
        $user->active = true;
        $user->activation_token = '';
        $user->save();
        return redirect('/');
    }
}