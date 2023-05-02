@extends('layouts.app')

@section('title',__('Register'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-9 mx-auto">
            <div class="card shadow-sm border-0 blur-bg" style="border-radius: 0.5rem;">
                <div class="row g-0">
                    <div class="col-md-6 col-lg-6 d-none d-md-block">
                        <img src="assets/img/edu/2.jpg" class="h-100 img-responsive" alt="login form" class="img-fluid" style="border-radius: 0.5rem 0rem 0rem 0.5rem;" />
                    </div>
                    <div class="col-md-6 col-lg-6 d-flex align-items-center">
                        <div class="card-body p-4 p-lg-5 text-black">

                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="d-flex align-items-center mb-3 pb-1">
                                    <i class="fas fa-address-card fa-2x me-3 text-primary"></i>
                                    <span class="h1"> {{ __('Register') }} </span>
                                </div>

                                <div class="form-outline mb-4">
                                    <label for="name">{{ __('Name') }}</label>
                                    <input id="name" type="text" class="form-control form-control-lg bg-white @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-outline mb-4">
                                    <label for="email">{{ __('Email Address') }}</label>
                                    <input id="email" type="email" class="form-control form-control-lg bg-white @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-outline mb-4">
                                    <label for="password">{{ __('Password') }}</label>
                                    <input id="password" type="password" class="form-control form-control-lg bg-white @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" required autocomplete="new-password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-outline mb-4">
                                    <label for="password-confirm">{{ __('Confirm Password') }}</label>
                                    <input id="password-confirm" type="password" class="form-control form-control-lg bg-white @error('password') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password">
                                </div>

                                <div class="w-100 pt-1 mb-3 d-flex justify-content-center">
                                    <button type="submit" class="btn btn-outline-primary btn-lg btn-block">{{ __('Register') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
