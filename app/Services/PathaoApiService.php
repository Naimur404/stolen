<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PathaoApiService
{
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;
    protected $username;
    protected $password;


    public function __construct()
    {
        $this->baseUrl = env('PATHAO_BASE_URL');
        $this->clientId = env('PATHAO_CLIENT_ID');
        $this->clientSecret = env('PATHAO_CLIENT_SECRET');
        $this->username = env('PATHAO_EMAIL');
        $this->password = env('PATHAO_PASSWORD');
    }

    // Method to get the access token
    public function getAccessToken()
    {
        // Check if the token is cached and not expired
        // Cache::forget('pathao_access_token');
        if (Cache::has('pathao_access_token')) {
            return Cache::get('pathao_access_token');
        }

        // Request a new access token

        $response = Http::post("{$this->baseUrl}/aladdin/api/v1/issue-token", [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'username' => $this->username,
            'password' => $this->password,
            'grant_type' => 'password',
        ]);

        if ($response->successful()) {
            $accessToken = $response->json('access_token');
            $expiresIn = $response->json('expires_in');

            // Cache the token with an expiry time
            Cache::put('pathao_access_token', $accessToken, $expiresIn);

            return $accessToken;
        }

        throw new \Exception('Could not retrieve access token');
    }

    // Method to parse the address and get city/zone details
    public function parseAddress($address)
    {
        $response = Http::withToken($this->getAccessToken())
            ->post("{$this->baseUrl}/aladdin/api/v1/address-parser", [
                'address' => $address,
            ]);

        if ($response->successful()) {
            return $response->json()['data'];
        }

        throw new \Exception('Failed to parse address: ' . $response->body());
    }

    // Method to create an order
    public function createOrder($orderData)
    {
        $url = "{$this->baseUrl}/aladdin/api/v1/orders";
        $headers = [
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        $response = Http::withHeaders($headers)->post($url, $orderData);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to create order: ' . $response->body());
    }
}
