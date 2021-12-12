<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DeviceId;

use Firebase\JWT\JWT;

use App\Models\User;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function deviceRegister(){

        $this->validate($this->request, [
            'device_id'     => 'required',
            'version_number'  => 'required',
            'platform'  => 'required'
        ]);

        $device_register_data = DeviceId::firstOrCreate(['device_id'=>$this->request->input('device_id')],$this->request->all());

        $key = env("JWT_SECRET_KEY");
        $payload = array(
            "device_id" => $device_register_data->id
        );

        $jwt = JWT::encode($payload, $key, 'HS256');

        return response()->json(['jwt'=>$jwt]);
    }

    public function userRegister(){

        $this->validate($this->request, [
            'unique_id'     => 'required',
            'username'  => 'required'
        ]);

        $jwt_token=$this->request->header('credentials');
        if(is_null($jwt_token)){
            return response()->json(['message'=>'JWT token is missing']);
        }

        $key = env("JWT_SECRET_KEY");


        $jwt_token_decoded = JWT::decode($jwt_token,$key,['HS256']);

        if(!isset($jwt_token_decoded->device_id)){
            return response()->json(['message'=>'JWT token error']);
        }
        
        if(!User::where('unique_id',$this->request->input('unique_id'))->exists()){
            $user = new User(
                $this->request->all()
            );
            $user->save();

            $payload = array(
                "user_id" => $user->id,
                "device_id"=>$jwt_token_decoded->device_id
            );
    
            $user_jwt = JWT::encode($payload, $key, 'HS256');
            return response()->json(['user_jwt'=>$user_jwt]);
        }
        else{
            return response()->json(['message'=>'User is already exists']);
        }
    }

    public function login(){

        $this->validate($this->request, [
            'unique_id'     => 'required'
        ]);

        $jwt_token=$this->request->header('credentials');
        if(is_null($jwt_token)){
            return response()->json(['message'=>'JWT token is missing']);
        }

        $key = env("JWT_SECRET_KEY");


        $jwt_token_decoded = JWT::decode($jwt_token,$key,['HS256']);

        if(!isset($jwt_token_decoded->device_id)){
            return response()->json(['message'=>'JWT token error']);
        }
        
        if($user=User::where('unique_id',$this->request->input('unique_id'))->first()){
            $payload = array(
                "user_id" => $user->id,
                "device_id"=>$jwt_token_decoded->device_id
            );
    
            $user_jwt = JWT::encode($payload, $key, 'HS256');
            return response()->json(['user_jwt'=>$user_jwt,'user_info'=>$user]);
        }
        else{
            return response()->json(['message'=>'User not fount']);
        }
    }
}
