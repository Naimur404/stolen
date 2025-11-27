<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\SendMessageLog;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class SendMessageLogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SendMessageLog::with('outlet')
                ->orderBy('created_at', 'desc')
                ->get();
                
            return Datatables::of($data)
                ->editColumn('recipient_info', function ($row) {
                    if ($row->recipient_type === 'all') {
                        return '<span class="badge bg-primary">All Customers (' . $row->recipients_count . ')</span>';
                    } else {
                        $outletName = $row->outlet_name ?? ($row->outlet ? $row->outlet->outlet_name : 'Unknown Outlet');
                        return '<span class="badge bg-info">Outlet: ' . $outletName . ' (' . $row->recipients_count . ')</span>';
                    }
                })
                ->editColumn('response', function ($row) {
                    $response = is_string($row->response) ? json_decode($row->response, true) : $row->response;
                    return '<pre style="font-size: 10px; max-height: 100px; overflow-y: auto;">' . json_encode($response, JSON_PRETTY_PRINT) . '</pre>';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '';
                })
                ->rawColumns(['recipient_info', 'response'])
                ->make(true);
        }

        return view('admin.message.sendMessageLog');
    }

    public function sendMeessageeView()
    {
        return view('admin.message.message');
    }


    public function sendMessage(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'message' => 'required|string|min:5',
            'recipient_type' => 'required|in:all,outlet',
            'outlet_id' => 'nullable|required_if:recipient_type,outlet|exists:outlets,id',
        ]);
    
        // Retrieve and prepare phone numbers based on recipient type
        $customerQuery = Customer::whereNotNull('mobile');
        
        // Filter by outlet if specified
        $outlet = null;
        if ($validatedData['recipient_type'] === 'outlet') {
            $outlet = Outlet::findOrFail($validatedData['outlet_id']);
            // Filter customers by outlet (assuming customers have outlet_id field)
            $customerQuery->where('outlet_id', $validatedData['outlet_id']);
        }
        
        $memberNumbers = $customerQuery->pluck('mobile')
            ->filter(function ($phone) {
                // Validate phone number format
                $validPrefixes = ['014', '013', '016', '015', '019', '018', '017'];
                return strlen($phone) === 11 &&
                       $phone[0] === '0' &&
                       in_array(substr($phone, 0, 3), $validPrefixes);
            })
            ->map(function ($phone) {
                // Add 880 prefix and remove the leading 0
                return '880' . substr($phone, 1);
            })
            ->unique()  // Remove duplicates
            ->toArray();
    
        // Check if there are any valid phone numbers
        if (empty($memberNumbers)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No valid phone numbers found.'
            ], 400);
        }
    
        // Convert numbers array to comma-separated string
        $phoneNumbersStr = implode(',', $memberNumbers);
    
        // SMS API Configuration
        $baseUrl = 'https://sms.apinet.club';
        $endpoint = '/services/sms/sendbulksms'; // Correct endpoint for bulk SMS
        $userId = config('services.sms.user_id', 'farsemac@gmail.com');
        $userPassword = config('services.sms.user_password', 'stolen.com.bd2@');
    
        try {
            // Create authorization header with Base64 encoding
            $authorization = 'Basic ' . base64_encode($userId . ':' . $userPassword);
    
            // Check if the message contains Unicode characters (e.g., Bengali)
            $smsTypeId = preg_match('/[^\x20-\x7E]/', $validatedData['message']) ? 5 : 1;

            $payload = [
                'campaignName' => 'General',
                // 'routeId' => 1,
                'messages' => [
                    [
                        'to' => $phoneNumbersStr,
                        'text' => $validatedData['message'],
                        'smsTypeId' => $smsTypeId
                    ]
                ],
                'refOrderNo' => 'FarseOrder_' . uniqid(),
                'responseType' => 1 // Full response
            ];
    
            // Send SMS using HTTP client with correct content type and auth
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => $authorization
            ])->post($baseUrl . $endpoint, $payload);

    
            // Log the attempt
            SendMessageLog::create([
                'message' => $validatedData['message'],
                'response' => $response->body(),
                'recipient_type' => $validatedData['recipient_type'],
                'outlet_id' => $validatedData['recipient_type'] === 'outlet' ? $validatedData['outlet_id'] : null,
                'outlet_name' => $outlet ? $outlet->outlet_name : null,
                'recipients_count' => count($memberNumbers)
            ]);
    
            // Check response status
            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'SMS sent successfully',
                    'total_recipients' => count($memberNumbers),
                    'response' => $response->json()
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to send SMS',
                    'total_recipients' => count($memberNumbers),
                    'response' => $response->body()
                ], 500);
            }
        } catch (\Exception $e) {
            // Log the exception
            SendMessageLog::create([
                'message' => $validatedData['message'],
                'response' => json_encode(['error' => $e->getMessage()]),
                'recipient_type' => $validatedData['recipient_type'],
                'outlet_id' => $validatedData['recipient_type'] === 'outlet' ? $validatedData['outlet_id'] : null,
                'outlet_name' => $outlet ? $outlet->outlet_name : null,
                'recipients_count' => count($memberNumbers)
            ]);
    
            return response()->json([
                'status' => 'error',
                'message' => 'Exception occurred while sending SMS',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
