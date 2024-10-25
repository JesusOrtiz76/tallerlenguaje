@extends('layouts.app')

@section('title',__('Register'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="card shadow-sm border-0 blur-bg">
                    <div class="row g-0">
                        <div class="col-md-3 col-lg-3 d-none d-md-block">
                            <img src="{{ asset('assets/img/bg-abstract.jpg') }}"
                                 class="h-100 img-responsive"
                                 alt="login form"
                                 style="border-radius: 0.5rem 0 0 0.5rem;">
                        </div>
                        <div class="col-md-9 col-lg-9 d-flex align-items-center">
                            <div class="card-body p-5 p-lg-5">

                                <form class="form" method="POST" action="{{ route('register') }}">
                                    @csrf

                                    <div class="d-flex align-items-center mb-3 pb-1">
                                        <i class="fas fa-address-card fa-2x me-3 text-primary"></i>
                                        <h1 class="text-gradient mb-4"> {{ __('Register') }} </h1>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <label for="name">Nombre completo</label>
                                        <input id="name"
                                               type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               name="name"
                                               value="{{ old('name') }}"
                                               oninput="toMay(this)"
                                               autocomplete="name"
                                               autofocus>
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-outline mb-4">
                                        <label for="rfc">{{ __('RFC') }}</label>
                                        <input id="rfc"
                                               type="text"
                                               class="form-control @error('rfc') is-invalid @enderror"
                                               name="rfc"
                                               value="{{ old('rfc') }}"
                                               oninput="toMay(this)"
                                               autocomplete="rfc"
                                               autofocus>
                                        @error('rfc')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-outline mb-4">
                                        <label for="oclave">{{ __('Clave del centro de trabajo') }}</label>
                                        <input id="oclave"
                                               type="text"
                                               class="form-control @error('oclave') is-invalid @enderror"
                                               name="oclave"
                                               value="{{ old('oclave') }}"
                                               oninput="toMay(this)"
                                               autocomplete="oclave"
                                               autofocus>
                                        @error('oclave')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-outline mb-4">
                                        <label for="email">{{ __('Email Address') }}</label>
                                        <input id="email"
                                               type="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               name="email"
                                               value="{{ old('email') }}"
                                               oninput="toMin(this)"
                                               autocomplete="email"
                                               autofocus
                                               onpaste="return false;" oncopy="return false;">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-outline mb-4">
                                        <label for="email-confirm">{{ __('Confirm Email Address') }}</label>
                                        <input id="email-confirm"
                                               type="email"
                                               class="form-control @error('email_confirmation') is-invalid @enderror"
                                               name="email_confirmation"
                                               value="{{ old('email_confirmation') }}"
                                               oninput="toMin(this)"
                                               autocomplete="email_confirmation"
                                               onpaste="return false;" oncopy="return false;">
                                        @error('email_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-outline mb-4">
                                        <label for="password">{{ __('Password') }}</label>
                                        <input id="password"
                                               type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               name="password"
                                               value="{{ old('password') }}"
                                               autocomplete="new-password"
                                               onpaste="return false;" oncopy="return false;">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-outline mb-4">
                                        <label for="password-confirm">{{ __('Confirm Password') }}</label>
                                        <input id="password-confirm"
                                               type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               name="password_confirmation"
                                               autocomplete="new-password"
                                               onpaste="return false;" oncopy="return false;">
                                    </div>

                                    <div class="w-100 pt-1 my-3 d-flex justify-content-center">
                                        <button type="submit"
                                                class="btn btn-outline-primary btn-block">{{ __('Register') }}
                                        </button>
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
