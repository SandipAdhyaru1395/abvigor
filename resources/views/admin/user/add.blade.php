@extends('admin.partials.layout')
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush
@section('content')
    <div class="admin container py-2">
        @include('admin.partials.sidebar')
        <div class="admin main-content p-4 table-responsive">
            <form class="mb-5" action="{{ route('admin.user.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-lg-6">
                        <label for="dealership_name" class="form-label align-self-end fw-bold">Dealership Name</label>
                        <input type="text" class="form-control" name="dealership_name" id="dealership_name"
                            value="{{ old('dealership_name') }}" autocomplete="off">
                        @error('dealership_name')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <label for="full_name" class="form-label align-self-end fw-bold">Full Name</label>
                        <input type="text" class="form-control" name="full_name" id="full_name"
                            value="{{ old('full_name') }}" autocomplete="off">
                        @error('full_name')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="username" class="form-label align-self-end fw-bold">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="username" id="username"
                            value="{{ old('username') }}" autocomplete="off">
                        @error('username')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="email" class="form-label align-self-end fw-bold">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}"
                         autocomplete="off">
                        @error('email')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="mobile" class="form-label align-self-end fw-bold">Mobile / Login Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" maxlength="10" onkeypress="return /^[0-9]+$/.test(event.key)" name="mobile" id="mobile" value="{{ old('mobile') }}"
                         autocomplete="off">
                        @error('mobile')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-12 mt-3">
                        <label for="address" class="form-label align-self-end fw-bold">Address</label>
                        <input type="text" class="form-control" name="address" id="address"
                            value="{{ old('address') }}" autocomplete="off">
                        @error('address')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="pincode" class="form-label align-self-end fw-bold">Pincode</label>
                        <input type="text" maxlength="6" onkeypress="return /^[0-9]+$/.test(event.key)" class="form-control" name="pincode" id="pincode"
                            value="{{ old('pincode') }}" autocomplete="off">
                        @error('pincode')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="city" class="form-label align-self-end fw-bold">City</label>
                        <input type="text" class="form-control" name="city" id="city"
                            value="{{ old('city') }}" autocomplete="off">
                        @error('city')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="state" class="form-label align-self-end fw-bold">State</label>
                        <input type="text" class="form-control" name="state" id="state"
                            value="{{ old('state') }}" autocomplete="off">
                        @error('state')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="gst_no" class="form-label align-self-end fw-bold">GST Number</label>
                        <input type="text" class="form-control" name="gst_no" id="gst_no"
                            value="{{ old('gst_no') }}" autocomplete="off">
                        @error('gst_no')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="phone" class="form-label align-self-end fw-bold">Phone Number</label>
                        <input type="text" maxlength="10" onkeypress="return /^[0-9]+$/.test(event.key)" class="form-control" name="phone" id="phone"
                            value="{{ old('phone') }}" autocomplete="off">
                        @error('phone')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        
                            <label for="activated_at" class="form-label align-self-end fw-bold">Activated at</label>
                            <input type="text" class="form-control datetimepicker" value="{{ old('activated_at') }}"
                                name="activated_at" autocomplete="off">
                            @error('activated_at')
                                <span class="text-danger error-text" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col">
                        <button type="submit" class="btn btn-sm btn-primary text-white">Create</button>
                        <input type="hidden" name="close" value="1" disabled>
                        <button type="submit" onclick="$('input[name=close]').prop('disabled', false);"
                            class="btn btn-sm btn-primary text-white">Create & Close</button>
                        <a href="{{ route('admin.user.list') }}"><button type="button"
                                class="btn btn-sm bg-danger text-white">Cancel</button></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $(document).ready(function() {
            flatpickr(".datetimepicker", {
                enableTime: true,
                 dateFormat: "d/m/Y H:i",
                 disableMobile: true,
                time_24hr: true,
                defaultDate: new Date()
            });

        })
    </script>
@endpush
