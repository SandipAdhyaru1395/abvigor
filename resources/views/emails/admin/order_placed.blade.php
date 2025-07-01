<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Confirmation</title>
  <style>
    body {
      font-family: 'Segoe UI', 'Roboto', 'Helvetica Neue', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 20px;
      color: #333;
      font-size: 15px;
      line-height: 1.6;
    }

    .container {
      max-width: 720px;
      margin: 0 auto;
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
      padding: 35px 40px;
    }

    h2 {
      font-size: 22px;
      color: #ed1c24;
      border-bottom: 2px solid #e0e0e0;
      padding-bottom: 10px;
      margin-top: 0;
    }

    h3 {
      font-size: 18px;
      color: #333;
      margin-top: 30px;
      margin-bottom: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    td {
      padding: 10px 12px;
      vertical-align: top;
      font-size: 15px;
    }

    td.label {
      width: 30%;
      font-weight: 600;
      background-color: #f9f9f9;
      color: #444;
    }

    .product-table th, .product-table td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: left;
    }

    .product-table th {
      background-color: #f0f0f0;
      font-weight: 600;
    }

    .footer {
      margin-top: 40px;
      font-size: 14px;
      color: #666;
    }

    .footer strong {
      color: #222;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>New order has been placed - #{{ $order->order_no }}</h2>
    <table>
      <tr>
        <td class="label">Ordered At:</td>
        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i:s') }}</td>
      </tr>
    </table>

    <h3>Dealership Information</h3>
    <table>
      <tr>
        <td class="label">Dealership Name:</td>
        <td>{{ $user->dealership_name }}</td>
      </tr>
      <tr>
        <td class="label">GST No:</td>
        <td>{{ $user->gst_number }}</td>
      </tr>
    </table>

    <h3>Customer Details</h3>
    <table>
      <tr>
        <td class="label">Person Name:</td>
        <td>{{ $user->name }}</td>
      </tr>
      <tr>
        <td class="label">Address:</td>
        <td>{{ $user->address }}</td>
      </tr>
      <tr>
        <td class="label">City:</td>
        <td>{{ $user->city }}</td>
      </tr>
      <tr>
        <td class="label">Pin code:</td>
        <td>{{ $user->zip }}</td>
      </tr>
      <tr>
        <td class="label">State:</td>
        <td>{{ $user->state }}</td>
      </tr>
      <tr>
        <td class="label">Phone:</td>
        <td>{{ $user->phone }}</td>
      </tr>
      <tr>
        <td class="label">Email:</td>
        <td>{{ $user->email }}</td>
      </tr>
    </table>

    <h3>Tractor Brand</h3>
    <table>
      <tr>
        <td class="label">Brand:</td>
        <td>{{ $order->brand->title }}</td>
      </tr>
    </table>

    <h3>Product Details</h3>
    <table class="product-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Part Number</th>
          <th>Product Title</th>
          <th>Quantity</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($orderItems as $product)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product->product->product_code }}</td>
                <td>{{ $product->product->title }}</td>
                <td>{{ $product->qty }}</td>
            </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</body>
</html>
