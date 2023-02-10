@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Introduction') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <p>{{ __('Welcom to the course') }}</p>
                        <a href="{{ route('cursos.index') }}" class="btn btn-primary">{{ __('Go to course') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
