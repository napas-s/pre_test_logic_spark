<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request){

        try{

            $this->validate($request, [
                'email'     => 'required|max:255',
                'password'  => 'required|max:255|min:8',
            ]);


            $user = User::where('email', $request->email)->first();

            if(empty($user)){
                return response()->json([
                    'status' => 'fail',
                    'msg'    => 'These credentials do not match our records',
                ], 401);
            }else{
                if(Hash::check($request->password, $user->password)){

                    $apikey = base64_encode(Str::random(60));
                    User::where('email', $request->email)->update(['api_token' => "$apikey"]);
                    return response()->json([
                        'status' => 'success',
                        'api_token' => $apikey,
                        'msg' => $user->name.' Login Sucess'
                        ]
                    );

                }else{

                    return response()->json([
                        'status' => 'fail',
                        'msg'    => 'User login Fail',
                    ], 401);

                }
            }

        }catch (ValidationException $exception) {

            return response()->json([
                'status' => 'error',
                'msg'    => 'Error',
                'errors' => $exception->errors(),
            ], 400);

        }
    }
}
