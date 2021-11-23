<?php

namespace App\Http\Controllers\DISC;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Generateid extends Controller
{
    public static function generate($table)
    {
        $digits = 5;
        $getIntial = DB::table('generate_id')->where('table_name', $table)->first()->id;
        // $unique = uniqid($getIntial) . date('YmdHis') . str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
        $unique = uniqid($getIntial);
        $check = DB::table($table)->where('id', '=', $unique)->first();

        if ($check) {
            return Generateid::generate($table);
        } else {
            DB::table('generate_id_list')->insert([
                "generate_id" => $getIntial,
                "value" => $unique
            ]);
            return $unique;
        }
    }

    public static function generate_pasword($role){
        $intialRole = DB::table('users_role')->where('id', '=', $role)->first()->name;
        $password = "IGDX". Str::upper($intialRole[0]) . Str::upper(Str::random(4));
        return $password;
    }

    public static function generate_token_verifikasi($user_id){
        $random = Str::random(40);
        $data['users_id'] = $user_id;
        $data['token'] = $random;
        $insert = DB::table('users_verification')->insert($data);
        if($insert){
            return $data;
        }else{
            return null;
        }
    }
}
