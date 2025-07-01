@extends('front.partials.layout')

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-base text-white">
                        <h4 class="mb-0">Login</h4>
                    </div>
                    <div class="card-body">
                        <form id="loginForm" method="POST" action="{{ route('post.login') }}">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-xl-8 mb-3">
                                    <label for="mobile" class="form-label">Mobile <span
                                            class="text-danger">*</span></label>
                                    <div class="d-flex gap-3">
                                        <input id="mobile" type="text" class="form-control w-70" maxlength="10"
                                            name="mobile" value="{{ old('mobile') }}" autocomplete="off"
                                            placeholder="Enter mobile no" onkeypress="return /[0-9]/i.test(event.key)">

                                        <button type="submit" class="btn bg-base text-white">
                                            Login
                                        </button>

                                    </div>
                                    @error('mobile')
                                        <span class="text-danger error-text" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

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

