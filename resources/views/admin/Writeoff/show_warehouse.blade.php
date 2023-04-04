@extends('layouts.admin.master')
@section('title')Show Warehouse Writeoff

@endsection
@push('css')
<style>
    .delete {
        color: #fff;
    }

    .custom-td {
        padding: 5px !important;
        vertical-align: middle !important;
    }
</style>
@endpush
@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-6">
			<h3>Show Outlet Writeoff

            </h3>
        </div>

        </div>

		@endslot

        @slot('button')

        @endslot
	@endcomponent
<div class="col-md-12 col-lg-12">
    <div class="card">
        <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">


            </div>
        </div>

        <div class="card-body">


                {{-- <div class="row">
                    <div class="col-md-3">Manufacturer Name : </div>
                    <div class="col-md-4"><b>
                            @if ($productPurchase->manufacturer_id == null)
                                N/A
                            @elseif ($productPurchase->manufacturer_id != null)
                                {{ $productPurchase->manufacturer->manufacturer_name }}
                            @endif
                        </b></div>

                </div> --}}

            </div>


            <table class="table table-bordered mt-2">
                <tr>
                    <th>Name Of Investigation</th>

                    <th>Pre Quantity</th>
                    <th>Quantity</th>
                    <th>Reason</th>

                </tr>
                    <tr>
                        <td>{{ $warehouseWriteoff->medicine_name }}

                        </td>

                        <td>{{  $warehouseWriteoff->previous_stock }}
                           </td>
                           <td>{{ $warehouseWriteoff->quantity}}
                           </td>
                           <td>{{ $warehouseWriteoff->reason}}
                        </td>
                    </tr>
            </table>



        </div>
        <div class="col-md-6"></div>


    </div>
</div>


@endsection
