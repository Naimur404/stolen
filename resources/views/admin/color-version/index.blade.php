@extends('layouts.admin.master')

@section('title')
    {{ $app_setting->app_name}}
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/date-picker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/owlcarousel.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/prism.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/whether-icon.css')}}">
@endpush
@section('content')

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-sm-6 col-xl-4 col-lg-6">
                <div class="card o-hidden border-0">
                    <div class="bg-primary b-r-4 card-body">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="database"></i></div>
                            <div class="media-body">
                                <span class="m-0">Low Stock Product</span>
                                <h4 class="mb-0 counter">{{ $stocks }}</h4>
                                <i class="icon-bg" data-feather="database"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4 col-lg-6">
                <div class="card o-hidden border-0">
                    <div class="bg-secondary b-r-4 card-body">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="shopping-bag"></i></div>
                            <div class="media-body">
                                <span class="m-0">Total Products</span>
                                <h4 class="mb-0 counter">{{ $products }}</h4>
                                <i class="icon-bg" data-feather="shopping-bag"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4 col-lg-6">
                <div class="card o-hidden border-0">
                    <div class="bg-primary b-r-4 card-body">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="message-circle"></i></div>
                            <div class="media-body">
                                <span class="m-0">Total Customers</span>
                                <h4 class="mb-0 counter">{{ $customers }}</h4>
                                <i class="icon-bg" data-feather="message-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

{{--            <div class="col-xl-6 xl-100 box-col-12">--}}
{{--                <div class="card">--}}
{{--                    <div class="cal-date-widget card-body">--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-xl-6 col-xs-12 col-md-6 col-sm-6">--}}
{{--                                <div class="cal-info text-center">--}}
{{--                                    <div>--}}
{{--                                        <h2>{{ \carbon\carbon::now()->format('d') }}</h2>--}}
{{--                                        <div class="d-inline-block"><span--}}
{{--                                                class="b-r-dark pe-3">{{ \carbon\carbon::now()->format('l') }}</span><span--}}
{{--                                                class="ps-3">{{ \carbon\carbon::now()->format('Y') }}</span></div>--}}
{{--                                        <p class="f-16"></p>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-xl-6 col-xs-12 col-md-6 col-sm-6">--}}
{{--                                <div class="cal-datepicker">--}}
{{--                                    <div class="datepicker-here float-sm-end" data-language="en"></div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-xl-3 xl-50 col-sm-6 box-col-6">--}}

{{--            </div>--}}
{{--            <div class="col-xl-3 xl-50 col-sm-6 box-col-6">--}}

{{--            </div>--}}

            @if(auth()->user()->hasrole(['Super Admin', 'Admin']))
                <div class="col-xl-6 xl-100 box-col-12">
                    @else
                        <div class="col-xl-6 xl-100 box-col-12">
                            @endif
                            <div class="card">
                                <div class="card-header pb-0 d-flex justify-content-center align-items-center">
                                    <h6 class="mt-3">Top 10 Products This Month(Sale)</h6>

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


                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="code-box-copy">
                                        <button class="code-box-copy__btn btn-clipboard"
                                                data-clipboard-target="#products-cart" title="Copy"><i
                                                class="icofont icofont-copy-alt"></i></button>

                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(auth()->user()->hasrole(['Super Admin', 'Admin']))
                            <div class="col-xl-6 xl-100 box-col-12">
                                <div class="card">
                                    <div class="card-header pb-0 d-flex justify-content-center align-items-center">
                                        <h6 class="mt-3">Top 10 Products This Month(Purchase)</h6>

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
                                                <tbody id="data-list2">


                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="code-box-copy">
                                            <button class="code-box-copy__btn btn-clipboard"
                                                    data-clipboard-target="#products-cart" title="Copy"><i
                                                    class="icofont icofont-copy-alt"></i></button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(auth()->user()->hasrole(['Super Admin', 'Admin']))
                            <div class="col-xl-8 xl-100 box-col-12">
                                @else
                                    <div class="col-xl-8 xl-100 box-col-12">
                                        @endif
                                        <div class="card">
                                            <div
                                                class="card-header pb-0 d-flex justify-content-center align-items-center">
                                                <h6 class="mt-3 text-center">Top 10 Customers This Month</h6>

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


                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="code-box-copy">
                                                    <button class="code-box-copy__btn btn-clipboard"
                                                            data-clipboard-target="#products-cart" title="Copy"><i
                                                            class="icofont icofont-copy-alt"></i></button>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                            </div>
                </div>

                @push('scripts')
                    <script src="{{asset('assets/js/prism/prism.min.js')}}"></script>

                    <script src="{{asset('assets/js/counter/counter-custom.js')}}"></script>
                    <script src="{{asset('assets/js/custom-card/custom-card.js')}}"></script>
                    <script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
                    <script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
                    <script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
                    <script src="{{asset('assets/js/owlcarousel/owl.carousel.js')}}"></script>
                    <script>
                        $(document).ready(function () {
                            $.ajax({
                                url: '{{ route('top-sale') }}',
                                type: 'GET',
                                success: function (data) {
                                    var items = '';

                                    $.each(data, function (key, value) {
                                        items += '<tr><td class="f-w-600">' + value.medicine_name
                                            + '</td><td><div class="span badge rounded-pill pill-badge-success">' + value.total + '</div></td><td><div class="span badge rounded-pill pill-badge-secondary">' + value.count + '</div></td></tr>';
                                    });

                                    $('#data-list').html(items);
                                }
                            });

                            $.ajax({
                                url: '{{ route('top-purchase') }}',
                                type: 'GET',
                                success: function (data) {
                                    var items = '';

                                    $.each(data, function (key, value) {
                                        items += '<tr><td class="f-w-600">' + value.medicine_name
                                            + '</td><td><div class="span badge rounded-pill pill-badge-success">' + value.total + '</div></td><td><div class="span badge rounded-pill pill-badge-secondary">' + value.count + '</div></td></tr>';
                                    });

                                    $('#data-list2').html(items);
                                }
                            });

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
                    </script>

    @endpush
@endsection
