@extends('admin.partials.layout')

@section('content')
    <div class="admin container py-2">
        @include('admin.partials.sidebar')
        <!-- Main Content -->
        <div class="admin main-content p-4" style="">
            <form id="profileForm" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <label for="login" class="form-label">Login <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="login" name="login"
                            value="{{ $admin->login }}" autocomplete="off">
                        @error('login')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ $admin->email }}" autocomplete="off">
                        @error('email')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="first_name" name="first_name"
                            value="{{ $admin->first_name }}" autocomplete="off">
                        @error('first_name')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="last_name" name="last_name"
                            value="{{ $admin->last_name }}" autocomplete="off">
                        @error('last_name')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" autocomplete="off">
                        @error('password')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="off">
                        @error('password_confirmation')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mt-3">
                         <button type="submit" class="btn btn-sm btn-primary text-white">Save</button>
                        <input type="hidden" name="close" value="1" disabled>
                        <button type="submit" onclick="$('input[name=close]').prop('disabled', false);"
                            class="btn btn-sm btn-secondary text-white">Save & Close</button>
                    </div>
                </div>
            </form>
        </div>
    @endsection
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#profileForm').on('submit', function(e) {
                    e.preventDefault();
                   
                    var login = $('#login').val();
                    var email = $('#email').val();
                    var first_name = $('#first_name').val();
                    var last_name = $('#last_name').val();
                    var password = $('#password').val();
                    var password_confirmation = $('#password_confirmation').val();

                    if (login == '') {
                        toastr.error('Login is required');
                        return false;
                    }
                    if (email == '') {
                        toastr.error('Email is required');
                        return false;
                    }
                    if (first_name == '') {
                        toastr.error('First name is required');
                        return false;
                    }
                    if (last_name == '') {
                        toastr.error('Last name is required');
                        return false;
                    }
                    if (password != password_confirmation) {
                        toastr.error('Password does not match');
                        return false;
                    }
                    $(this).off('submit').submit();

                })
            });
        </script>
    @endpush
