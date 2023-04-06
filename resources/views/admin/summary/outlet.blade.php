@extends('layouts.admin.master')
@section('title')Outlet Summary
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
			<h3>Outlet Summary</h3>
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


@foreach ($data_arr as $data)

            <table class="table table-bordered mt-4">
                <tr>
                    <th colspan="2">{{ $data['name'] }}</th>

                </tr>
                    <tr>
                        <td style="width: 50%">Total Sale</td>
                        <td style="width: 50%">{{ $data['sale'] }} TK</td>

                    </tr>

                    <tr>
                        <td>Total Due </td>
                        <td>
                            {{ $data['due'] }} TK
                        </td>

                    </tr>
                    <tr>
                        <td>Total Return </td>
                        <td> {{ $data['return'] }}
                        </td>

                    </tr>
                    <tr>
                        <td>Total Received </td>
                        <td> {{ $data['received'] }}
                        </td>

                    </tr>
                    <tr>
                        <td>Expired Today </td>
                        <td> {{ $data['stock'] }}
                        </td>

                    </tr>
            </table>

            @endforeach

        </div>
    </div>
</div>


@endsection
