@extends('layouts.admin.master')

@section('title') Send Message Logs @endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
        <h3>Send Message Logs</h3>
        @endslot
        @slot('button')
        <a href="{{ route('sendMessageView') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Send Message</a>
        @endslot
    @endcomponent

    <div class="container-fluid list-products">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive product-table">
                            <table class="display data-table">
                                <thead>
                                    <tr>
                                        <th>Message</th>
                                        <th>Sent To</th>
                                        <th>Response</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- DataTable will populate the rows -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script src="{{ asset('assets/js/datatables/jquery.dataTables.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('sendMessageLogs.index') }}",
            columns: [
                {data: 'message', name: 'message'},
                {data: 'recipient_info', name: 'recipient_info', orderable: false, searchable: false},
                {data: 'response', name: 'response', orderable: false, searchable: false},
                {data: 'created_at', name: 'created_at'}
            ],
            order: [[3, 'desc']] // Sort by created_at column in descending order
        });
    });
</script>
@endpush
@endsection
