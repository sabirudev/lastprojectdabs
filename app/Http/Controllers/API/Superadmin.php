<?php

namespace App\Http\Controllers\API;

use App\Exports\UserExport as ExportsUserExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DISC\AuthAPI;
use App\Http\Controllers\DISC\JsonReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class Superadmin extends Controller
{
    //
    public function export_user(Request $request)
    {
        $table = 'users';
        if (AuthAPI::get_auth_api($request)) {
            $data = $request->all();
            $fileName = $table . date('ymdHis') . ".xlsx";
            $path = $table . '/' . $fileName;
            $imageUrl = url('public/' . $table) . '/' . $fileName;
            Excel::store(new ExportsUserExport, $path, 'real_public');
            return JsonReturn::successReturn("Succes get Career Data", $imageUrl, $table, $request);
        } else {
            return JsonReturn::failedReturn('Unauthorized', $table, $request);
        }
    }
    public function analytics(Request $request)
    {
        $table = 'users';
        if (AuthAPI::get_auth_api($request)) {
            $data = $request->all();
            $data['unique_player'] = DB::table($table)->distinct()->count('email');
            $data['total_play'] = DB::table($table)->where('status', '=', 1)->count();
            $data['email_play'] = number_format((float)(DB::table($table)->count() / $data['unique_player']), 2, '.', '');
            return JsonReturn::successReturn("Succes get Career Data", $data, $table, $request);
        } else {
            return JsonReturn::failedReturn('Unauthorized', $table, $request);
        }
    }
}
