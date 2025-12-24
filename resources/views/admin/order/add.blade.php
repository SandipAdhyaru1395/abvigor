@extends('admin.partials.layout')

@push('styles')
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

        .form-section-title {
            font-size: 16px;
            font-weight: 600;
            color: #34495e;
            margin-bottom: 10px;
        }

        .orders-card label.form-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .orders-card .form-control,
        .orders-card .form-select {
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.9rem;
            font-family: 'Segoe UI', sans-serif;
            border: 1px solid #dfe6e9;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.03);
            transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
        }

        .orders-card .form-control:focus,
        .orders-card .form-select:focus {
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
            justify-content: flex-end;
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
                align-items: stretch;
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
                            <i class="fas fa-plus-circle page-title-icon"></i>
                            Create Order
                        </h1>
                    </div>

                    <form class="mb-3" action="{{ route('admin.order.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <div class="form-section-title">Order Information</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="order_no" class="form-label align-self-end">Order No : <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="order_no" id="order_no"
                                    value="{{ old('order_no') }}" autocomplete="off">
                                @error('order_no')
                                    <span class="text-danger error-text" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="order_date" class="form-label align-self-end">Order Date : <span
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
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="customer_name" class="form-label align-self-end">Client : <span
                                        class="text-danger">*</span></label>
                                <select class="form-select select2" name="user_id" data-placeholder="Select Client"
                                    id="client" aria-label="Default select example">
                                    @if ($users)
                                        <option selected value="">Select client</option>
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
                            <div class="col-md-6">
                                <label for="brand" class="form-label align-self-end">Brand : <span
                                        class="text-danger">*</span></label>
                                <select class="form-select select2" name="brand_id" data-placeholder="Select Brand"
                                    id="brand" aria-label="Default select example">
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

                        <div class="row">
                            <div class="col">
                                <div class="action-buttons">
                                    <button type="submit" class="btn btn-sm btn-primary text-white">Create</button>
                                    <input type="hidden" name="close" value="1" disabled>
                                    <button type="submit" onclick="$('input[name=close]').prop('disabled', false);"
                                        class="btn btn-sm btn-primary text-white">Create &amp; Close</button>
                                    <a href="{{ route('admin.order.list') }}"
                                        class="btn btn-sm btn-secondary text-white">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
