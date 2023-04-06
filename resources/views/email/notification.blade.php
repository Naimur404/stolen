@component('mail::message')

    <p>Hi,</p>

    # You have been sent a download link from {{ config('app.instance') }}.

    <p>To access the download, please use the access code below:</p>
    <p><b>Date:</b> {{ \Carbon\Carbon::today() }}</p>

    @component('mail::button', ['url' => url('/'), 'color' => 'green'])
        Download now
    @endcomponent

    <p>If you’re having trouble clicking the download button, copy and paste the URL below into your web browser: </p>

    <p><a href="{{ url('/') }}">{{ url('/') }}</a></p>

    <p><b>Note:</b> this link will expire in <b>24 hours.</b></p>

    Regards,<br>
    {{ config('app.instance') }}

    {{-- Subcopy --}}
    @component('mail::subcopy')
        Login back into the system by clicking this link or copy and paste the URL below
        into your web browser: [{{ route('login') }}]({!! route('login') !!})
    @endcomponent

@endcomponent
