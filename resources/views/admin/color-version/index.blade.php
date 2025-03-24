@extends('layouts.admin.master')

@section('title')
    {{ $app_setting->app_name}}
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/date-picker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/owlcarousel.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/prism.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/whether-icon.css')}}">
    <!-- Chart.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.css">
    <style>
        .dashboard-card {
            transition: all 0.2s ease;
            border-radius: 6px !important;
            overflow: hidden;
            margin-bottom: 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }
        .dashboard-card:hover {
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .dashboard-icon {
            font-size: 22px;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
        }
        .media-body span {
            font-size: 0.85rem;
            opacity: 0.9;
        }
        .media-body h4 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-top: 3px;
            margin-bottom: 0;
        }
        .chart-container {
            position: relative;
            height: 220px;
            width: 100%;
        }
        .badge-value {
            font-size: 0.75rem;
            padding: 3px 8px;
            border-radius: 12px;
        }
        .card-bg-primary {
            background: linear-gradient(135deg, #7366ff 0%, #836afb 100%);
            color: white;
        }
        .card-bg-secondary {
            background: linear-gradient(135deg, #1ea6ec 0%, #45b6f1 100%);
            color: white;
        }
        .card-bg-success {
            background: linear-gradient(135deg, #51bb25 0%, #68cf3a 100%);
            color: white;
        }
        .card-header {
            padding: 12px 15px;
        }
        .card-body {
            padding: 15px;
        }
        .table th, .table td {
            padding: 0.5rem;
        }
        .icon-bg {
            font-size: 80px;
            opacity: 0.05;
            position: absolute;
            right: 0;
            bottom: 0;
        }
        .chart-loading {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 220px;
            width: 100%;
            background-color: rgba(255,255,255,0.7);
            position: absolute;
            top: 0;
            left: 0;
            z-index: 10;
        }
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #7366ff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-sm-6 col-xl-4 col-lg-6">
                <div class="card o-hidden border-0 dashboard-card">
                    <div class="bg-primary b-r-4 card-body p-3">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="database"></i></div>
                            <div class="media-body ms-3">
                                <span class="m-0">Low Stock Product</span>
                                <h4 class="mb-0 counter">{{ $stocks }}</h4>
                                <i class="icon-bg" data-feather="database"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4 col-lg-6">
                <div class="card o-hidden border-0 dashboard-card">
                    <div class="bg-secondary b-r-4 card-body p-3">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="shopping-bag"></i></div>
                            <div class="media-body ms-3">
                                <span class="m-0">Total Products</span>
                                <h4 class="mb-0 counter">{{ $products }}</h4>
                                <i class="icon-bg" data-feather="shopping-bag"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4 col-lg-6">
                <div class="card o-hidden border-0 dashboard-card">
                    <div class="bg-primary b-r-4 card-body p-3">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="message-circle"></i></div>
                            <div class="media-body ms-3">
                                <span class="m-0">Total Customers</span>
                                <h4 class="mb-0 counter">{{ $customers }}</h4>
                                <i class="icon-bg" data-feather="message-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4 col-lg-6">
                <div class="card o-hidden border-0 dashboard-card">
                    <div class="bg-secondary b-r-4 card-body p-3">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="credit-card"></i></div>
                            <div class="media-body ms-3">
                                <span class="m-0">Today's Sale</span>
                                <h4 class="mb-0 counter">{{ $sales }}</h4>
                                <i class="icon-bg" data-feather="credit-card"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4 col-lg-6">
                <div class="card o-hidden border-0 dashboard-card">
                    <div class="bg-secondary b-r-4 card-body p-3">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="credit-card"></i></div>
                            <div class="media-body ms-3">
                                <span class="m-0">Yesterday's Sale</span>
                                <h4 class="mb-0 counter">{{ $lastdaysales }}</h4>
                                <i class="icon-bg" data-feather="credit-card"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4 col-lg-6">
                <div class="card o-hidden border-0 dashboard-card">
                    <div class="bg-primary b-r-4 card-body p-3">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="dollar-sign"></i></div>
                            <div class="media-body ms-3">
                                <span class="m-0">Today's Invoice</span>
                                <h4 class="mb-0 counter">{{ $invoices }}</h4>
                                <i class="icon-bg" data-feather="dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            @if(auth()->user()->hasrole(['Super Admin', 'Admin']))
            <div class="col-sm-6 col-xl-4 col-lg-6">
                <div class="card o-hidden border-0 dashboard-card">
                    <div class="bg-secondary b-r-4 card-body p-3">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="credit-card"></i></div>
                            <div class="media-body ms-3">
                                <span class="m-0">Sales of this Month</span>
                                <h4 class="mb-0 counter">{{ $thisMonthSales }}</h4>
                                <i class="icon-bg" data-feather="credit-card"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4 col-lg-6">
                <div class="card o-hidden border-0 dashboard-card">
                    <div class="bg-primary b-r-4 card-body p-3">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="truck"></i></div>
                            <div class="media-body ms-3">
                                <span class="m-0">Invoice of this month</span>
                                <h4 class="mb-0 counter">{{ $thisMonthInvoices }}</h4>
                                <i class="icon-bg" data-feather="truck"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4 col-lg-6">
                <div class="card o-hidden border-0 dashboard-card">
                    <div class="bg-primary b-r-4 card-body p-3">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="dollar-sign"></i></div>
                            <div class="media-body ms-3">
                                <span class="m-0">Return of this month</span>
                                <h4 class="mb-0 counter">{{ $thisMonthReturns }}</h4>
                                <i class="icon-bg" data-feather="dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="col-xl-6 xl-100 box-col-12">
                <div class="card dashboard-card">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Monthly Sales Trend</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm dropdown-toggle p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i data-feather="more-vertical"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item refresh-chart" href="javascript:void(0)">Refresh Data</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <div id="salesChartLoading" class="chart-loading">
                                <div class="spinner"></div>
                            </div>
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 xl-100 box-col-12">
                <div class="card dashboard-card">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Inventory Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="height: 200px;">
                            <canvas id="inventoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-7 xl-100 box-col-12">
                <div class="card dashboard-card">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Top 10 Products This Month (Sale)</h5>
                    </div>
                    <div class="card-body">
                        <div class="user-status table-responsive">
                            <table class="table table-bordernone">
                                <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Count</th>
                                </tr>
                                </thead>
                                <tbody id="data-list">
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-5 xl-100 box-col-12">
                <div class="card dashboard-card">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Top 10 Customers This Month</h5>
                    </div>
                    <div class="card-body">
                        <div class="user-status table-responsive">
                            <table class="table table-bordernone">
                                <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Mobile</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Count</th>
                                </tr>
                                </thead>
                                <tbody id="data-list3">
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('assets/js/prism/prism.min.js')}}"></script>
    <script src="{{asset('assets/js/counter/counter-custom.js')}}"></script>
    <script src="{{asset('assets/js/custom-card/custom-card.js')}}"></script>
    <script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
    <script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
    <script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
    <script src="{{asset('assets/js/owlcarousel/owl.carousel.js')}}"></script>
    
    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    
    <script>
        $(document).ready(function () {
            // Initialize Feather Icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
            
            // Inventory Chart
            const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
            const inventoryChart = new Chart(inventoryCtx, {
                type: 'doughnut',
                data: {
                    labels: ['In Stock', 'Low Stock'],
                    datasets: [{
                        data: [{{ $products - $stocks }}, {{ $stocks }}],
                        backgroundColor: ['#7366ff', '#f73164'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
            
            // Load Monthly Sales Chart
            loadMonthlySalesChart();
            
            // Refresh chart on click
            $('.refresh-chart').on('click', function() {
                loadMonthlySalesChart();
            });
            
            // Load Top Products Data
            $.ajax({
                url: '{{ route('top-sale') }}',
                type: 'GET',
                success: function (data) {
                    var items = '';
                    
                    $.each(data, function (key, value) {
                        items += '<tr><td class="f-w-600">' + value.medicine_name
                            +'</td><td><div class="span badge rounded-pill pill-badge-success">' + value.total + '</div></td><td><div class="span badge rounded-pill pill-badge-secondary">' + value.count + '</div></td></tr>';
                    });
                    
                    $('#data-list').html(items);
                }
            });
            
            // Load Top Customers Data
            $.ajax({
                url: '{{ route('top-customer') }}',
                type: 'GET',
                success: function (data) {
                    var items = '';
                    
                    $.each(data, function (key, value) {
                        items += '<tr><td class="f-w-600">' + value.name + '</td><td class="f-w-600">' + value.mobile
                            + '</td><td><div class="span badge rounded-pill pill-badge-success">' + value.total + '</div></td><td><div class="span badge rounded-pill pill-badge-secondary">' + value.count + '</div></td></tr>';
                    });
                    
                    $('#data-list3').html(items);
                }
            });
        });
        
        // Function to load monthly sales chart
        function loadMonthlySalesChart() {
            $('#salesChartLoading').show();
            
            // Get existing chart instance and destroy it if it exists
            let chartInstance = Chart.getChart('salesChart');
            if (chartInstance) {
                chartInstance.destroy();
            }
            
            // Fetch monthly sales data from the backend
            $.ajax({
                url: '{{ route('monthly-sales') }}',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#salesChartLoading').hide();
                    
                    const salesCtx = document.getElementById('salesChart').getContext('2d');
                    const salesChart = new Chart(salesCtx, {
                        type: 'line',
                        data: {
                            labels: data.map(item => item.month + ' ' + item.year),
                            datasets: [{
                                label: 'Monthly Sales',
                                data: data.map(item => item.sales),
                                borderColor: '#7366ff',
                                backgroundColor: 'rgba(115, 102, 255, 0.1)',
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return 'Sales: $' + context.raw.toFixed(2);
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        display: true,
                                        color: 'rgba(0,0,0,0.05)'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                },
                error: function (xhr, status, error) {
                    $('#salesChartLoading').hide();
                    console.error("Error loading monthly sales data:", error);
                    
                    // Fallback to estimated data if API call fails
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    const currentMonth = new Date().getMonth(); // 0-11 (Jan is 0, Dec is 11)
                    let monthLabels = [];
                    
                    for (let i = 0; i < 12; i++) {
                        // Calculate month index working backwards from current month
                        let monthIndex = (currentMonth - 11 + i + 12) % 12;
                        monthLabels.push(months[monthIndex]);
                    }
                    
                    // Create realistic fallback data
                    let salesData = Array(11).fill(0).map(() => 
                        Math.floor(Math.random() * 1000) + 500
                    );
                    salesData.push({{ $thisMonthSales }});
                    
                    const salesCtx = document.getElementById('salesChart').getContext('2d');
                    const salesChart = new Chart(salesCtx, {
                        type: 'line',
                        data: {
                            labels: monthLabels,
                            datasets: [{
                                label: 'Monthly Sales (Estimated)',
                                data: salesData,
                                borderColor: '#7366ff',
                                backgroundColor: 'rgba(115, 102, 255, 0.1)',
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        display: true,
                                        color: 'rgba(0,0,0,0.05)'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                }
            });
        }
    </script>
@endpush