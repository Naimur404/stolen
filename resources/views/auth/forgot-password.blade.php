{{-- <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}

@extends('auth.master')

@section('title') Reset Mail
 {{ $title }}
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.css') }}">
@endpush

@section('content')
    <section>
	    <div class="container-fluid p-0">
	        <div class="row m-0">
         
	            <div class="col-12 p-0">
	                <div class="login-card">
	                    <div class="login-main">
	                        <form class="theme-form login-form" method="post" action="{{ route('password.email') }}">
                                @csrf
	                            <h4>Reset Password</h4>
	                            <div class="form-group">
	                                <label class="col-form-label">Email</label>


	                                    <div class="input-group">
                                            <span class="input-group-text"><i class="icon-email"></i></span>
                                            <input class="form-control" type="email" required="" placeholder="Test@gmail.com" name="email" />

                                        </div>
                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />

	                            </div>

	                            <div class="form-group">
	                                <button class="btn btn-primary btn-block" type="submit">Sent</button>
	                            </div>
	                            <p>Already have an account?<a class="ms-2" href="{{ route('login') }}">Sign in </a></p>
	                        </form>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</section>


    @push('scripts')
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
    @if (Session()->get('status'))

<script>
$.notify('<i class="fa fa-bell-o"></i><strong>{{ Session()->get('status') }}</strong>', {
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
    @endpush

@endsection
