{{-- <x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}

@extends('auth.master')

@section('title')Forget Password
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
	                        <form class="theme-form login-form" method="POST" action="{{ route('password.store') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">
	                            <h4 class="mb-3">Reset Your Password</h4>


                                <div class="form-group">
                                    <label>Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="icon-email"></i></span>
                                        <input class="form-control" type="email" required="" name="email"  value="{{ old('email', $request->email)  }}"/>

                                    </div>
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

	                            <h6>Create Your Password</h6>
	                            <div class="form-group">
	                                <label>New Password</label>
	                                <div class="input-group">
	                                    <span class="input-group-text"><i class="icon-lock"></i></span>
	                                    <input class="form-control" type="password" name="password" required="" placeholder="*********"  />

	                                    <div class="show-hide"><span class="show"></span></div>

	                                </div>
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
	                            </div>
	                            <div class="form-group">
	                                <label>Retype Password</label>
	                                <div class="input-group">
	                                    <span class="input-group-text"><i class="icon-lock"></i></span>
	                                    <input class="form-control" type="password" name="password_confirmation" required="" placeholder="*********" />

	                                </div>
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
	                            </div>
	                            <div class="form-group">
	                                <div class="checkbox">
	                                    <input id="checkbox1" type="checkbox" />
	                                    <label class="text-muted" for="checkbox1">Remember password</label>
	                                </div>
	                            </div>
	                            <div class="form-group">
	                                <button class="btn btn-primary btn-block" type="submit">Reset Password</button>
	                            </div>
	                            <p>Already have an password?<a class="ms-2" href="{{ route('login') }}">Sign in</a></p>
	                        </form>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</section>

    @push('scripts')
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    @endpush

@endsection
