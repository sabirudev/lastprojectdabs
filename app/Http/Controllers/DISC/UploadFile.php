<?php

namespace App\Http\Controllers\DISC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UploadFile extends Controller
{
    //
    public static function upload_file(Request $request, $table, $string_var, $id)
    {
        if ($request->hasFile($string_var)) {
            $file = $request->file($string_var);
            $destination_upload = public_path($table . '/' . $string_var . '/');
            if (!file_exists($destination_upload)) {
                File::makeDirectory($destination_upload, 0777, true);
                // mkdir($destination_upload, 0777, true);
            }
            $file->move($destination_upload, $string_var . $id . '.' . $file->getClientOriginalExtension());
            $imageUrl = 'public' . '/' . $table . '/' . $string_var . '/' . $string_var . $id . '.' . $file->getClientOriginalExtension();
            return $imageUrl;
        } else {
            return false;
        }
    }
}
