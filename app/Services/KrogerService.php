<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class KrogerService
{
    public static function getAccessToken()
    {
        $clientId = env('KROGER_CLIENT_ID');
        $clientSecret = env('KROGER_CLIENT_SECRET');
        $base64Credentials = base64_encode("$clientId:$clientSecret");

        $response = Http::asForm()->withHeaders([
            'Authorization' => "Basic $base64Credentials",
        ])->post(env('KROGER_API_URL') . '/connect/oauth2/token', [
            'grant_type' => 'client_credentials',
            'scope' => 'product.compact',
        ]);

        return $response->json()['access_token'] ?? null;
    }
}
