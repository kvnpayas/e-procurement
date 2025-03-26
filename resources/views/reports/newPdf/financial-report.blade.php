<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <style>
    body {
      font-size: 12px;
    }

    .table-details-header {
      margin-top: 30px;
      width: 100%;
    }

    .table-details-header thead tr td {
      font-weight: bolder;
      font-size: 18px;
      background-color: #D9D9D9;
      padding: 5px;
      border: 1px solid #000;
    }

    .table-details {
      margin-top: 30px;
      border-collapse: collapse;
      width: 100%;
    }

    .table-details thead td {
      border: 1px solid #000;
      padding: 5px;
    }

    .table-details thead tr td:nth-child(1),
    .table-details thead tr td:nth-child(3) {
      font-weight: bolder;
      background-color: #D9D9D9;
    }

    .table-details thead tr:last-child td {
      font-weight: bolder !important;
      background-color: #ffff !important;
    }

    .table-details tbody tr td {
      padding: 5px;
      border: 1px solid #000;
    }

    .table-details thead tr:nth-child(1) td {
      border: 0px solid #fff !important;
      padding: 0px !important;
      opacity: 0%;
    }

    .table-details tbody tr:nth-last-child(1) td:nth-child(1),
    .table-details tbody tr:nth-last-child(2) td:nth-child(1) {
      font-weight: bolder;
      background-color: #D9D9D9;
    }

    .empty-records td {
      text-align: center;
      font-weight: bold;
      font-size: 14px;
      background-color: rgba(0, 0, 0, .075);
    }

    .page-break {
      page-break-before: always;
    }
  </style>
</head>

<body>
  @include('reports.newPdf.envelope-header-report', ['envelope' => 'financial'])

  <table class="table-details-header">
    <thead class="">
      <tr>
        <td colspan="4">Vendor Details</td>
      </tr>
    </thead>
  </table>

  @foreach ($data as $vendor)
    <table class="table-details">
      <thead>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="2">Vendor Name</td>
          <td colspan="5">{{ $vendor['name'] }}</td>
          <td colspan="2">Email</td>
          <td colspan="5">{{ $vendor['email'] }}</td>
        </tr>
        <tr>
          <td colspan="2">Address</td>
          <td colspan="5">{{ $vendor['address'] }}</td>
          <td colspan="2">Contact Number</td>
          <td colspan="5">{{ $vendor['number'] }}</td>
        </tr>
        <tr>
          <td colspan="2">Total Amount</td>
          <td colspan="5">PHP {{ number_format($vendor['grand_total'], 2) }}</td>
          <td colspan="2">Status</td>
          <td colspan="5">
            @if ($vendor['result'])
              <span style="color: green">PASSED</span>
            @else
              <span style="color: red">FAILED</span>
            @endif
          </td>
        </tr>
        @if ($bid->score_method == 'Rating')
          <tr>
            <td colspan="2">
              Score
            </td>
            <td colspan="5">
              {{ number_format($vendor['vendor_rating_score'], 2) }}%</td>
            <td colspan="2"></td>
            <td colspan="5"></td>
          </tr>
        @endif
        <tr>
          <td colspan="14">Financial Envelope</td>
        </tr>
        <tr>
          <td colspan="2">Inventory ID</td>
          <td colspan="2">Description</td>
          <td colspan="1">UOM</td>
          <td colspan="1">Quantity</td>
          <td colspan="2">Reserved Price</td>
          <td colspan="2">Vendor Price</td>
          <td colspan="2">Tax/Duties/Fees/Levies</td>
          <td colspan="2">Amount</td>
        </tr>
      </thead>
      <tbody>
        @foreach ($vendor['data'] as $response)
          <tr>
            <td colspan="2">{{ $response['inventory_id'] }}</td>
            <td colspan="2">{{ $response['description'] }}</td>
            <td colspan="1">{{ $response['uom'] }}</td>
            <td colspan="1">{{ $response['quantity'] }}</td>
            <td colspan="2">PHP {{ number_format($response['reserved_price'], 2) }}</td>
            <td colspan="2">
              @if ($response['admin_price'])
                <span style="color: #E76727">PHP {{ number_format($response['price'], 2) }} - admin</span>
              @else
                <span>PHP {{ number_format($response['price'], 2) }}</span>
              @endif
            </td>
            <td colspan="2">
              @if ($response['admin_fees'])
                <span style="color: #E76727">PHP {{ number_format($response['other_fees'], 2) }} - admin</span>
              @else
                <span>PHP {{ number_format($response['other_fees'], 2) }}</span>
              @endif
            </td>
            <td colspan="2">
              PHP {{ number_format($response['amount'], 2) }}
            </td>
          </tr>
        @endforeach
        <tr>
          <td colspan="2">
            Attachments:
          </td>
          <td colspan="12">
            @foreach ($vendor['files'] as $file)
              <span style="display: block">{{ $file }}</span>
            @endforeach
          </td>
        </tr>
        <tr>
          <td colspan="2">Remarks</td>
          <td colspan="12">{{ $vendor['vendor_remarks'] }}</td>
        </tr>
      </tbody>
    </table>
  @endforeach
</body>

</html>
