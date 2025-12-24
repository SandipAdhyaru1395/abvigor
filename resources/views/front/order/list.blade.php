@extends('front.partials.layout')

@section('content')
    <div class="front container my-5">
        @include('front.partials.sidebar')
        <div class="front main-content p-4">
            <div class="orders-table-wrapper">
                <div class="table-header mb-4">
                    <h4 class="mb-0"><i class="fa fa-list"></i> Orders List</h4>
                    <a href="{{ route('order.add') }}">
                        <button class="btn btn-place-order">
                            <i class="fa fa-plus"></i> Place Order
                        </button>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table orders-table">
                        <thead>
                            <tr>
                                <th scope="col">Order Number</th>
                                <th scope="col">Tractor Brand</th>
                                <th scope="col">Order Date</th>
                                <th scope="col">Order Time</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td><strong>{{ $order->order_no }}</strong></td>
                                    <td>{{ $order->brand->title }}</td>
                                    <td>{{ date('d/m/Y', strtotime($order->created_at)) }}</td>
                                    <td>{{ date('h:i A', strtotime($order->created_at)) }}</td>
                                    <td>
                                        <a href="{{ route('order.get', $order->id) }}">
                                            <button class="btn btn-view">
                                                <i class="fa fa-eye"></i> View
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center no-records">
                                        <i class="fa fa-inbox"></i>
                                        <p>No Records Found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination Links -->
            @if($orders->isNotEmpty())
                <div class="pagination-wrapper d-flex justify-content-between align-items-center mt-4">
                    <div class="pagination-info">
                        <i class="fa fa-info-circle"></i>
                        Showing <strong>{{ $orders->firstItem() }}</strong> to <strong>{{ $orders->lastItem() }}</strong> of <strong>{{ $orders->total() }}</strong> records
                    </div>
                    <div class="pagination-links">
                        {!! $orders->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
<style>
    .orders-table-wrapper {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        padding: 24px 24px 20px;
        border-top: 4px solid #ed1c24;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .table-header h4 {
        color: #333;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table-header h4 i {
        color: #ed1c24;
    }

    .btn-place-order {
        background: linear-gradient(135deg, #ed1c24 0%, #c91a20 100%);
        color: #ffffff;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(237, 28, 36, 0.3);
    }

    .btn-place-order:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(237, 28, 36, 0.4);
        color: #ffffff;
    }

    .orders-table {
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    .orders-table thead {
        background: linear-gradient(135deg, #ed1c24 0%, #c91a20 100%);
        color: #ffffff;
    }

    .orders-table thead th {
        padding: 15px 20px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        border: none;
        white-space: nowrap;
    }


    .orders-table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f0f0f0;
    }

    .orders-table tbody tr:hover {
        background-color: #f8f9fa;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .orders-table tbody td {
        padding: 18px 20px;
        vertical-align: middle;
        color: #555;
        border: none;
    }

    .orders-table tbody td:first-child {
        color: #ed1c24;
        font-weight: 600;
    }

    .btn-view {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: #ffffff;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(40, 167, 69, 0.4);
        color: #ffffff;
    }

    .btn-view i {
        font-size: 0.9rem;
    }

    .no-records {
        padding: 60px 20px !important;
        color: #999;
    }

    .no-records i {
        font-size: 3rem;
        display: block;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    .no-records p {
        margin: 0;
        font-size: 1.1rem;
    }

    .pagination-wrapper {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
    }

    .pagination-info {
        color: #666;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pagination-info i {
        color: #ed1c24;
    }

    .pagination-info strong {
        color: #333;
        font-weight: 700;
    }

    .pagination-links .pagination {
        margin: 0;
    }

    .pagination-links .page-link {
        color: #ed1c24;
        border-color: #dee2e6;
        padding: 8px 14px;
        transition: all 0.3s ease;
    }

    .pagination-links .page-link:hover {
        background-color: #ed1c24;
        color: #ffffff;
        border-color: #ed1c24;
    }

    .pagination-links .page-item.active .page-link {
        background-color: #ed1c24;
        border-color: #ed1c24;
        box-shadow: 0 2px 8px rgba(237, 28, 36, 0.3);
    }

    @media (max-width: 768px) {
        .table-header {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .table-header h4 {
            font-size: 1rem;
            flex-shrink: 1;
        }

        .btn-place-order {
            padding: 8px 16px;
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        .orders-table-wrapper {
            padding: 15px;
        }

        .orders-table thead th,
        .orders-table tbody td {
            padding: 12px 10px;
            font-size: 0.85rem;
        }

        .pagination-wrapper {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
    }
</style>
@endpush
