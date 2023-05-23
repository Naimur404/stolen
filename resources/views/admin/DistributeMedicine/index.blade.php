@extends('layouts.admin.master')

@section('title',' All Distribute Medicine to Outlet')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <div class="row">
                <div class="col-sm-8 mt-3">
                     <h3>All Distribute Medicine to Outlet</h3>
                </div>

            </div>
        @endslot

        @slot('button')
        @can('medchine_purchase.create')
            <a href="{{ route('distribute-medicine.create') }}" class="btn btn-primary btn"
               data-original-title="btn btn-danger btn" title="">Distribute Medicine</a>
               @endcan
        @endslot
    @endcomponent

    <div class="container-fluid list-products">
        <div class="row">
            <!-- Individual column searching (text inputs) Starts-->
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display data-table">
                                <thead>
                                <tr>
                                    <th class="date">Date</th>
                                    <th>Outlet Name</th>
                                    <th>Warehouse Name</th>
                                    <th>Added By</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($medicinedistributes as $productPurchase)

                                    <tr>
                                        <td>
                                            {{ \Carbon\Carbon::parse($productPurchase->date)->format('d-m-Y')}}</td>
                                        @if ( $productPurchase->outlet_id == null)

                                            <td> N/A</td>
                                        @elseif ( $productPurchase->outlet_id
                                            != null)

                                            <td>{{ $productPurchase->outlet->outlet_name }}</td>
                                        @endif

                                        @if ( $productPurchase->warehouse_id == null)

                                            <td> N/A</td>
                                        @elseif ( $productPurchase->warehouse_id
                                            != null)

                                            <td>{{ $productPurchase->warehouse->warehouse_name }}</td>
                                        @endif

                                        <td>{{ $productPurchase->user->name }}</td>
                                        <td>{{ $productPurchase->remarks }}</td>
                                            <td class="form-inline uniqueClassName">
                                                @if(Auth::user()->hasRole(['Admin','Super Admin']))
                                                @if($productPurchase->has_sent == 0)
                                                    @can('medchine_purchase.edit')

                                                        <a href="{{ route('distribute-medicine.edit', $productPurchase->id) }}"
                                                        class="btn btn-success btn-xs" title="Edit"
                                                        style="margin-right:5px"><i class="fa fa-pencil-square-o"
                                                        aria-hidden="true"></i></a>
                                                    @endcan

                                                @else
                                                    <a href="javscript:void()"
                                                    class="btn btn-warning btn-xs" title="Sent"
                                                    style="margin-right:5px"><i class="fa fa-check"
                                                    aria-hidden="true"></i></a>

                                                @endif
                                                {{-- @can('product_purchase.print')
                                                <a href="{{ route('medicine-purchase.show', $productPurchase->id) }}" class="btn btn-info btn-xs"  title="Print Invoice" target="__blank" style="margin-right:3px"><i class="fas fa-print"></i></a>
                                                @endcan --}}

                                                @can('distribute-medicine.delete')
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['distribute-medicine.destroy', $productPurchase->id]]) !!}
                                                    {{ Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'id' => 'delete', 'title' => 'Delete']) }}
                                                    {!! Form::close() !!}
                                                @endcan



                                                <a href="{{ route('distribute-medicine.checkIn', $productPurchase->id) }}"
                                                class="btn btn-info btn-xs " title="CheckIn" style="margin-left:5px"><i
                                                        class="fa fa-eye" aria-hidden="true"></i></a>



                                                @else
                                                @if($productPurchase->has_received == 1)
                                                <a href="javscript:void()"
                                                class="btn btn-warning btn-xs" title="Recived"
                                                style="margin-right:5px"><i class="fa fa-check"
                                                                            aria-hidden="true"></i></a>
                                                @endif
                                                <a href="{{ route('distribute-medicine.checkIn', $productPurchase->id) }}"
                                                class="btn btn-info btn-xs " title="CheckIn" style="margin-left:5px"><i
                                                        class="fa fa-eye" aria-hidden="true"></i></a>

                                                @endif

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
                $('.data-table').DataTable(
                    {
                        order: [[1, 'desc']],
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



















