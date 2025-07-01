@extends('front.partials.layout')

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-base text-white">
                        <h4 class="mb-0">Register</h4>
                    </div>
                    <div class="card-body">
                        <form id="registerForm" method="POST" action="{{ route('post.register') }}">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-xl-6 mb-3">
                                    <label for="dealer_name" class="form-label">Dealer Name <span
                                            class="text-danger">*</span></label>
                                    <input id="dealer_name" type="text" class="form-control" name="dealer_name"
                                        value="{{ old('dealer_name') }}" placeholder="Enter dealer name" autofocus autocomplete="off">
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label for="gst_no" class="form-label">GST No <span
                                            class="text-danger">*</span></label>
                                    <input id="gst_no" type="text" class="form-control" name="gst_no" value="{{ old('gst_no') }}"
                                        placeholder="Enter GST No" autocomplete="off">
                                    @error('gst_no')
                                        <span class="text-danger error-text" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="full_name" class="form-label">Full Name <span
                                            class="text-danger">*</span></label>
                                    <input id="full_name" type="text" class="form-control" name="full_name"
                                        value="{{ old('full_name') }}" placeholder="Enter full name" autocomplete="off">
                                    @error('full_name')
                                        <span class="text-danger error-text" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="address" class="form-label">Address <span
                                            class="text-danger">*</span></label>
                                    <input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}"
                                        autocomplete="off" placeholder="Enter address">
                                    @error('address')
                                        <span class="text-danger error-text" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                    <input id="city" type="text" class="form-control" name="city" value="{{ old('city') }}"
                                        autocomplete="off" placeholder="Enter city">
                                    @error('city')
                                        <span class="text-danger error-text" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label for="pincode" class="form-label">Pin Code <span
                                            class="text-danger">*</span></label>
                                    <input id="pincode" type="text" onkeypress="return /[0-9]/i.test(event.key)" maxlength="6" class="form-control" name="pincode" value="{{ old('pincode') }}"
                                        autocomplete="off" placeholder="Enter pincode">
                                    @error('pincode')
                                        <span class="text-danger error-text" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label for="state" class="form-label">State <span
                                            class="text-danger">*</span></label>
                                    <input id="state" type="text" class="form-control" name="state" value="{{ old('state') }}"
                                        autocomplete="off" placeholder="Enter state">
                                    @error('state')
                                        <span class="text-danger error-text" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label for="phone" class="form-label">Phone <span
                                            class="text-danger">*</span></label>
                                    <input id="phone" type="text" class="form-control" maxlength="10" name="phone" value="{{ old('phone') }}"
                                        autocomplete="off" placeholder="Enter phone no" onkeypress="return /[0-9]/i.test(event.key)">
                                    @error('phone')
                                        <span class="text-danger error-text" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label for="mobile" class="form-label">Alternate Mobile (Optional)</label>
                                    <input id="mobile" type="text" class="form-control" name="mobile"
                                        value="{{ old('mobile') }}" maxlength="10" onkeypress="return /[0-9]/i.test(event.key)" autocomplete="off" placeholder="Enter mobile no">
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label for="email" class="form-label">Email Address <span
                                            class="text-danger">*</span></label>
                                    <input id="email" type="email" class="form-control" name="email"
                                        value="{{ old('email') }}" autocomplete="off" placeholder="Enter email address">
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn bg-base text-white">
                                    Register
                                </button>
                            </div>

                            <div class="mt-3 text-center">
                                <a href="{{ route('get.login') }}">Already have an account? Login</a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // $('#gst_no').on('change', function() {

            //     isValid=isValidGSTIN($(this).val());

            //     if(!isValid){
            //         toastr.error('Invalid GSTIN');
            //     }
            // });

            $('#registerForm').on('submit', function(e) {

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
                if(gst_no=="" || gst_no==null){
                    toastr.error('Please enter GST No');
                    return false;
                }
                if(full_name=="" || full_name==null){
                    toastr.error('Please enter full name');
                    return false;
                }
                if(address=="" || address==null){
                    toastr.error('Please enter address');
                    return false;
                }
                if(city=="" || city==null){
                    toastr.error('Please enter city');
                    return false;
                }
                if(pincode=="" || pincode==null){
                    toastr.error('Please enter pincode');
                    return false;
                }
                if(state=="" || state==null){
                    toastr.error('Please enter state');
                    return false;
                }
                if(phone=="" || phone==null){
                    toastr.error('Please enter phone');
                    return false;
                }
                if(email=="" || email==null){
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
