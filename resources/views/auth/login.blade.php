@extends('layouts.login')

@section('title', __('Login'))

@push('styles')
    {{-- White background login (Smart Admin 5.0.3) --}}
    <style>
        .hero-section { position: relative; min-height: 100vh; display: flex; align-items: center; background: #f8f9fa; }
        .login-card { position: relative; z-index: 1; background: #ffffff; }
        .form-label { color: #212529; }
        .text-muted-soft { color: #6c757d; }
    </style>
@endpush

@section('content')
    {{-- Smart Admin 5.0.3: white login page --}}
    <section class="hero-section position-relative overflow-hidden py-5">
        <div class="container" style="position: relative; z-index: 1;">
            <div class="row justify-content-center">
                <div class="col-11 col-md-8 col-lg-6 col-xl-4">
                    <div class="login-card p-4 p-md-5 rounded-4 shadow-lg border">
                        <h2 class="text-center mb-4 text-dark">{{ config('app.name') }}</h2>

                        @if (session('success'))
                            <div class="alert alert-success text-center">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="user_email" class="form-label">{{ __('Email') }}</label>
                                <input type="email"
                                       class="form-control form-control-lg @error('user.email') is-invalid @enderror"
                                       id="user_email"
                                       name="user[email]"
                                       value="{{ old('user.email') }}"
                                       placeholder="{{ __('Your Email') }}"
                                       required
                                       autofocus
                                       autocomplete="email">
                                @error('user.email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="user_password" class="form-label">{{ __('Password') }}</label>
                                <div class="input-group">
                                    <input type="password"
                                           class="form-control form-control-lg @error('user.password') is-invalid @enderror"
                                           id="user_password"
                                           name="user[password]"
                                           placeholder="{{ __('Your Password') }}"
                                           required
                                           autocomplete="current-password">
                                </div>
                                @error('user.password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg bg-primary bg-opacity-75 border-0">{{ __('Login') }}</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
