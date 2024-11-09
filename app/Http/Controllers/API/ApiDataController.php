<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Outlet;
use App\Models\OutletExchange;
use App\Models\OutletExchangeDetails;
use App\Models\OutletInvoice;
use App\Models\OutletInvoiceDetails;
use App\Models\OutletPayment;
use App\Models\OutletStock;
use App\Models\PaymentMethod;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiDataController extends Controller
{
    public function getUserApi(Request $request)
    {
        // Determine the outlet ID
        // $outletId = Auth::user()->outlet_id ?? Outlet::orderBy('id', 'desc')->first('id')->id;
        $search = $request->search;

        // Check the user role and set the base query for customers
        // if (Auth::user()->hasRole(['Super Admin', 'Admin'])) {
            $query = Customer::query();
        // } else {
        //     $query = Customer::where('outlet_id', $outletId);
        // }

        // Apply search filter if provided
        if (!empty($search)) {
            $query->where('mobile', 'like', '%' . $search . '%');
        }

        // Limit the results to 5
        $customers = $query->limit(5)->get();

        // Prepare the response
        $response = $customers->map(function ($customer) {
            return [
                "id" => $customer->id,
                "name" => $customer->name,
                "mobile" => $customer->mobile,
                "address" => $customer->address,
                "points" => $customer->points,
            ];
        });

        return response()->json($response);
    }

    public function getOutletStockApi(Request $request)
    {
        // Retrieve the outlet ID, using the authenticated user's outlet ID if available, or fallback to the latest outlet.
        $outletId = Auth::user()->outlet_id ?? Outlet::orderBy('id', 'desc')->first()->id;

        // Check for search query in the request
        $search = $request->search;

        // Base query for outlet stocks with quantity greater than zero
        $query = DB::table('outlet_stocks')
            ->where('outlet_id', $outletId)
            ->where('outlet_stocks.quantity', '>', 0)
            ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
            ->select(
                'outlet_stocks.id as id',
                'outlet_stocks.quantity as quantity',
                'outlet_stocks.price as price',
                'medicines.category_id as category_id',
                'outlet_stocks.size as size',
                'medicines.medicine_name as medicine_name',
                'medicines.id as medicine_id'
            )
            ->limit(5);

        // Add search condition if search term is provided
        if ($search) {
            $query->where('medicines.medicine_name', 'like', '%' . $search . '%');
        }

        // Execute query
        $medicines = $query->get();

        // Prepare response
        $response = [];
        foreach ($medicines as $medicine) {
            // Fetch category name
            $category = Category::find($medicine->category_id);

            // Populate response array
            $response[] = [
                "stock_id" => $medicine->id,
                "product_id" => $medicine->medicine_id,
                "name" => $medicine->medicine_name,
                "category_name" => $category->category_name ?? 'Unknown',
                "size" => $medicine->size,
                "quantity" => $medicine->quantity,
                "price" => $medicine->price
            ];
        }

        return response()->json($response);
    }
    public function getInvoicesApi(Request $request)
    {
        // Fetch pagination, sorting, and filtering parameters from request
        $page = (int) $request->get("page", 1);
        $row_per_page = (int) $request->get("per_page", 10);
        $searchValue = $request->get("search", '');
        $paymentMethod = $request->get("payment_method", '');

        // Calculate offset for pagination
        $start = ($page - 1) * $row_per_page;
        $outlet_id = Auth::user()->outlet_id ?? Outlet::orderBy('id', 'desc')->first()->id;
        // Base query with search and filter conditions
        $query = OutletInvoice::with('customer')
        ->when(auth()->user()->hasRole('Super Admin'), function ($q) {
            return $q;
        }, function ($q) use ($outlet_id) {
            return $q->where('outlet_id', $outlet_id);
        })
            ->when($searchValue, function ($q) use ($searchValue) {
                $q->whereHas('customer', function ($q) use ($searchValue) {
                    $q->where('mobile', 'like', "%$searchValue%");
                })
                ->orWhere('id', 'like', "%$searchValue%")
                ->orWhereDate('sale_date', 'like', "%$searchValue%");
            })
            ->when($paymentMethod, function ($q) use ($paymentMethod) {
                $q->where('payment_method_id', PaymentMethod::where('method_name', $paymentMethod)->first()->id);
            });

        // Count total records for pagination
        $totalRecords = $query->count();

        // Apply pagination and retrieve data
        $invoices = $query->skip($start)->take($row_per_page)->orderBy('id', 'desc')->get();

        // Prepare data for JSON response
        $data_arr = $invoices->map(function ($invoice) {
            return [
                "id" => $invoice->id,
                "sale_date" => Carbon::parse($invoice->sale_date)->format('d-m-Y'),
                "outlet_name" => Outlet::getOutletName($invoice->outlet_id),
                "mobile" => $invoice->customer->mobile ?? '',
                "payment_method_id" => PaymentMethod::getPayment($invoice->payment_method_id),
                "grand_total" => $invoice->total_with_charge,
                "paid_amount" => $invoice->paid_amount,
                "sold_by" => User::getUser($invoice->added_by),
            ];
        })->toArray();

        // Response structure with total records count
        return response()->json([
            "status" => "success",
            "data" => $data_arr,
            "total_records" => $totalRecords,
            "current_page" => $page,
            "per_page" => $row_per_page,
        ]);
    }

    public function sales_details_api($id)
{
    $salesReturn = OutletInvoice::where('id', $id)->first();
    if (!$salesReturn) {
        return response()->json(['error' => 'Sales return not found'], 404);
    }

    $salesreturndetails = OutletInvoiceDetails::where('outlet_invoice_id', $salesReturn->id)->get();
    // $saledetails = OutletPayment::where('invoice_id', $id)->get();

    return response()->json([
        'salesReturn' => $salesReturn,
        'saleDetails' => $salesreturndetails,

    ], 200);
}

public function apiDashboard()
{
    $isAdmin = auth()->user()->hasrole(['Super Admin', 'Admin']);

    // Better outlet_id handling
    $outlet_id = $isAdmin ? null : (Auth::user()->outlet_id ?? Outlet::orderby('id', 'desc')->first()->id);

    $today = Carbon::now();
    $startOfMonth = $today->copy()->startOfMonth();
    $endOfMonth = $today->copy()->endOfMonth();

    // Improved filters function to handle multiple conditions
    $filters = function ($query, $conditions = []) {
        if (empty($conditions)) {
            return $query;
        }

        if (is_array($conditions[0])) {
            // Multiple conditions
            foreach ($conditions as $condition) {
                if (count($condition) === 2) {
                    $query->where($condition[0], $condition[1]);
                } elseif (count($condition) === 3) {
                    $query->where($condition[0], $condition[1], $condition[2]);
                }
            }
        } else {
            // Single condition
            if (count($conditions) === 2) {
                $query->where($conditions[0], $conditions[1]);
            } elseif (count($conditions) === 3) {
                $query->where($conditions[0], $conditions[1], $conditions[2]);
            }
        }

        return $query;
    };

    // Helper functions with proper condition handling
    $getCount = function($model, $conditions = []) use ($filters) {
        return $filters($model::query(), $conditions)->count();
    };

    $getSum = function($model, $field, $conditions = []) use ($filters) {
        return $filters($model::query(), $conditions)->sum($field);
    };

    try {
        $response = [
            'customers' => $getCount(Customer::class,
                $isAdmin ? [] : [['outlet_id', $outlet_id]]),

            'products' => $getCount(OutletStock::class,
                $isAdmin ? [['quantity', '>', 0]] : [['outlet_id', $outlet_id], ['quantity', '>', 0]]),

            'stocks' => $getCount(OutletStock::class,
                $isAdmin ? [['quantity', '<', 3]] : [['outlet_id', $outlet_id], ['quantity', '<', 3]]),

            'sales' => $getSum(OutletInvoice::class, 'payable_amount',
                $isAdmin ? [['sale_date', Carbon::now()->format('Y-m-d')]] : [['outlet_id', $outlet_id], ['sale_date', Carbon::now()->format('Y-m-d')]]),

            'invoices' => $getCount(OutletInvoice::class,
                $isAdmin ? [['sale_date', Carbon::now()->format('Y-m-d')]] : [['outlet_id', $outlet_id], ['sale_date', Carbon::now()->format('Y-m-d')]]),

            'last_day_sales' => $getSum(OutletInvoice::class, 'payable_amount',
                $isAdmin ? [['sale_date', $today->copy()->subDay()]] :
                    [['outlet_id', $outlet_id], ['sale_date', $today->copy()->subDay()->format('Y-m-d')]]),

            'this_month_sales' => $getSum(OutletInvoice::class, 'payable_amount',
                $isAdmin ? [
                    ['sale_date', '>=', $startOfMonth],
                    ['sale_date', '<=', $endOfMonth]
                ] : [
                    ['outlet_id', $outlet_id],
                    ['sale_date', '>=', $startOfMonth],
                    ['sale_date', '<=', $endOfMonth]
                ]),

            'this_month_invoices' => $getCount(OutletInvoice::class,
                $isAdmin ? [
                    ['sale_date', '>=', $startOfMonth],
                    ['sale_date', '<=', $endOfMonth]
                ] : [
                    ['outlet_id', $outlet_id],
                    ['sale_date', '>=', $startOfMonth],
                    ['sale_date', '<=', $endOfMonth]
                ]),
        ];

        return response()->json($response);

    } catch (\Exception $e) {
        \Log::error('API Dashboard Error: ' . $e->getMessage());
        return response()->json([
            'error' => 'An error occurred while fetching dashboard data',
            'details' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}


public function createCustomer(Request $request)
{

    // Validate the incoming request
    $request->validate([
        'name' => 'required|string',
        'phone' => 'required|string',
        'address' => 'required|string',
    ]);

    // Extract the input data
    $input = $request->all();

    // Search for the existing customer by mobile number
    $customer = Customer::where('mobile', $input['phone'])->first();

    // If customer exists, return already have customer response
    if ($customer) {
        return response()->json([
            'flag' => false,
            'message' => 'Customer already exists.',
            'customer' => $customer
        ], 200);
    }

    // Prepare customer details
    $customerDetails = [
        'name' => ucfirst($input['name']),
        'mobile' => $input['phone'],
        'address' => $input['address'],
        'birth_date' => '',
        'outlet_id' => Auth()->user()->outlet_id,
        'due_balance' => 0,
        'points' => 0,
    ];

    // Create a new customer
    $customer = Customer::create($customerDetails);
    // $this->sendWelcomeSMS($input['mobile'], ucfirst($input['name']), $input['outlet_id']);
    return response()->json([
        'flag' => true,
        'message' => 'Customer created successfully.',
        'customer' => $customer
    ], 201);
}

public function getPosData()
    {
        try {
            // Get payment methods
            $payment_methods = PaymentMethod::select('id', 'method_name')
                ->get();
                $response = $payment_methods->map(function ($method) {
                    return [
                        'id' => $method->id,
                        'name' => $method->method_name
                    ];
                });

            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch payment methods',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function topSale(Request $request)
    {
        if (auth()->user()->hasrole(['Super Admin', 'Admin'])) {

            $topSalesProducts = DB::table('outlet_invoice_details')->
                whereBetween('outlet_invoice_details.created_at',
                [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ])
                ->leftJoin('medicines', 'medicines.id', '=', 'outlet_invoice_details.medicine_id')
                ->select('medicines.id', 'medicines.medicine_name', 'outlet_invoice_details.medicine_id',
                    DB::raw('SUM(outlet_invoice_details.quantity) as total'), DB::raw('COUNT(outlet_invoice_details.medicine_id) as count'))
                ->groupBy('medicines.id', 'medicines.medicine_name', 'outlet_invoice_details.medicine_id')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get();
        } else {
            $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');
            $get_invoice = OutletInvoice::whereBetween('sale_date',
                [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ])->where('outlet_id', $outlet_id)->pluck('id');
            $topSalesProducts = DB::table('outlet_invoice_details')->
                whereBetween('outlet_invoice_details.created_at',
                [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ])->whereIn('outlet_invoice_details.outlet_invoice_id', $get_invoice)
                ->leftJoin('medicines', 'medicines.id', '=', 'outlet_invoice_details.medicine_id')
                ->select('medicines.id', 'medicines.medicine_name', 'outlet_invoice_details.medicine_id',
                    DB::raw('SUM(outlet_invoice_details.quantity) as total'), DB::raw('COUNT(outlet_invoice_details.medicine_id) as count'))
                ->groupBy('medicines.id', 'medicines.medicine_name', 'outlet_invoice_details.medicine_id')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get();

        }

        return response()->json($topSalesProducts);

    }



    public function apiExchangeData(Request $request)
{
    $perPage = $request->input('per_page', 10); // Default 10 items per page
    $outlet_id = Auth::user()->outlet_id ?? Outlet::orderBy('id', 'desc')->first('id');

    try {
        $query = OutletExchange::with(['customer'])
            ->orderBy('id', 'desc');

        // Apply outlet filter if not Super Admin
        if (!auth()->user()->hasRole('Super Admin')) {
            $query->where('outlet_id', $outlet_id);
        }

        // Get paginated results
        $exchanges = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => [
                'exchanges' => $exchanges->items(),
                'pagination' => [
                    'current_page' => $exchanges->currentPage(),
                    'last_page' => $exchanges->lastPage(),
                    'per_page' => $exchanges->perPage(),
                    'total' => $exchanges->total()
                ]
            ]
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while fetching exchanges',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function apiExchangeDetails($id)
{
    try {
        $exchange = OutletExchange::with('outlet:id,outlet_name')->where('id', $id)->first();

        if (!$exchange) {
            return response()->json([
                'status' => false,
                'message' => 'Exchange not found',
                'data' => null
            ], 404);
        }

        $exchangeDetails = OutletExchangeDetails::where('outlet_exchange_id', $id)->get();

        return response()->json([
            'status' => true,
            'message' => 'Exchange details retrieved successfully',
            'data' => [
                'exchange' => $exchange,
                'exchange_details' => $exchangeDetails
            ]
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'An error occurred while retrieving exchange details',
            'error' => $e->getMessage()
        ], 500);
    }
}


public function getExcahangeProducts($invoiceId)
{
    $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');
    $invoiceCheck = OutletInvoice::where('id', $invoiceId)->where('outlet_id', $outlet_id)->first();

    if ($invoiceCheck == null) {
        return response()->json([
            'status' => 'error',
            'message' => 'Exchange is Not Possible, in this store',
            'data' => null
        ], 404);
    } else {
        if ($invoiceCheck->is_exchange == 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Already one time exchange, So no more Exchange Possible',
                'data' => null
            ], 404);
        } else {
            $invoiceDate = Carbon::parse($invoiceCheck->created_at);

            // Get the current date and time
            $currentDate = Carbon::now();

            // Calculate the difference in days
            $daysDifference = $currentDate->diffInDays($invoiceDate);

            if ($daysDifference > 4) {
                // Invoice is greater than 4 days old
                return response()->json([
                    'status' => 'error',
                    'message' => 'Exchange Not Possible, Purchase Date More than 4 days',
                    'data' => null
                ], 404);
            } else {
                // Invoice is not greater than 4 days old
                $products = OutletInvoiceDetails::where('outlet_invoice_id', $invoiceId)->get();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Products fetched successfully',
                    'data' => $products
                ], 200);
            }
        }
    }
}


}
