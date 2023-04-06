@extends('layouts.admin.master')
@section('title')
    Warehouse Summary
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
                    <h3>Warehouse Summary</h3>
                </div>

            </div>

        @endslot

        @slot('button')

        @endslot
    @endcomponent
    <div class="col-md-12 col-lg-12">
        <div class="card">

            <div class="card-body">


                @foreach ($data_arr as $data)

                    <table class="table table-bordered mt-4">
                        <tr>
                            <th colspan="2">{{ $data['name'] }}</th>

                        </tr>
                        <tr>
                            <td style="width: 50%">Total Purchase QTY</td>
                            <td style="width: 50%">{{ $data['purchase_qty'] }}</td>

                        </tr>

                        <tr>
                            <td>Total Purchase Amount</td>
                            <td>
                                {{ $data['purchase_amt'] }}
                            </td>

                        </tr>
                        <tr>
                            <td>Distribute (QTY),</td>
                            <td> {{ $data['distribute'] }}
                            </td>

                        </tr>
                        <tr>
                            <td>Medicine Request (QTY),</td>
                            <td> {{ $data['request'] }}
                            </td>

                        </tr>
                        <tr>
                            <td>Expired Today</td>
                            <td> {{ $data['stock'] }}
                            </td>

                        </tr>
                    </table>

                @endforeach

            </div>
        </div>
    </div>

@endsection
