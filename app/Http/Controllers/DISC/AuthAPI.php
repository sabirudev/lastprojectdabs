<?php

namespace App\Http\Controllers\DISC;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthAPI extends Controller
{
    public static function get_auth_api(Request $request)
    {
        $authorization = $request->bearerToken();
        $getAuth = DB::table('auth_token_api')->first();
        if ($authorization == $getAuth->token) {
            return true;
        } else {
            return false;
        }
    }

    public static function set_token_login($data)
    {
        $table = 'users_sessions';
        $insert['users_id'] = $data->id;
        $insert['token'] = Str::random(60);
        $dateNow = date("Y-m-d H:i:s");
        $insert['valid_until'] = date("Y-m-d H:i:s", strtotime("$dateNow +15 days"));
        $check = DB::table('users_sessions')->where('users_id', '=', $data->id)->get()->toArray();
        if (count($check) > 0) {
            foreach ($check as $key => $value) {
                $update['status'] = 0;
                DB::table($table)->where('id', '=', $value->id)->update($update);
            }
        }
        $insertData = DB::table($table)->insert($insert);
        if ($insertData) {
            return $insert['token'];
        } else {
            return null;
        }
    }

    public static function get_data_users($token)
    {
        $table = 'users_sessions';
        $getToken = DB::table($table)->where('token', '=', $token)->first();
        if ($getToken) {
            $getUser = DB::table('users')->where('id', '=', $getToken->users_id)->get()->except('password')->first();
            $getUser->session_token = $token;
            $getUser->detail = DB::table('users_details')->where('users_id', '=', $getToken->users_id)->first();
            return $getUser;
        } else {
            return null;
        }
    }
}
