@extends('front.partials.layout')

@section('content')
    <div class="front container py-2">
        @include('front.partials.sidebar')
        <div class="front main-content p-4 table-responsive">
            <div class="row mb-4">
                <div class="col text-end">
                    <a href="{{ route('order.add') }}"><button class="btn btn-sm bg-base text-white">Place Order</button></a>
                </div>
            </div>
            <table class="table">
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
                            <td>{{ $order->order_no }}</td>
                            <td>{{ $order->brand->title }}</td>
                            <td>{{ date('d/m/Y', strtotime($order->created_at)) }}</td>
                            <td>{{ date('h:i A', strtotime($order->created_at)) }}</td>
                            <td><a href="{{ route('order.get', $order->id) }}"><button class="btn bg-base btn-sm text-white">View</button></a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No Records Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination Links -->
            @if($orders->isNotEmpty())
                <div class="d-flex justify-content-between align-items-center my-2">
                    <div>
                        Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} records
                    </div>

                    <div>
                        {!! $orders->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
