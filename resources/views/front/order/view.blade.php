@extends('front.partials.layout')

@section('content')
    <div class="front container my-5">
        @include('front.partials.sidebar')

        <div class="front main-content p-4">
            <div class="order-view-wrapper">
                <div class="view-header mb-4">
                    <h4 class="mb-0"><i class="fa fa-file-text-o"></i> Order Details</h4>
                    <a href="{{ route('order.list') }}">
                        <button class="btn btn-back">
                            <i class="fa fa-arrow-left"></i> Back
                        </button>
                    </a>
                </div>

                <!-- Order Information Card -->
                <div class="order-info-card mb-4">
                    <h5 class="card-title"><i class="fa fa-info-circle"></i> Order Information</h5>
                    <div class="order-details-table">
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fa fa-hashtag"></i> Order Number
                            </div>
                            <div class="detail-value">
                                <strong>{{ $order->order_no }}</strong>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fa fa-truck"></i> Tractor Brand
                            </div>
                            <div class="detail-value">{{ $order->brand->title }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fa fa-calendar"></i> Order Date
                            </div>
                            <div class="detail-value">{{ date('d-m-Y', strtotime($order->created_at)) }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fa fa-clock-o"></i> Order Time
                            </div>
                            <div class="detail-value">{{ date('h:i A', strtotime($order->created_at)) }}</div>
                        </div>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="products-table-wrapper">
                    <h5 class="table-section-title"><i class="fa fa-cubes"></i> Order Products</h5>
                    <div class="table-responsive">
                        <table class="table products-table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Product Code</th>
                                    <th scope="col">Product Title</th>
                                    <th scope="col">Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->products as $key => $product)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td><strong>{{ $product->product->product_code }}</strong></td>
                                        <td>{{ $product->product->title }}</td>
                                        <td>{{ $product->qty }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .order-view-wrapper {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        padding: 24px 24px 20px;
        border-top: 4px solid #ed1c24;
    }

    .view-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .view-header h4 {
        color: #333;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .view-header h4 i {
        color: #ed1c24;
    }

    .btn-back {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        color: #ffffff;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
    }

    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(108, 117, 125, 0.4);
        color: #ffffff;
    }

    .order-info-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 10px;
        padding: 25px;
        border-left: 4px solid #ed1c24;
    }

    .order-info-card .card-title {
        color: #333;
        font-weight: 700;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.2rem;
    }

    .order-info-card .card-title i {
        color: #ed1c24;
    }

    .order-details-table {
        display: grid;
        gap: 15px;
    }

    .detail-row {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 15px;
        padding: 12px 0;
        border-bottom: 1px solid #dee2e6;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: #666;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-label i {
        color: #ed1c24;
        width: 20px;
    }

    .detail-value {
        color: #333;
        font-size: 1rem;
    }

    .detail-value strong {
        color: #ed1c24;
        font-size: 1.1rem;
    }

    .products-table-wrapper {
        margin-top: 50px;
    }

    .table-section-title {
        color: #333;
        font-weight: 700;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.2rem;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .table-section-title i {
        color: #ed1c24;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .products-table {
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    .products-table thead {
        background: linear-gradient(135deg, #ed1c24 0%, #c91a20 100%);
        color: #ffffff;
    }

    .products-table thead th {
        padding: 15px 20px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        border: none;
        white-space: nowrap;
    }

    .products-table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f0f0f0;
    }

    .products-table tbody tr:hover {
        background-color: #f8f9fa;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .products-table tbody td {
        padding: 18px 20px;
        vertical-align: middle;
        color: #555;
        border: none;
    }

    .products-table tbody td:first-child {
        color: #ed1c24;
        font-weight: 600;
    }

    .products-table tbody td:nth-child(2) strong {
        color: #ed1c24;
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .order-view-wrapper {
            padding: 15px;
        }

        .view-header {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .view-header h4 {
            font-size: 1rem;
            flex-shrink: 1;
        }

        .btn-back {
            padding: 8px 16px;
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        .order-info-card {
            padding: 15px;
        }

        .detail-row {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .products-table thead th,
        .products-table tbody td {
            padding: 12px 10px;
            font-size: 0.85rem;
        }
    }
</style>
@endpush
