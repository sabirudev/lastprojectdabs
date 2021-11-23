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
            $data['token'] = Str::random(60);
            $data['ip_address'] = $request->ip();
            $sendEmail = SendEmail::send_email($data);
            if ($sendEmail == 'success') {
                DB::table($table)->insert($data);
                return JsonReturn::successReturn("Terimaksih sudah mengikuti event ini, Check email kamu untuk lebih lanjut", $data, $table, $request);
            } else {
                return JsonReturn::failedReturn('Email anda tidak benar, periksa kembali email anda', $table, $request);
            }
            // return JsonReturn::successReturn('Fetch message', $getChat, $table, $request);
        } else {
            return JsonReturn::failedReturn('Unauthorized', $table, $request);
        }
    }

    public function post_score(Request $request)
    {
        $table = "aanwijzing_chat";
        if (AuthAPI::get_auth_api($request)) {
            $data = $request->all();
            $data['status'] = 1;
            DB::table($table)->where('id', '=', $data['id'])->update($data);
            $getAlldata = DB::table($table)->orderByDesc('score')->get();
            $user['rank'] = $getAlldata->search(function ($dataGet) {
                return $data['id'] === $data['id'];
            });
            // return JsonReturn::successReturn('Fetch message', $getChat, $table, $request);
        } else {
            return JsonReturn::failedReturn('Unauthorized', $table, $request);
        }
    }


    public function chec_data(Request $request)
    {
        $table = "aanwijzing_chat";
        if (AuthAPI::get_auth_api($request)) {
            $data = $request->all();
            // $getChat = DB::table($table)->where('networking_id', '=', $data['networking_id'])->orderByDesc('created_at')->take(25)->get();
            $data = AanwjzingChat::where('aanwijzing_id', '=', $data['id'])->with('vendor', 'user')->get();
            return JsonReturn::successReturn('fetch message', $data, $table, $request);
            // return JsonReturn::successReturn('Fetch message', $getChat, $table, $request);
        } else {
            return JsonReturn::failedReturn('Unauthorized', $table, $request);
        }
    }
}
