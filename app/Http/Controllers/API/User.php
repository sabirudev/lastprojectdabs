<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DISC\AuthAPI;
use App\Http\Controllers\DISC\JsonReturn;
use App\Http\Controllers\DISC\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class User extends Controller
{
    //

    public function submit_data(Request $request)
    {
        $table = "users";
        if (AuthAPI::get_auth_api($request)) {
            $data = $request->all();
            $check = DB::table($table)->where('email', '=', $data['email'])->where('created_at', '<=', date('Y-m-d') . " 23:59:59")->get();
            if ($check->count() > 0) {
                return JsonReturn::failedReturn("Email anda sudah digunakan pada hari ini", $table, $request);
            } else {
                $data['token'] = Str::random(60);
                $data['ip_address'] = $request->ip();
                $sendEmail = SendEmail::send_email($data);
                if ($sendEmail == 'success') {
                    $id = DB::table($table)->insertGetId($data);
                    $data = DB::table($table)->where('id', '=', $id)->first();
                    return JsonReturn::successReturn("Terimaksih sudah mengikuti event ini, Check email kamu untuk lebih lanjut", $data, $table, $request);
                } else {
                    return JsonReturn::failedReturn("Email anda tidak valid, cek email anda kembali", $table, $request);
                }
            }
        } else {
            return JsonReturn::failedReturn('Unauthorized', $table, $request);
        }
    }


    public function submit_data_email(Request $request)
    {
        $table = "users";
        if (AuthAPI::get_auth_api($request)) {
            $data = $request->all();
            $data['token'] = Str::random(60);
            $data['ip_address'] = $request->ip();
            $sendEmail = SendEmail::send_email($data);
            if ($sendEmail == 'success') {
                $id = DB::table($table)->insertGetId($data);
                $data = DB::table($table)->where('id', '=', $id)->first();
                return JsonReturn::successReturn("Terimaksih sudah mengikuti event ini, Check email kamu untuk lebih lanjut", $data, $table, $request);
            } else {
                return JsonReturn::failedReturn($sendEmail, $table, $request);
            }
        } else {
            return JsonReturn::failedReturn('Unauthorized', $table, $request);
        }
    }

    public function post_score(Request $request)
    {
        $table = "users";
        if (AuthAPI::get_auth_api($request)) {
            $data = $request->all();
            $check = DB::table($table)->where('id', '=', $data['id'])->where('status', '=', 0)->first();
            if ($check) {
                $data['status'] = 1;
                DB::table($table)->where('id', '=', $data['id'])->update($data);
                $getAlldata = DB::table($table)->orderByDesc('score')->get();
                $index = 1;
                $rank = 0;
                foreach ($getAlldata as $key => $value) {
                    if ($value->id == $data['id']) {
                        $rank = $index;
                    }
                    $index++;
                }
                $returnData['rank'] = $rank;
                $returnData['all_player'] = $getAlldata->count();
                return JsonReturn::successReturn('Success ' . $request->method() . ' ' . $table, $returnData, $table, $request);
            } else {
                return JsonReturn::failedReturn('Score already submited', $table, $request);
            }
        } else {
            return JsonReturn::failedReturn('Unauthorized', $table, $request);
        }
    }


    public function check_data(Request $request)
    {
        $table = "users";
        if (AuthAPI::get_auth_api($request)) {
            $data = $request->all();
            $checIndex = DB::table($table)->where('token', '=', $data['token'])->where('status', '=', 0)->first();
            if ($checIndex) {
                return JsonReturn::successReturn('Sueccess ' . $request->method() . ' ' . $table, $checIndex, $table, $request);
            } else {
                return JsonReturn::failedReturn('Sesi anda sudah expired atau tidak terdaftar', $table, $request);
            }
            // return JsonReturn::successReturn('Fetch message', $getChat, $table, $request);
        } else {
            return JsonReturn::failedReturn('Unauthorized', $table, $request);
        }
    }
}
