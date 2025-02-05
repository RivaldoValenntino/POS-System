<?php

namespace App\Http\Controllers;

use App\Services\KrogerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KrogerController extends Controller
{
        
    public function testKrogerApi()
    {
        $token = KrogerService::getAccessToken();

        if (!$token) {
            return response()->json(['error' => 'Failed to get access token'], 400);
        }

        return response()->json(['access_token' => $token]);
    }
    public function redirectToKroger()
    {
        $query = http_build_query([
            'client_id' => env('KROGER_CLIENT_ID'),
            'redirect_uri' => env('KROGER_REDIRECT_URI'),
            'response_type' => 'code',
            'scope' => 'product.compact'
        ]);

        return redirect(env('KROGER_API_URL') . '/connect/oauth2/authorize?' . $query);
    }
    public function handleKrogerCallback(Request $request)
    {
        $code = $request->query('code');

        if (!$code) {
            return response()->json(['error' => 'Authorization code not provided'], 400);
        }

        $response = Http::asForm()->withHeaders([
            'Authorization' => 'Basic ' . base64_encode(env('KROGER_CLIENT_ID') . ':' . env('KROGER_CLIENT_SECRET')),
        ])->post(env('KROGER_API_URL') . '/connect/oauth2/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => env('KROGER_REDIRECT_URI'),
        ]);

        $data = $response->json();

        return response()->json($data);
    }

}
