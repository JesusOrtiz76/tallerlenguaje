@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-7">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-lg-5 text-black">
                        <div class="d-flex align-items-center mb-3 pb-1">
                            <i class="fas fa-rotate-left fa-2x me-3 text-primary"></i>
                            <span class="h1 fw-bold mb-0">{{ __('Reset Password') }}</span>
                        </div>
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="form-outline mb-4">
                                <label for="email">{{ __('Email Address') }}</label>
                                <input id="email" type="email" class="form-control form-control-lg bg-white @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>
                            <div class="w-100 pt-1 mb-3 d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary">{{ __('Send Password Reset Link') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
