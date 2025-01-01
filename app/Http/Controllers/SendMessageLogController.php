<?php

namespace App\Http\Controllers;

use App\Models\Customer;
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
            $data = SendMessageLog::latest()->get();
            return Datatables::of($data)
                ->editColumn('response', function ($row) {
                    return '<pre>' . json_encode($row->response, JSON_PRETTY_PRINT) . '</pre>';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i') : '';
                })
                ->rawColumns(['action', 'response'])
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
        ]);

        // Retrieve and prepare phone numbers
        $memberNumbers = Customer::whereNotNull('mobile')
            ->pluck('mobile')
            ->filter(function ($phone) {
                // Validate phone number format
                $validPrefixes = ['014', '013', '016', '015', '019', '018', '017'];
                return strlen($phone) === 11 &&
                       $phone[0] === '0' &&
                       in_array(substr($phone, 0, 3), $validPrefixes);
            })
            ->take(10)
            ->toArray();
            dump(   $memberNumbers);

        // Check if there are any valid phone numbers
        if (empty($memberNumbers)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No valid phone numbers found.'
            ], 400);
        }

        // SMS API Configuration
        $baseUrl = 'https://sms.apinet.club/sendSms';
        $userId = config('services.sms.user_id', 'farsemac@gmail.com');
        $userPassword = config('services.sms.user_password', 'stolen.com.bd2@');

        try {
            // Prepare query parameters
            $params = [
                'user_id' => $userId,
                'user_password' => $userPassword,
                'route_id' => 1,
                'sms_type_id' => 1, // Plain Text
                'sms_sender' => 'DigitalLab',
                'sms_receiver' => implode(',', $memberNumbers), // All numbers in one string
                'sms_text' => $validatedData['message'],
                // 'sms_category_name' => 'Notification',
                'campaignType' => 'T', // Transactional
                'return_type' => 'JSON',
                'refOrderNo' => 'Order_' . uniqid()
            ];

            // Send SMS using HTTP client
            $response = Http::asForm()->post($baseUrl, $params);

            // Log the attempt
            SendMessageLog::create([
                'message' => $validatedData['message'],
                'response' => $response->body(),
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
