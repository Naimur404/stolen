<?php

namespace App\Http\Controllers;

use App\Services\PathaoApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AddressController extends Controller
{
    private $pathaoApiService;

    public function __construct(PathaoApiService $pathaoApiService)
    {
        $this->pathaoApiService = $pathaoApiService;
    }

    public function parseAddress(Request $request)
    {
        $accessToken = $this->pathaoApiService->getAccessToken();

        // Call the address-parser API
        $response = Http::withToken($accessToken)
            ->post("https://api-hermes.pathao.com/aladdin/api/v1/address-parser", [
                'address' => $request->address,
            ]);

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Failed to parse address'], 400);
    }

    public function createOrder(Request $request)
    {
        $address = "House: 15\nRoad- 1, Lane- 8, Block- A, Halishahar, Chittagong";
        $recipientIdentifier = "01309092748";

        // Parse address to get city and zone details
        $parsedData = $this->pathaoApiService->parseAddress($address);
        $cityId = $parsedData['district_id'];
        $zoneId = $parsedData['zone_id'];

        // Create order with dynamic city and zone
        $orderData = [
            "store_id" => 6173,
            "recipient_name" => "John Doe",
            "recipient_phone" => "01309092748",
            "recipient_address" => $address,
            "recipient_city" => $cityId,
            "recipient_zone" => $zoneId,
            "delivery_type" => 48,
            "item_type" => 2,
            "item_quantity" => 1,
            "item_weight" => 0.5,
            "amount_to_collect" => 500,
        ];

        $response = $this->pathaoApiService->createOrder($orderData);

        return response()->json($response);
    }
}

