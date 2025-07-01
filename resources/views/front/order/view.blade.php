@extends('front.partials.layout')

@section('content')
    <div class="front container py-2">
        @include('front.partials.sidebar')

        <div class="front main-content p-4">
            <div class="row">
                <div class="col text-end">
                    <a href="{{ route('order.list') }}"><button class="btn btn-sm btn-secondary text-white">Back</button></a>
                </div>
            </div>
            <table class="table">
                <tr>
                    <td>Order Number</td>
                    <td>{{ $order->order_no }}</td>
                </tr>
                <tr>
                    <td>Order Number</td>
                    <td>{{ $order->brand->title }}</td>
                </tr>
                <tr>
                    <td>Order Date</td>
                    <td>{{ date('d-m-Y', strtotime($order->created_at)) }}</td>
                </tr>
                <tr>
                    <td>Order Time</td>
                    <td>{{ date('h:i', strtotime($order->created_at)) }}</td>
                </tr>
            </table>
            <table class="table mt-5">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Product Code</th>
                        <th scope="col">Product Title</th>
                        <th scope="col">Product Qty.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->products as $key => $product)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $product->product->product_code }}</td>
                            <td>{{ $product->product->title }}</td>
                            <td>{{ $product->qty }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Pagination Links -->
            {{-- <div class="d-flex justify-content-between align-items-center my-2">
                <div>
                    Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} records
                </div>

                <div>
                    {!! $orders->links('pagination::bootstrap-4') !!}
                </div>
            </div> --}}

        </div>
    </div>
@endsection
