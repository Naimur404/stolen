@extends('layouts.admin.master')

@section('Send Message')
  Send Message
@endsection

@push('css')
    <!-- Include Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <div class="row">
                <div class="col-sm-10">
                    <h3>Send Message</h3>
                </div>
            </div>
        @endslot
    @endcomponent
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">

                    <form id="sendMessageForm" method="POST" role="form" class="needs-validation">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="recipient_type" class="form-label">Send To</label>
                                        <select name="recipient_type" id="recipient_type" class="form-control" required>
                                            <option value="all">All Customers</option>
                                            <option value="outlet">Specific Outlet Customers</option>
                                        </select>
                                        <span class="text-danger" id="recipientTypeError"></span>
                                        <small class="text-info" id="allCustomersCount"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3" id="outlet_selection" style="display: none;">
                                        <label for="outlet_id" class="form-label">Select Outlet</label>
                                        <select name="outlet_id" id="outlet_id" class="form-control">
                                            <option value="">Select an outlet...</option>
                                        </select>
                                        <span class="text-danger" id="outletError"></span>
                                        <small class="text-info" id="customerCount" style="display: none;"></small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="message" class="form-label">Message</label>
                                        <textarea name="message" id="message" class="form-control" required></textarea>
                                        <span class="text-danger" id="messageError"></span>
                                        <small class="text-muted" id="characterCount">0/160 (1 Message)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Submit Button -->
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary" id="sendMessageButton">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Include Toastr and jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script>
      $(document).ready(function () {
    const maxCharactersPerMessage = 160; // Set the character limit per message
    const messageField = $('#message');
    const characterCountField = $('#characterCount');

    // Handle recipient type change
    $('#recipient_type').on('change', function() {
        if ($(this).val() === 'outlet') {
            $('#outlet_selection').show();
            $('#allCustomersCount').hide();
            loadOutlets();
        } else {
            $('#outlet_selection').hide();
            $('#outlet_id').val('');
            $('#customerCount').hide().text('');
            loadAllCustomersCount();
        }
    });

    // Load total customer count for all customers
    function loadAllCustomersCount() {
        $.ajax({
            url: "{{ route('get-all-customer-count') }}",
            method: "GET",
            success: function(response) {
                const count = response.count;
                $('#allCustomersCount').show().text(`Total customers: ${count}`);
            },
            error: function() {
                $('#allCustomersCount').show().text('Unable to load customer count');
            }
        });
    }

    // Initialize customer count on page load
    loadAllCustomersCount();

    // Handle outlet selection change
    $('#outlet_id').on('change', function() {
        const outletId = $(this).val();
        if (outletId) {
            loadCustomerCount(outletId);
        } else {
            $('#customerCount').hide().text('');
        }
    });

    // Load customer count for selected outlet
    function loadCustomerCount(outletId) {
        $.ajax({
            url: "{{ route('get-outlet-customer-count') }}",
            method: "GET",
            data: { outlet_id: outletId },
            success: function(response) {
                const count = response.count;
                $('#customerCount').show().text(`Total customers: ${count}`);
            },
            error: function() {
                $('#customerCount').show().text('Unable to load customer count');
            }
        });
    }

    // Load outlets for selection
    function loadOutlets() {
        $.ajax({
            url: "{{ route('get-outlet') }}",
            method: "GET",
            success: function(data) {
                $('#outlet_id').empty().append('<option value="">Select an outlet...</option>');
                $.each(data.results, function(key, value) {
                    $('#outlet_id').append('<option value="' + value.id + '">' + value.text + '</option>');
                });
            },
            error: function() {
                toastr.error('Failed to load outlets');
            }
        });
    }

    messageField.on('input', function () {
        const messageLength = messageField.val().length;
        const messageCount = Math.ceil(messageLength / maxCharactersPerMessage);

        // Update character count and message count display
        characterCountField.text(`${messageLength}/${maxCharactersPerMessage} (${messageCount} Message${messageCount > 1 ? 's' : ''})`);

        // Highlight if message exceeds single message limit
        if (messageCount > 1) {
            characterCountField.addClass('text-danger').removeClass('text-muted');
        } else {
            characterCountField.addClass('text-muted').removeClass('text-danger');
        }
    });

    $('#sendMessageForm').on('submit', function (e) {
        e.preventDefault();

        // Clear previous error messages
        $('#messageError').text('');
        $('#recipientTypeError').text('');
        $('#outletError').text('');

        // Validate outlet selection when required
        if ($('#recipient_type').val() === 'outlet' && !$('#outlet_id').val()) {
            $('#outletError').text('Please select an outlet');
            return false;
        }

        // Disable button to prevent multiple submissions
        $('#sendMessageButton').prop('disabled', true).text('Sending...');

        $.ajax({
            url: "{{ route('message.sendMessage') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function (response) {
                if (response.status === "success") {
                    // Display the success message from the API response
                    toastr.success(response.status);
                    setTimeout(function() {
                        window.location.href = "{{ route('sendMessageLogs.index') }}"; // Redirect to another page
                    }, 500);
                } else {
                    toastr.error('An unexpected error occurred.');
                }
            },
            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // Display validation errors
                    if (xhr.responseJSON.errors.message) {
                        $('#messageError').text(xhr.responseJSON.errors.message);
                    }
                    if (xhr.responseJSON.errors.recipient_type) {
                        $('#recipientTypeError').text(xhr.responseJSON.errors.recipient_type);
                    }
                    if (xhr.responseJSON.errors.outlet_id) {
                        $('#outletError').text(xhr.responseJSON.errors.outlet_id);
                    }
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    // Display error message returned by the server
                    toastr.error(xhr.responseJSON.message);
                } else {
                    toastr.error('An error occurred while sending the message.');
                }
            },
            complete: function () {
                // Re-enable the button after request completes
                $('#sendMessageButton').prop('disabled', false).text('Send');
            }
        });
    });
});
    </script>

@endpush
