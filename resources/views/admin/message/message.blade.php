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
                    // Display validation error for the "message" field
                    $('#messageError').text(xhr.responseJSON.errors.message);
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
