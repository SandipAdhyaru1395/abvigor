@extends('admin.partials.layout')
@section('content')
    <div class="admin container py-2">
        @include('admin.partials.sidebar')
        <div class="admin main-content p-4 table-responsive">
            <div class="row">
                <div class="col">
                    <a href="{{ route('admin.user.list') }}"><button type="button" class="btn btn-sm btn-secondary text-white">Back to users list</button></a>
                    <a href="{{ route('admin.user.edit', $user->id) }}"><button type="button" class="btn btn-sm btn-primary text-white">Update details</button></a>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-lg-6">
                    <label for="dealership_name" class="form-label align-self-end fw-bold">Dealership Name</label>
                    <input type="text" class="form-control" name="dealership_name" id="dealership_name"
                        value="{{ $user->dealership_name }}" disabled autocomplete="off">
                </div>
                <div class="col-lg-6">
                    <label for="full_name" class="form-label align-self-end fw-bold">Full Name</label>
                    <input type="text" class="form-control" name="full_name" id="full_name" value="{{ $user->name }}"
                        disabled autocomplete="off">
                </div>
                <div class="col-lg-6 mt-3">
                    <label for="username" class="form-label align-self-end fw-bold">Username <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="username" id="username" value="{{ $user->username }}"
                        disabled autocomplete="off">
                </div>
                <div class="col-lg-6 mt-3">
                    <label for="email" class="form-label align-self-end fw-bold">Email <span
                            class="text-danger">*</span></label>
                    <input type="email" class="form-control" disabled name="email" id="email"
                        value="{{ $user->email }}" autocomplete="off">
                </div>
                <div class="col-lg-6 mt-3">
                    <label for="mobile" class="form-label align-self-end fw-bold">Mobile / Login Name <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control" disabled maxlength="10"
                        onkeypress="return /^[0-9]+$/.test(event.key)" name="mobile" id="mobile"
                        value="{{ $user->phone }}" autocomplete="off">
                </div>
                <div class="col-lg-12 mt-3">
                    <label for="address" class="form-label align-self-end fw-bold">Address</label>
                    <input type="text" class="form-control" name="address" id="address" value="{{ $user->address }}"
                        disabled autocomplete="off">
                </div>
                <div class="col-lg-6 mt-3">
                    <label for="pincode" class="form-label align-self-end fw-bold">Pincode</label>
                    <input type="text" maxlength="6" onkeypress="return /^[0-9]+$/.test(event.key)" class="form-control"
                        name="pincode" id="pincode" value="{{ $user->zip }}" disabled autocomplete="off">
                </div>
                <div class="col-lg-6 mt-3">
                    <label for="city" class="form-label align-self-end fw-bold">City</label>
                    <input type="text" class="form-control" name="city" id="city" value="{{ $user->city }}"
                        disabled autocomplete="off">
                </div>
                <div class="col-lg-6 mt-3">
                    <label for="state" class="form-label align-self-end fw-bold">State</label>
                    <input type="text" class="form-control" name="state" id="state" value="{{ $user->state }}"
                        disabled autocomplete="off">
                </div>
                <div class="col-lg-6 mt-3">
                    <label for="gst_no" class="form-label align-self-end fw-bold">GST Number</label>
                    <input type="text" class="form-control" name="gst_no" id="gst_no"
                        value="{{ $user->gst_number }}" disabled autocomplete="off">
                </div>
                <div class="col-lg-6 mt-3">
                    <label for="phone" class="form-label align-self-end fw-bold">Phone Number</label>
                    <input type="text" maxlength="10" onkeypress="return /^[0-9]+$/.test(event.key)"
                        class="form-control" name="phone" id="phone" value="{{ $user->mobile }}" disabled
                        autocomplete="off">
                </div>
                <div class="col-lg-6 mt-3">
                    <label for="activated_at" class="form-label align-self-end fw-bold">Activated at</label>
                    <input type="text" class="form-control datetimepicker"
                        value="{{ $user->activated_at ? \Carbon\Carbon::parse($user->activated_at)->format('d/m/Y H:i') : '' }}"
                        name="activated_at" disabled autocomplete="off">
                </div>
            </div>
        </div>
    </div>
@endsection
