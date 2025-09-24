<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QzTrayController extends Controller
{
    public function sign(Request $request)
    {
        $privateKey = file_get_contents(storage_path('app/qz/private-key.pem'));

        $data = $request->input('request');
        $signature = null;

        openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA1);

        return base64_encode($signature);
    }
}
