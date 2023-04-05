{{-- <x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ml-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}






@extends('auth.master')

@section('title')login- {{ env('APP_NAME') }}

@endsection

@push('css')

@endpush

@section('content')
    <section>
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="login-card">
                    {!! Form::open(['route'=>'login', 'method'=>'POST','role' => 'form','class' => 'theme-form login-form']) !!}
                    {!! Form::token(); !!}

                        <h4>Login - {{ env('APP_NAME') }}</h4>
                        <h6>Welcome back! Log in to your account.</h6>
                        <div class="form-group">
                            {!! Form::label('', 'Email address') !!}

                            <div class="input-group">

                                <span class="input-group-text"><i class="icon-email"></i></span>
                                {!! Form::email('email',null,['class'=>'form-control', 'placeholder'=>'name@example.com', 'id' => 'exampleFormControlInput1' ]) !!}

                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div class="form-group">
                            {!! Form::label('', 'Password') !!}

                            <div class="input-group">
                                <span class="input-group-text"><i class="icon-lock"></i></span>

                                {!! Form::password('password',['class'=>'form-control', 'placeholder'=>'*********', 'required','id' => 'myInput']) !!}

                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <div class="form-group mt-3">
                            <div class="checkbox">
                                <input id="checkbox1" type="checkbox" />
                                <label for="checkbox1">Remember password</label>
                            </div>
                            <a class="link" href="{{ route('password.request') }}">Forgot password?</a>
                        </div>
                        <div class="form-group">
                            {!!  Form::submit('Sign in',['class'=> 'btn btn-primary btn-block']); !!}

                        </div>
                        <div class="login-social-title">
                            <h5>Sign in with</h5>
                        </div>
                        <div class="form-group">
                            <ul class="login-social">
                                <li>
                                    <a href="https://www.linkedin.com/login" target="_blank"><i data-feather="linkedin"></i></a>
                                </li>
                                <li>
                                    <a href="https://www.linkedin.com/login" target="_blank"><i data-feather="twitter"></i></a>
                                </li>
                                <li>
                                    <a href="https://www.linkedin.com/login" target="_blank"><i data-feather="facebook"></i></a>
                                </li>
                                <li>
                                    <a href="https://www.instagram.com/login" target="_blank"><i data-feather="instagram"> </i></a>
                                </li>
                            </ul>
                        </div>
                        {{-- <p>Don't have account?<a class="ms-2" href="{{ route('register') }}">Create Account</a></p> --}}
                    {!! Form::close(); !!}
                </div>
            </div>
        </div>
    </div>
</section>


    @push('scripts')

    @endpush

@endsection
