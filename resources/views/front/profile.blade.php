@extends('front.partials.layout')

@section('content')
    <div class="front container my-5">
        @include('front.partials.sidebar')
        <div class="front main-content p-4">
            <div class="profile-card">
                <div class="profile-header">
                    <h4><i class="fa fa-user-circle"></i> Profile</h4>
                    <p class="profile-subtitle">Update your dealer and contact details</p>
                </div>
                <div class="profile-body">
                    <form id="userForm" method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-xl-6 mb-3">
                                <label for="dealer_name" class="form-label">Dealer Name <span class="text-danger">*</span></label>
                                <input id="dealer_name" type="text" class="form-control auth-input" name="dealer_name"
                                    value="{{ old('dealer_name') ?? $user->dealership_name }}" placeholder="Enter dealer name"
                                    autofocus autocomplete="off">
                                @error('dealer_name')
                                    <span class="text-danger error-text" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label for="gst_no" class="form-label">GST No <span class="text-danger">*</span></label>
                                <input id="gst_no" type="text" class="form-control auth-input" name="gst_no"
                                    value="{{ old('gst_no') ?? $user->gst_number }}" placeholder="Enter GST No" autocomplete="off">
                                @error('gst_no')
                                    <span class="text-danger error-text" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input id="full_name" type="text" class="form-control auth-input" name="full_name"
                                    value="{{ old('full_name') ?? $user->name }}" placeholder="Enter full name" autocomplete="off">
                                @error('full_name')
                                    <span class="text-danger error-text" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                <input id="address" type="text" class="form-control auth-input" name="address"
                                    value="{{ old('address') ?? $user->address }}" autocomplete="off" placeholder="Enter address">
                                @error('address')
                                    <span class="text-danger error-text" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                <input id="city" type="text" class="form-control auth-input" name="city"
                                    value="{{ old('city') ?? $user->city }}" autocomplete="off" placeholder="Enter city">
                                @error('city')
                                    <span class="text-danger error-text" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label for="pincode" class="form-label">Pin Code <span class="text-danger">*</span></label>
                                <input id="pincode" type="text" onkeypress="return /[0-9]/i.test(event.key)" maxlength="6"
                                    class="form-control auth-input" name="pincode" value="{{ old('pincode') ?? $user->zip }}"
                                    autocomplete="off" placeholder="Enter pincode">
                                @error('pincode')
                                    <span class="text-danger error-text" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                                <input id="state" type="text" class="form-control auth-input" name="state"
                                    value="{{ old('state') ?? $user->state }}" autocomplete="off" placeholder="Enter state">
                                @error('state')
                                    <span class="text-danger error-text" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                <input id="phone" type="text" class="form-control auth-input" maxlength="10" name="phone"
                                    value="{{ old('phone') ?? $user->phone }}" autocomplete="off" placeholder="Enter phone no"
                                    onkeypress="return /[0-9]/i.test(event.key)">
                                @error('phone')
                                    <span class="text-danger error-text" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label for="mobile" class="form-label">Alternate Mobile (Optional)</label>
                                <input id="mobile" type="text" class="form-control auth-input" name="mobile"
                                    value="{{ old('mobile') ?? $user->mobile }}" maxlength="10"
                                    onkeypress="return /[0-9]/i.test(event.key)" autocomplete="off"
                                    placeholder="Enter mobile no">
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input id="email" type="email" class="form-control auth-input" name="email"
                                    value="{{ old('email') ?? $user->email }}" autocomplete="off"
                                    placeholder="Enter email address">
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-auth-submit">
                                <i class="fa fa-save"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
<style>
    .profile-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        padding: 24px 24px 20px;
        border-top: 4px solid #ed1c24;
    }

    .profile-header {
        margin-bottom: 20px;
        border-bottom: 1px solid #f1f1f1;
        padding-bottom: 12px;
    }

    .profile-header h4 {
        margin: 0 0 4px;
        font-weight: 700;
        color: #333333;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .profile-header h4 i {
        color: #ed1c24;
    }

    .profile-subtitle {
        margin: 0;
        font-size: 0.9rem;
        color: #777777;
    }

    .profile-body .form-label {
        font-weight: 600;
        color: #555;
    }

    .auth-input {
        border-radius: 8px;
        border-color: #ced4da;
        padding: 0.5rem 0.75rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        background-color: #fdfdfd;
    }

    .auth-input:focus {
        border-color: #ed1c24;
        box-shadow: 0 0 0 0.16rem rgba(237, 28, 36, 0.18);
        background-color: #ffffff;
    }

    .btn-auth-submit {
        background: linear-gradient(135deg, #ed1c24 0%, #c91a20 100%);
        color: #ffffff;
        font-weight: 600;
        border: none;
        border-radius: 10px;
        padding: 9px 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(237, 28, 36, 0.35);
        transition: all 0.25s ease;
    }

    .btn-auth-submit:hover {
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(237, 28, 36, 0.45);
    }

    .btn-auth-submit:active {
        transform: translateY(0);
        box-shadow: 0 3px 8px rgba(237, 28, 36, 0.35);
    }

    .btn-auth-submit i {
        font-size: 0.9rem;
    }

    .error-text {
        font-size: 0.85rem;
        margin-top: 4px;
        display: block;
    }

    @media (max-width: 768px) {
        .profile-card {
            padding: 20px 16px 18px;
            border-radius: 12px;
        }
    }
</style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            // $('#gst_no').on('change', function() {

            //     isValid=isValidGSTIN($(this).val());

            //     if(!isValid){
            //         toastr.error('Invalid GSTIN');
            //     }
            // });

            $('#userForm').on('submit', function(e) {

                e.preventDefault();

                var dealer_name = $('#dealer_name').val().trim();
                var gst_no = $('#gst_no').val().trim();
                var full_name = $('#full_name').val().trim();
                var address = $('#address').val().trim();
                var city = $('#city').val().trim();
                var pincode = $('#pincode').val().trim();
                var state = $('#state').val().trim();
                var phone = $('#phone').val().trim();
                var email = $('#email').val().trim();

                if (dealer_name == "" || dealer_name == null) {
                    toastr.error('Please enter dealer name');
                    return false;
                }
                if (gst_no == "" || gst_no == null) {
                    toastr.error('Please enter GST No');
                    return false;
                }
                if (full_name == "" || full_name == null) {
                    toastr.error('Please enter full name');
                    return false;
                }
                if (address == "" || address == null) {
                    toastr.error('Please enter address');
                    return false;
                }
                if (city == "" || city == null) {
                    toastr.error('Please enter city');
                    return false;
                }
                if (pincode == "" || pincode == null) {
                    toastr.error('Please enter pincode');
                    return false;
                }
                if (state == "" || state == null) {
                    toastr.error('Please enter state');
                    return false;
                }
                if (phone == "" || phone == null) {
                    toastr.error('Please enter phone');
                    return false;
                }
                if (email == "" || email == null) {
                    toastr.error('Please enter email');
                    return false;
                }

                $(this).off('submit').submit();
            });
        });

        function isValidGSTIN(gstin) {
            // GSTIN format: 2 digits + 10 alphanumeric (PAN) + 1 digit + 1 char (Z) + 1 alphanumeric
            const regex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;
            return regex.test(gstin);
        }
    </script>
@endpush
