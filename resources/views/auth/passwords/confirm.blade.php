@extends('layouts.app')

@section('title',__('Confirm Password'))

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-7">
                <div class="card blur-bg shadow-sm border-0">
                    <div class="card-body p-lg-5">
                        <div class="d-flex align-items-center mb-3 pb-1">
                            <i class="fas fa-rotate-left fa-2x me-3 text-primary"></i>
                            <h1 class="text-gradient mb-4 text-center">{{ __('Confirm Password') }}</h1>
                        </div>
                        {{ __('Please confirm your password before continuing.') }}

                        <form class="form" method="POST" action="{{ route('password.confirm') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Confirm Password') }}
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
