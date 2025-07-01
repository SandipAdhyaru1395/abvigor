@extends('admin.partials.layout')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-base text-white">
                        <h4 class="mb-0">Admin Login</h4>
                    </div>
                    <div class="card-body">
                        <form id="loginForm" method="POST" action="{{ route('admin.post.login') }}">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-xl-8">
                                    <label for="username" class="form-label">Username <span
                                            class="text-danger">*</span></label>
                                    <div class="d-flex gap-3">
                                        <input id="username" type="text" class="form-control w-70" maxlength="10"
                                            name="username" value="{{ old('username') }}" autocomplete="off"
                                            placeholder="Enter username">
                                    </div>
                                    @error('username')
                                        <span class="text-danger error-text" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-xl-8 mb-3">
                                    <label for="password" class="form-label">Password <span
                                            class="text-danger">*</span></label>
                                    <div class="d-flex gap-3">
                                        <input id="password" type="password" class="form-control w-70" maxlength="10"
                                            name="password" value="{{ old('password') }}" autocomplete="off"
                                            placeholder="Enter password">
                                    </div>
                                    @error('password')
                                        <span class="text-danger error-text" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn bg-base text-white">
                                Login
                            </button>
                            <div class="mt-3 text-center">
                                <a href="{{ route('get.register') }}">Don't have an account? Register</a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection