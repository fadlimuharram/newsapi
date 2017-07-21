<?php

namespace App\Http\Controllers\PublicApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class LoginController extends Controller
{
    public function AttemptLogin(Request $req)
    {
      $validasi = $this->validateLogin($req);
      if ($validasi == 'success') {
        if (Auth::attempt(['email'=>$req->email,'password'=>$req->password])) {
          return $this->generateToken($req);
        }else {
          return response()->json(['condition'=>'fail','messages'=>'anda gagal login']);
        }
      }else {
        return $validasi;
      }
    }

    private function generateToken($request)
    {
      $credentials = $request->only('email', 'password');
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        // all good so return the token
        return response()->json(['condition'=>'success','messages'=>'You have successfully logged in','token'=>$token,'Data'=>$this->checklevel()]);
    }

    private function validateLogin($req)
    {
      $validasi = Validator($req->all(), [
                    'email' => 'required|string|email',
                    'password' => 'required|string'
                  ]);
      if ($validasi->fails()) {
        return response()->json($validasi->messages());
      }
      return 'success';
    }

    private function checklevel()
    {
      if (Auth::user()->level == 'admin') {
        $data = \App\User::where('id',Auth::user()->id)->with('admin')->get()->toArray();
        $kembali['name'] = $data[0]['admin']['name'];
      }elseif (Auth::user()->level == 'public') {
        $data = \App\User::where('id',Auth::user()->id)->with('public_profile')->get()->toArray();
        $kembali['name'] = $data[0]['public_profile']['name'];
      }
      $kembali['email'] = $data[0]['email'];
      $kembali['created_at'] = $data[0]['created_at'];
      $kembali['updated_at'] = $data[0]['updated_at'];

      return $kembali;
    }


}
