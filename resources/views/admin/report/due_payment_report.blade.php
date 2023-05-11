@extends('layouts.admin.public_layouts')
@section('title','Due Payment Report')
@section('main-content')
<style>
    .space{
        line-height: 3px;
    }
    .all-content{
            margin-top: 60px !important;
            min-width: 1000px;
            max-width: 1000px;
            margin: 0 auto;
        }
    .table tbody tr td{
            /* border-top: none; */
            border: 1px solid #070707;
            padding: 2px;
        }
    .table thead tr th{
            /* border-top: none; */
            border: 1px solid #070707;
            padding: 2px;
            text-align: center;
        }
</style>



<div class="row">
    <div class="col-md-5">
        <img src="{{asset('uploads/'.$app_setting->logo)}}" alt="" style="max-height: 150px">
    </div>
    <div class="col-md-7 text-right">
        <br>
        <h4>Due Payment Report</h4>
        {{-- <p>{{ $business->address }} <br> {{ $business->mobile }}</p> --}}
    </div>
</div>

<div class="row all-content">
    <p align="center"><b> Due Payment Report -
        @if ($start_date && $end_date !=null )
        From {{Carbon\Carbon::parse($start_date)->format('d-m-Y')}}
        To {{Carbon\Carbon::parse($end_date)->format('d-m-Y') }}
    @endif
   </b></p>
    <div class="col-md-12">
        <table class="table table-hover table-bordered">
            <thead class="">
                <tr>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Due</th>
                                <th>Pay</th>
                                <th>Rest Amount</th>
                                <th>Collected By</th>
                </tr>
            </thead>
            <tbody>
                @php
                $total = 0;


                @endphp
                    @foreach ($due_payments as $due_payment)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($due_payment->created_at)->format('d-m-Y') }}
                        <td> {{ $due_payment->mobile ?? '' }}</td>
                        <td>{{ $due_payment->due_amount }}</td>
                        <td>{{ $due_payment->pay }}</td>
                        <td>{{ $due_payment->rest_amount}}
                        </td>
                        <td>{{ \App\Models\User::findOrFail($due_payment->received_by)->name }}</td>


                    </tr>
                    @php
                    $total = $total + $due_payment->pay;


                    @endphp
                @endforeach


            </tbody>
        </table>

        <p class="text-center">Total Collection BDT {{ $total }}/-</p>
        <p class="text-center" style="font-size: 12px">Thank You ❤ Software by Pigeon Soft</p>

    </div>
</div>

@section('custom-js')
@if ($app_setting->print == 1)
<script>
    setTimeout(function() { window.print(); }, 1000);
</script>
@endif
@endsection
@endsection
