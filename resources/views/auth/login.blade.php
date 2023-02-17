@extends('layouts.app')

@section('title',__('Login'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-9 mx-auto">
            <div class="card border-0 shadow-lg" style="border-radius: 0.5rem;">
                <div class="row g-0">
                    <div class="col-md-6 col-lg-6 d-flex align-items-center">
                        <div class="card-body p-4 p-lg-5 text-black">

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="d-flex align-items-center mb-3 pb-1">
                                    <i class="fas fa-key fa-2x me-3 text-primary"></i>
                                    <span class="h1 fw-bold mb-0"> {{ __('Login') }} </span>
                                </div>

                                <h5 class="fw-normal mb-4 pb-3" style="letter-spacing: 1px;"> {{ __('Sign into your account') }} </h5>

                                <div class="form-outline mb-4">
                                    <label for="email">{{ __('E-Mail Address') }}</label>
                                    <input id="email" type="email" class="form-control form-control-lg bg-white @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-outline mb-1">
                                    <label for="password">{{ __('Password') }}</label>
                                    <input id="password" type="password" class="form-control form-control-lg bg-white @error('password') is-invalid @enderror" name="password" autocomplete="current-password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>

                                @if (Route::has('password.request'))
                                <div class="w-100 mb-4 d-flex justify-content-end">
                                    <a class="btn btn-link pt-3" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                </div>
                                @endif

                                <div class="w-100 pt-1 mb-3 d-flex justify-content-center">
                                    <button class="btn btn-outline-primary btn-lg btn-block">{{ __('Login') }}</button>
                                </div>
                                @if (Route::has('register'))
                                <p class="mb-5 pt-3" style="color: #393f81;"> {{ __("Don't have an account?") }}
                                    <a href="{{ route('register') }}" style="color: #393f81;">{{ __('Sign up') }}</a>
                                </p>
                                @endif
                            </form>

                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 d-none d-md-block">
                        <img src="assets/img/edu/1.jpg" class="h-100 img-responsive" alt="login form" class="img-fluid" style="border-radius: 0 0.5rem 0.5rem 0;" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection