@extends('admin.partials.layout')
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .orders-page-wrapper {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }

        .orders-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .orders-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
        }

        .page-header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-title-icon {
            font-size: 24px;
            color: #667eea;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .orders-card label.form-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .orders-card .form-control {
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.9rem;
            font-family: 'Segoe UI', sans-serif;
            border: 1px solid #dfe6e9;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.03);
            transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
        }

        .orders-card .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
            outline: none;
            background-color: #ffffff;
        }

        .orders-card .error-text {
            font-size: 0.8rem;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-primary,
        .btn-danger,
        .btn-secondary {
            border-radius: 8px;
            font-weight: 600;
            padding: 8px 18px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(102, 126, 234, 0.5);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ed1c24 0%, #ff6b6b 100%);
        }

        .btn-danger:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(237, 28, 36, 0.5);
        }

        .btn-secondary {
            background: #95a5a6;
        }

        @media (max-width: 768px) {
            .orders-card {
                padding: 20px;
                border-radius: 12px;
            }

            .page-title {
                font-size: 20px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endpush
@section('content')
    <div class="orders-page-wrapper">
        <div class="admin container-fluid py-2">
            @include('admin.partials.sidebar')
            <div class="admin main-content p-4">
                <div class="orders-card">
                    <div class="page-header">
                        <h1 class="page-title">
                            <i class="fas fa-user-edit page-title-icon"></i>
                            Edit User
                        </h1>
                    </div>

                    <form class="mb-3" action="{{ route('admin.user.update') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="row mb-3">
                    <div class="col-lg-6">
                        <label for="dealership_name" class="form-label align-self-end fw-bold">Dealership Name</label>
                        <input type="text" class="form-control" name="dealership_name" id="dealership_name"
                            value="{{ $user->dealership_name }}" autocomplete="off">
                        @error('dealership_name')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <label for="full_name" class="form-label align-self-end fw-bold">Full Name</label>
                        <input type="text" class="form-control" name="full_name" id="full_name"
                            value="{{ $user->name }}" autocomplete="off">
                        @error('full_name')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="username" class="form-label align-self-end fw-bold">Username <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="username" id="username"
                            value="{{ $user->username }}" autocomplete="off">
                        @error('username')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="email" class="form-label align-self-end fw-bold">Email <span
                                class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" id="email" value="{{ $user->email }}"
                            autocomplete="off">
                        @error('email')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="mobile" class="form-label align-self-end fw-bold">Mobile / Login Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" maxlength="10"
                            onkeypress="return /^[0-9]+$/.test(event.key)" name="mobile" id="mobile"
                            value="{{ $user->phone }}" autocomplete="off">
                        @error('mobile')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-12 mt-3">
                        <label for="address" class="form-label align-self-end fw-bold">Address</label>
                        <input type="text" class="form-control" name="address" id="address"
                            value="{{ $user->address }}" autocomplete="off">
                        @error('address')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="pincode" class="form-label align-self-end fw-bold">Pincode</label>
                        <input type="text" maxlength="6" onkeypress="return /^[0-9]+$/.test(event.key)"
                            class="form-control" name="pincode" id="pincode" value="{{ $user->mobile }}"
                            autocomplete="off">
                        @error('pincode')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="city" class="form-label align-self-end fw-bold">City</label>
                        <input type="text" class="form-control" name="city" id="city"
                            value="{{ $user->city }}" autocomplete="off">
                        @error('city')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="state" class="form-label align-self-end fw-bold">State</label>
                        <input type="text" class="form-control" name="state" id="state"
                            value="{{ $user->state }}" autocomplete="off">
                        @error('state')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="gst_no" class="form-label align-self-end fw-bold">GST Number</label>
                        <input type="text" class="form-control" name="gst_no" id="gst_no"
                            value="{{ $user->gst_number }}" autocomplete="off">
                        @error('gst_no')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="phone" class="form-label align-self-end fw-bold">Phone Number</label>
                        <input type="text" maxlength="10" onkeypress="return /^[0-9]+$/.test(event.key)"
                            class="form-control" name="phone" id="phone" value="{{ $user->mobile }}"
                            autocomplete="off">
                        @error('phone')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="activated_at" class="form-label align-self-end fw-bold">Activated at</label>
                        <input type="text" class="form-control datetimepicker1"
                            value="{{ $user->activated_at ? \Carbon\Carbon::parse($user->activated_at)->format('d/m/Y H:i') : '' }}"
                            name="activated_at" autocomplete="off">
                        @error('activated_at')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                        <div class="row mt-4 align-items-center">
                            <div class="col-md-8 mb-3 mb-md-0">
                                <div class="action-buttons">
                                    <button type="submit" class="btn btn-sm btn-primary text-white">Save</button>
                                    <input type="hidden" name="close" value="1" disabled>
                                    <button type="submit"
                                        onclick="$('input[name=close]').prop('disabled', false);"
                                        class="btn btn-sm btn-primary text-white">Save &amp; Close</button>
                                    <a href="{{ route('admin.user.list') }}"
                                        class="btn btn-sm btn-secondary text-white">Cancel</a>
                                </div>
                            </div>
                            @if(!$user->deleted_at)
                                <div class="col-md-4 text-md-end">
                                    <a href="{{ route('admin.user.delete', $user->id) }}"
                                        class="btn btn-sm btn-danger" id="delete-user">Delete User</a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $(document).ready(function() {
            flatpickr(".datetimepicker1", {
                enableTime: true,
                 dateFormat: "d/m/Y H:i",
                 disableMobile: true,
                time_24hr: true,
                 
            });

        })
    </script>
@endpush
