@extends('layouts.admin.master')
@isset($title)
@section('title',$title)
@endisset

@push('css')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/buttons.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/responsive.bootstrap4.min.css')}}">
@endpush
@section('content')
@component('components.breadcrumb')
@slot('breadcrumb_title')

@endslot

@slot('button')

@endslot

@endcomponent
@php
$grand_total = 0;
$total_quantity = 0;
$total_pay = 0;
@endphp
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="card ">
                <div class="card-header py-2">
                    <div class="d-flex justify-content-between align-items-center">

                    </div>
                </div>
                <div class="card-body">
                      @can('category-wise-report-alert-outlet')
                    {!! Form::open(['url' => 'category-wise-report-alert-submit', 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) !!}
                     <div class="row">
                         <div class="col-md-3">

                                  <label class="input-group" for="inputGroupSelect01" style="margin-right: 10px">Outlet Name* </label>

                                {{ Form::select('outlet_id', $outlet, null, ['class' => 'form-control', 'placeholder' => 'Select Outlet', 'id' => 'outlet','required']) }}

                         </div>
                         <div class="col-md-3">

                                  <label class="input-group" for="inputGroupSelect01" style="margin-right: 10px">Category</label>


                                {{ Form::select('category_id[]', $category1, null, ['class' => 'form-control', 'multiple'=>'multiple', 'id' => 'sel_emp' ]) }}

                         </div>
                         <div class="col-md-3">

                                  <label class="input-group" for="inputGroupSelect01" style="margin-right: 10px">Manufacturar</label>
                                  {{ Form::select('manufacturer_id[]', [''], null,['class' => 'form-control', 'id' => 'sel_emp2','multiple'=>'multiple', ]) }}

                         </div>

                         <div class="col-md-2 mt-4">
                            <button type="submit" class="btn btn-info">
                                Search
                            </button>
                         </div>
                     </div>
                     {!! Form::close() !!}
                     @endcan
                     @can('category-wise-report-alert-warehouse')
                     {!! Form::open(['url' => 'category-wise-report-alert-submit', 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) !!}
                     <div class="row mt-3">
                         <div class="col-md-3">
                            <label class="input-group"  style="margin-right: 10px">Warehouse Name </label>
                                {{ Form::select('warehouse_id', $warehouse, null, ['class' => 'form-control', 'placeholder' => 'Select Outlet', 'id' => 'outlet']) }}

                         </div>
                         <div class="col-md-3">

                            <label class="input-group" for="inputGroupSelect01" style="margin-right: 10px">Category</label>


                          {{ Form::select('category_id[]', $category1, null, ['class' => 'form-control', 'multiple'=>'multiple', 'id' => 'sel_emp4' ]) }}

                   </div>
                   <div class="col-md-3">

                            <label class="input-group" for="inputGroupSelect01" style="margin-right: 10px">Manufacturar</label>
                            {{ Form::select('manufacturer_id[]', [''], null,['class' => 'form-control', 'id' => 'sel_emp3','multiple'=>'multiple']) }}

                   </div>
                         <input type="hidden" name="outlet_id" value="">
                         <div class="col-md-2 mt-4">
                            <button type="submit" class="btn btn-info">
                                Search
                            </button>
                         </div>
                     </div>
                     {!! Form::close() !!}
                     @endcan


                    <div class="table-responsive mt-3">
                        <table class="table display table-bordered table-striped table-hover custom-table"
                        id="product_purchase">
                        <thead>
                            <tr>
                                <th>SL</th>

                                {{-- <th>Outlet Name</th> --}}
                                <th>Medicine Name</th>
                                <th>Expiry Date</th>

                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>

                            </tr>
                        </thead>
                        <tbody>

                            @isset($productSales)

                            @foreach ($productSales as $productPurchase)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>


                                    <td>{{ $productPurchase->medicine_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($productPurchase->expiry_date)->format('d-m-Y') }}
                                    </td>
                                    <td>{{ $productPurchase->price }}</td>
                                    <td>{{ $productPurchase->quantity }}</td>
                                    <td>{{ $productPurchase->price * $productPurchase->quantity }}</td>

                                </tr>
                                @php
                                $grand_total = $grand_total + $productPurchase->quantity;

                                $total_quantity = $total_quantity + $productPurchase->quantity;
                                @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>



                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right"><b>Total Quantity</b></td>
                                <td>
                                    @if ($total_quantity >= $total)
                                    <h5> <span class="badge bg-success">{{ $total_quantity }}</span></h5>
                                    @else
                                    <h5><span class="badge bg-danger">{{ $total_quantity }}</span></h5>
                                    @endif
                            </tr>
                        </tfoot>
                        @endisset
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')

<script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>
<script type="text/javascript">
 var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$(document).ready(function() {
    $('#product_purchase').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'print',
                exportOptions: {
                    columns: ':visible'
                }
            },

            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ ':visible' ]
                }
            },
            'colvis'
        ]
    } );




          $( "#sel_emp2" ).select2({

             ajax: {
               url: "{{route('get-manufacturer')}}",
               type: "post",
               dataType: 'json',

               data: function (params) {
                 return {
                    _token: CSRF_TOKEN,
                    search: params.term // search term
                 };
               },
               processResults: function (response) {
                 return {
                   results: response
                 };
               },
               cache: true
             }

          });

          $( "#sel_emp" ).select2({

             ajax: {
               url: "{{route('get-category1')}}",
               type: "post",
               dataType: 'json',

               data: function (params) {
                 return {
                    _token: CSRF_TOKEN,
                    search: params.term // search term
                 };
               },
               processResults: function (response) {
                 return {
                   results: response
                 };
               },
               cache: true
             }

          });


$( "#sel_emp3" ).select2({

 ajax: {
  url: "{{route('get-manufacturer')}}",
  type: "post",
  dataType: 'json',

  data: function (params) {
    return {
       _token: CSRF_TOKEN,
       search: params.term // search term
    };
  },
  processResults: function (response) {
    return {
      results: response
    };
  },
  cache: true
}

});

$( "#sel_emp4" ).select2({

ajax: {
  url: "{{route('get-category1')}}",
  type: "post",
  dataType: 'json',

  data: function (params) {
    return {
       _token: CSRF_TOKEN,
       search: params.term // search term
    };
  },
  processResults: function (response) {
    return {
      results: response
    };
  },
  cache: true
}

});

 } );


</script>
@endpush
@endsection
