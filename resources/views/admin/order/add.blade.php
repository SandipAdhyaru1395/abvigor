@extends('admin.partials.layout')

@section('content')
    <div class="admin container py-2">
        @include('admin.partials.sidebar')
        <div class="admin main-content p-4 table-responsive">
            <form class="mb-5" action="{{ route('admin.order.store') }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col">
                        <label for="order_no" class="form-label align-self-end fw-bold">Order No : <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="order_no" id="order_no"
                            value="{{ old('order_no') }}" autocomplete="off">
                        @error('order_no')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="order_date" class="form-label align-self-end fw-bold">Order Date : <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker" name="order_date" id="order_date"
                            value="{{ old('order_date') }}" readonly autocomplete="off">
                        @error('order_date')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col">
                        <label for="customer_name" class="form-label align-self-end fw-bold">Client : <span
                                class="text-danger">*</span></label>
                        <select class="form-select select2" name="user_id" data-placeholder="Select Client" id="client"
                            aria-label="Default select example">
                            @if ($users)
                                <option selected value="">Select brand</option>
                            @endif
                            @forelse ($users as $user)
                                <option value="{{ $user->id }}" @selected($user->id == old('user_id'))>{{ $user->name }}</option>
                            @empty
                                <option value="">No client found</option>
                            @endforelse
                        </select>
                        @error('user_id')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="brand" class="form-label align-self-end fw-bold">Brand : <span
                                class="text-danger">*</span></label>
                        <select class="form-select select2" name="brand_id" data-placeholder="Select Brand" id="brand"
                            aria-label="Default select example">
                            @if ($brands)
                                <option selected value="">Select brand</option>
                            @endif
                            @forelse ($brands as $brand)
                                <option value="{{ $brand->id }}" @selected($brand->id == old('brand_id'))>{{ $brand->title }}</option>
                            @empty
                                <option value="">No brand found</option>
                            @endforelse
                        </select>
                        @error('brand_id')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row position-sticky" style="bottom: 0;">
                    <div class="col text-end">
                        <button type="submit" class="btn btn-sm btn-primary text-white">Create</button>
                        <input type="hidden" name="close" value="1" disabled>
                        <button type="submit" onclick="$('input[name=close]').prop('disabled', false);" class="btn btn-sm btn-primary text-white">Create & Close</button>
                        <a href="{{ route('admin.order.list') }}"><button type="button"
                                class="btn btn-sm bg-danger text-white">Cancel</button></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
