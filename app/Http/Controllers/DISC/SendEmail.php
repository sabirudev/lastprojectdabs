<?php

namespace App\Http\Controllers\DISC;

use App\Http\Controllers\Controller;
use App\Mail\OtpEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\DISC\Generateid;
use App\Mail\ForgotPassword;
use App\Mail\PasswordEmail;
use App\Mail\SendPrize;
use App\Mail\SendPrizeGojek;
use App\Mail\SendToken;
use App\Mail\VerifikasiEmail;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SendEmail extends Controller
{

    public static function send_email($users)
    {
        try {
            Mail::to($users->email)->send(new SendToken([
                "unique_id" => $users->token,
                "fullname" => $users->fullname,
            ]));
            return "success";
        } catch (Exception $ex) {
            // Debug via $ex->getMessage();
            return $ex->getMessage();
        }
    }
}
