@extends('layouts.admin.master')

@section('title',' All Sales Return')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <div class="row">
                <div class="col-sm-6">
                    <h3> All Sales Return</h3>
                </div>

            </div>
        @endslot

        @slot('button')

        @endslot
    @endcomponent

    <div class="container-fluid list-products">
        <div class="row">
            <!-- Individual column searching (text inputs) Starts-->
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-body">
                        <div class="table-responsive product-table">
                            <table class="display data-table">
                                <thead>
                                <tr>

                                    <th>Return Date</th>
                                    <th>Outlet Name</th>
                                    <th>Customer</th>
                                    {{-- <th>Product Type</th> --}}

                                    <th>Payment Method</th>
                                    <th>Grand Total</th>
                                    <th>Pay</th>


                                    <th>Sold By</th>
                                    <th>Action</th>

                                    {{-- @if (auth()->user()->can('invoice.edit') || auth()->user()->can('invoice.delete'))
                                    <th>Action</th>
                                    @endif --}}
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($datas as $productPurchase)
                                    <tr>


                                        <td>{{ \Carbon\Carbon::parse($productPurchase->sale_date)->format('d-m-Y')}}
                                        </td>
                                        @if ( $productPurchase->outlet_id == null)

                                            <td> N/A</td>
                                        @elseif ( $productPurchase->outlet_id
                                            != null)

                                            <td>{{ $productPurchase->outlet->outlet_name }}</td>
                                        @endif
                                        <td> @if($productPurchase->customer_id != '' || $productPurchase->customer_id != null)
                                            @php
                                              $customer = App\Models\Customer::where('id',$productPurchase->customer_id)->first();
                                          @endphp

                                          {{ $customer->mobile }}
                                          @if ($customer->mobile == null || $customer->mobile == '')

                                          @else
                                          {{ $customer->mobile }}
                                        @endif
                                          @endif
                                        </td>

                                        <td>@php
                                                $data = App\Models\PaymentMethod::where('id',$productPurchase->payment_method_id)->first();
                                            @endphp
                                            {{ $data->method_name }}</td>

                                        <td>{{ $productPurchase->grand_total }}</td>
                                        <td>{{ $productPurchase->paid_amount }}</td>


                                        <td>
                                            @php
                                                $user = App\Models\User::where('id',$productPurchase->added_by)->first();
                                            @endphp
                                            {{ $user->name }}
                                        </td>


                                        <td class="form-inline">

                                            <a href="{{ route('sale-return.details', $productPurchase->id) }}"
                                               class="btn btn-primary btn-xs" title="Pay Now" style="margin-right:3px"><i class="fa fa-info" aria-hidden="true"></i>
                                            </a>

                                            {!! Form::open(['method' => 'DELETE', 'route' => ['sale-return.destroy', $productPurchase->id]]) !!}
                                            {{ Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'id' => 'delete', 'title' => 'Delete']) }}
                                            {!! Form::close() !!}



                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Individual column searching (text inputs) Ends-->
        </div>
    </div>

    @push('scripts')

        <script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                $('.data-table').DataTable(                  {
                        order: [[0, 'desc']],
});
            });


        </script>


        @if (Session()->get('success'))

            <script>
                $.notify('<i class="fa fa-bell-o"></i><strong>{{ Session()->get('success') }}</strong>', {
                    type: 'theme',
                    allow_dismiss: true,
                    delay: 2000,
                    showProgressbar: true,
                    timer: 300
                });
            </script>

        @endif
        @if (Session()->get('error'))

            <script>
                $.notify('<i class="fa fa-bell-o"></i><strong>{{ Session()->get('error') }}</strong>', {
                    type: 'theme',
                    allow_dismiss: true,
                    delay: 2000,
                    showProgressbar: true,
                    timer: 300
                });
            </script>

        @endif
        {{-- <script src="{{asset('assets/js/ecommerce.js')}}"></script> --}}
        {{-- <script src="{{asset('assets/js/product-list-custom.js')}}"></script> --}}
    @endpush

@endsection
