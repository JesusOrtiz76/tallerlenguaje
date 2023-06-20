@extends('layouts.app')

@section('title',__('Verify Your Email Address'))

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-7">
                <div class="card blur-bg shadow-sm border-0">
                    <div class="card-body p-lg-5">
                        <div class="d-flex align-items-center mb-3 pb-1">
                            <i class="fas fa-envelope fa-2x me-3 text-primary"></i>
                            <h1 class="text-gradient mb-4">{{ __('Verify Your Email Address') }}</h1>
                        </div>
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                            </div>
                        @endif

                        {{ __("Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.") }}
                        <br>
                        <form class="form" class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary align-baseline mt-5">{{ __('Resend Verification Email') }}</button>.
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
