<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\SignupActivate;
use Illuminate\Support\Facades\Auth;
use AfricasTalking\SDK\AfricasTalking;
use Validator;
use Redirect;
use Response;

use App\User;

class SMSController extends Controller
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $otp = mt_rand(1000,9999);
        $user = User::create([
            'name' => request('name'),
            'phone_no' => request('phone_no'),
            'activation_token' => $otp
        ]);

        $username = 'sandbox'; // use 'sandbox' for development in the test environment
        $apiKey   = 'db2c1184569bb9461f06b7b9b6bf44619e12452cf4df8073801a7baac2a429b7'; // use your sandbox app API key for development in the test environment
        $AT       = new AfricasTalking($username, $apiKey);

        // Get one of the services
        $sms      = $AT->sms();

        $verify   = $sms->send([
            'to'      => request('phone_no'),
            'message' => 'OTP: '.$otp
        ]);

        print_r($verify);

        return response()->json([
            'message' => 'Account created',
            'user' => $user,
            'status' => 200,
        ]);
    }

    public function login()
    {
        // Check if a user with the specified email exists
        $user = User::wherePhone_no(request('phone_no'))->first();

        if (! $user) {

            //flash('Wrong email or password')->error();
            return response()->json([
                'message' => 'Wrong phone number or password',
                'status' => 422,
            ], 422);
        }
        /*
         If a user with the email was found - check if the specified password
         belongs to this user
        */
        if (! \Hash::check(request('password'), $user->password)) {
            return response()->json([
                'message' => 'Wrong phone number or password',
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
        return auth()->user();
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