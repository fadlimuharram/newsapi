<?php

namespace App\Http\Controllers\AdminApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Admin;
use App\Public_profile;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class RegisterController extends Controller
{

    public function register(Request $req)
    {
      $validasi = $this->validateRegister($req);
      if ($validasi == 'success') {

        $this->createUser($req);
        $credentials = $req->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['condition'=>'fail','messages' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['condition'=>'fail','messages' => 'could_not_create_token'], 500);
        }

        $kembali['condition'] = 'success';
        $kembali['token'] = $token;
        $kembali['data']['name'] = $req->name;
        $kembali['data']['email'] = $req->email;
        $kembali['data']['level'] = $req->level;


        return response()->json($kembali);
      }else {
        return $validasi;
      }

      return response()->json(['condition'=>'fail','messages'=>'something went wrong']);
    }

    private function validateRegister($req)
    {
      $validasi = Validator($req->all(), [
                    'email' => 'required|string|email|max:100|unique:users',
                    'name' => 'required|string|max:50',
                    'level'=>'required',
                    'password' => 'required|string|min:6',
                  ]);
      $kembali['condition'] = 'fail';
      $kembali['messages'] = 'Validation Error';
      $kembali['error'] = $validasi->messages();
      return ($validasi->fails()) ? response()->json($kembali) : 'success';
    }


    private function createUser($req)
    {
      try {
        $dataFromToken = JWTAuth::setToken($req->header('Authorization'))->parseToken()->authenticate();

      } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        return response()->json($e->getMessage(),$e->getStatusCode());
      }


        $usr = User::create([
                   'email' => $req->email,
                   'level' => $req->level,
                   'byadmin' => $dataFromToken['id'],
                   'password' => bcrypt($req->password),
               ]);
        if ($req->level == 'admin') {
            Admin::create([
              'name'=>$req->name,
              'user_id'=>$usr->id
            ]);
        }elseif ($req->level == 'public') {
            Public_profile::create([
              'name'=>$req->name,
              'user_id'=>$usr->id
            ]);
        }


    }

}
