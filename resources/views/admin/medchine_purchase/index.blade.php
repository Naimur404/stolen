


@extends('layouts.admin.master')

@section('title',' All Purchase')

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">

@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-6">
			<h3>All Purchase</h3>
        </div>

        </div>
		@endslot

        @slot('button')
        <a href="{{ route('medicine-purchase.create') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Purchase</a>
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
                                        <th>SL</th>
                                        <th>Supplier</th>
                                        <th>Purchase Date</th>
                                        <th>Payment Method</th>
                                        <th>Total</th>
                                        <th>Pay</th>
                                        <th>Due</th>
                                        <th>Action</th>

                                    </tr>
	                            </thead>
	                            <tbody>

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

        $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });

    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ajax: "/get-medicine-purchase",

        columns: [
            {data: 'id', name: 'sl'},
            {data: 'supplier_id', name: 'Supplier'},
            {data: 'purchase_date', name: 'Purchase Date'},
            {data: 'payment_method_id', name: 'Payment Method'},
            {data: 'grand_total', name: 'total'},
            {data: 'paid_amount', name: 'pay'},
            {data: 'due_amount', name: 'Due'},
            {data: 'action', name: 'action'},

        ]
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



















