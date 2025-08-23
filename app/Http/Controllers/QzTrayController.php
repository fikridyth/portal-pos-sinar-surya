<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QzTrayController extends Controller
{
    public function signMessage(Request $request)
    {
        $message = $request->getContent();

        $privateKeyPath = storage_path('app/public/qztray/certificate-key.pem');
        $privateKey = openssl_pkey_get_private(file_get_contents($privateKeyPath));

        if (!$privateKey) {
            return response("Invalid private key", 500);
        }

        openssl_sign($message, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        openssl_free_key($privateKey);

        return base64_encode($signature);
    }

    public function getCertificate()
    {
        $certPath = storage_path('app/public/qztray/cert.pem');
        if (!file_exists($certPath)) {
            return response("File tidak ditemukan di: $certPath", 404);
        }

        return response()->file($certPath, [
            'Content-Type' => 'application/x-x509-ca-cert'
        ]);
    }
}
