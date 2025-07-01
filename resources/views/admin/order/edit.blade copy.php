@extends('admin.partials.layout')
@push('styles')
    <style>
        input.form-control {
            border-radius: 0.3rem;
            padding: 0.4rem 0.6rem;
            font-size: 0.875rem;
            /* small but readable */
            font-family: 'Segoe UI', sans-serif;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: border-color 0.2s, box-shadow 0.2s;
        }
    </style>
@endpush
@section('content')
    <div class="admin container py-2">
        @include('admin.partials.sidebar')
        <div class="admin main-content p-4 table-responsive">
            <div class="row">
                <div class="col text-end">
                    <a href="{{ route('admin.order.list') }}"><button type="button"
                            class="btn bg-base text-white">Back</button></a>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <h4>Edit Order</h4>
                    <hr>
                </div>
            </div>
            <form action="{{ route('admin.order.update') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $order->id }}">
                <div class="row mb-3">
                    <div class="col">
                        <label for="order_no" class="form-label align-self-end fw-bold">Order No : <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="order_no" id="order_no"
                            value="{{ $order->order_no }}" autocomplete="off">
                        @error('order_no')
                            <span class="text-danger error-text" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="order_date" class="form-label align-self-end fw-bold">Order Date : <span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker" name="order_date" id="order_date"
                            value="{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}" readonly
                            autocomplete="off">
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
                        <select class="form-select select2" name="user_id" data-placeholder="Select Client" id="client" aria-label="Default select example">
                            @if ($users)
                                <option selected value="">Select brand</option>
                            @endif
                            @forelse ($users as $user)
                                <option value="{{ $user->id }}" @selected($user->id == $order->user_id)>{{ $user->name }}</option>
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
                        <select class="form-select select2" name="brand_id" data-placeholder="Select Brand" id="brand" aria-label="Default select example">
                            @if ($brands)
                                <option selected value="">Select brand</option>
                            @endif
                            @forelse ($brands as $brand)
                                <option value="{{ $brand->id }}" @selected($brand->id == $order->category_id)>{{ $brand->title }}</option>
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
                <div class="row mb-5">
                    <div class="col text-center">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Products</h5>
                </div>
                <div class="card-body mt-4">
                    <form action="{{ route('admin.order.product.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <input type="hidden" name="order_no" value="{{ $order->order_no }}">
                        <input type="hidden" name="user_email" value="{{ $order->user->email }}">
                        <input type="hidden" name="category_title" value="{{ $order->brand->title }}">

                        <div class="row mb-3">
                            <div class="col-lg-7 mb-3 mb-lg-0">
                                <select class="form-select select2" data-placeholder="Select Product" name="product_id" aria-label="Select product">
                                    @if ($products)
                                        <option selected value="">Select product</option>
                                    @endif
                                    @forelse ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->product_code }} -
                                            {{ $product->title }}</option>
                                    @empty
                                        <option value="">No product found</option>
                                    @endforelse
                                </select>
                                @error('product_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                                <input type="number" class="form-control" name="quantity" placeholder="Quantity">
                                @error('quantity')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-lg-2">
                                <button type="submit" class="btn btn-primary text-white">Add</button>
                            </div>
                        </div>
                    </form>
                    <table id="order-products-table" class="table mt-5">
                        <thead>
                            <tr>
                                <th scope="col">Part No</th>
                                <th scope="col">Product</th>
                                <th scope="col">Quantity</th>
                                <th scope="col" width="25%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->products as $product)
                                <tr>
                                    <td>{{ $product->product->product_code }}</td>
                                    <td>{{ $product->product->title }}</td>
                                    <td width="20%"><input type="text" class="form-control input-qty"
                                            value="{{ $product->qty }}"></td>
                                    <td>
                                        <button data-url="{{ route('admin.order.product.updateQty', $product->id) }}"
                                            class="btn btn-update btn-sm btn-secondary  mb-xl-0 mb-2">Update</button>
                                        <a href="{{ route('admin.order.product.remove', $product->id) }}"
                                            class="btn btn-sm btn-danger">Remove</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No Records Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.btn-update', function(e) {
                var row = $(this).closest('tr');
                var qty = row.find('.input-qty').val();

                var url = $(this).data('url');
                url += '?qty=' + qty;

                window.location.href = url;
            });
        });
    </script>
@endpush
