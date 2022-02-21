<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

use App\Models\User;

class RegisterController extends Controller
{

    public function create(Request $request){

        try {
            $this->validate($request, [
                'name'      => 'required|max:255',
                'email'     => 'required|max:255|unique:users',
                'password'  => 'required|max:255|min:8',
            ]);

            $data = new User;
            $data->name         = $request->name;
            $data->email        = $request->email;
            $data->password     = Hash::make($request->password);
            $data->api_token    = Str::random(60);
            $data->save();

            return response()->json([
                'status' => 'success',
                'msg'    => 'Data created success',
            ], 200);

        }catch (ValidationException $exception) {

            return response()->json([
                'status' => 'error',
                'msg'    => 'Error',
                'errors' => $exception->errors(),
            ], 400);

        }

    }

}
