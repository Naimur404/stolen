<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\SendMessageLog;
use GuzzleHttp\Client;

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

    public function sendMeessageeView(){
        return view('admin.message.message');
    }


    public function sendMessage(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'message' => 'required|string|min:5',
        ]);

        // Retrieve phone numbers and prepare them for SMS
        $memberNumbers = Customer::whereNotNull('mobile')
            ->pluck('mobile')
            ->map(function ($phone) {
                // Validate and format phone numbers to include country code (88)
                return (strlen($phone) === 11 && $phone[0] === '0') ? '88' . $phone : null;
            })
            ->filter() // Remove invalid or null numbers
            ->toArray();

        if (empty($memberNumbers)) {
            return response()->json(['status' => 'error', 'message' => 'No valid phone numbers found.'], 400);
        }

        $receiverNumbers = implode(',', $memberNumbers);

        // SMS API configuration
        $url = "http://services.smsnet24.com/sendSms";

        $payload = [
            'sms_receiver' => $receiverNumbers,
            'sms_text' => $request->input('message'),
            'campaignType' => 'T',
            'user_password' => 'stolen.com.bd2@',
            'user_id' => 'farsemac@gmail.com'
        ];

        // Send the request using Guzzle HTTP client
        try {
            // Send the SMS request
            $client = new Client();
            $response = $client->post($url, ['form_params' => $payload]);

            // Parse the response
            $responseBody = json_decode($response->getBody(), true);

            // If the response is null, consider it as success
            if ($responseBody === null) {
                // Log the success (null response means no error)
                SendMessageLog::create([
                    'message'  => $validatedData['message'],
                    'response' => json_encode(['status' => 'success', 'message' => 'No response from API']),
                ]);

                return response()->json([
                    'status' => 'success',
                    'data'   => ['status' => 'success', 'message' => 'No response from API'],
                ], 200);
            }

            // If the response is not null, check if there's an error in it
            if ($responseBody !== null) {
                // Log the error response
                SendMessageLog::create([
                    'message'  => $validatedData['message'],
                    'response' => json_encode(['error' => $responseBody]),
                ]);

                return response()->json([
                    'status'  => 'error',
                    'message' => 'Failed to send SMS. Error details: ' . json_encode($responseBody),
                ], 500);
            }

            // // Log successful response if no errors were found
            // SendMessageLog::create([
            //     'message'  => $validatedData['message'],
            //     'response' => json_encode($responseBody),
            // ]);

            // return response()->json([
            //     'status' => 'success',
            //     'data'   => $responseBody,
            // ], 200);

        } catch (\Exception $e) {
            // Catch any other exceptions (network issues, etc.)
            SendMessageLog::create([
                'message'  => $validatedData['message'],
                'response' => json_encode(['error' => $e->getMessage()]),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to send SMS. ' . $e->getMessage(),
            ], 500);
        }
    }

}
