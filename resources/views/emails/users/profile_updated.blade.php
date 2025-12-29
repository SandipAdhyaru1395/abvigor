<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profile Updated</title>
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

    .success-message {
      background-color: #d4edda;
      border: 1px solid #c3e6cb;
      color: #155724;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 20px;
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
    <h2>Profile Updated Successfully</h2>
    
    <div class="success-message">
      <strong>✓ Your profile has been updated successfully!</strong>
      <p style="margin: 5px 0 0 0;">Your updated profile details are shown below.</p>
    </div>

    <h3>Dealership Information</h3>
    <table>
      <tr>
        <td class="label">Dealership Name:</td>
        <td>{{ $user->dealership_name ?? 'N/A' }}</td>
      </tr>
      <tr>
        <td class="label">GST No:</td>
        <td>{{ $user->gst_number ?? 'N/A' }}</td>
      </tr>
    </table>

    <h3>Personal Details</h3>
    <table>
      <tr>
        <td class="label">Full Name:</td>
        <td>{{ $user->name }}</td>
      </tr>
      <tr>
        <td class="label">Email:</td>
        <td>{{ $user->email }}</td>
      </tr>
      <tr>
        <td class="label">Phone:</td>
        <td>{{ $user->phone ?? 'N/A' }}</td>
      </tr>
      @if($user->mobile)
      <tr>
        <td class="label">Alternate Mobile:</td>
        <td>{{ $user->mobile }}</td>
      </tr>
      @endif
    </table>

    <h3>Address Details</h3>
    <table>
      <tr>
        <td class="label">Address:</td>
        <td>{{ $user->address ?? 'N/A' }}</td>
      </tr>
      <tr>
        <td class="label">City:</td>
        <td>{{ $user->city ?? 'N/A' }}</td>
      </tr>
      <tr>
        <td class="label">Pin Code:</td>
        <td>{{ $user->zip ?? 'N/A' }}</td>
      </tr>
      <tr>
        <td class="label">State:</td>
        <td>{{ $user->state ?? 'N/A' }}</td>
      </tr>
    </table>

    <div class="footer">
      <p><strong>Updated on:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
      <p>If you did not make these changes, please contact us immediately at <strong>{{ env('ADMIN_EMAIL') }}</strong></p>
      <p>Thank you,</p>
      <p><strong>{{ config('mail.from.name', 'Prestige India') }} Team</strong></p>
    </div>
  </div>
</body>
</html>

