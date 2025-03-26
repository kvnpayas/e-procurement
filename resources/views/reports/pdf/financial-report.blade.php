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

    .header-table,
    .table-module,
    td,
    .table-details {
      width: 100%;
    }

    .header-table thead tr td:nth-child(2) {
      text-align: center;
      font-weight: bolder;
    }

    .header-table thead tr td:nth-child(3),
    .header-table thead tr:nth-child(2) td:nth-child(2) {
      text-align: right;
      font-weight: normal;
    }


    .logo {
      /* display: inline-block; */
      font-size: 24px;
      color: #E76727;
    }

    .table-module {
      margin-top: 15px;
    }

    .table-module thead th {
      font-weight: bolder;
      font-size: 18px;
    }

    .table-module tbody th {
      font-weight: bolder;
      font-size: 14px;
      color: #E76727;
    }

    .table-details {
      margin-top: 20px;
    }

    .table-details,
    .table-details td {
      margin-top: 20px;
      border-collapse: collapse;
    }

    .table-details td {
      white-space: nowrap;
      padding-left: 10px;
    }

    .table-details thead tr {
      border-bottom: 1pt solid black;
    }

    .table-details thead tr td {
      font-size: 16px;
    }

    .table-details thead td {
      font-weight: bolder;

    }

    .table-details tbody .vendor td {
      font-weight: bolder;

    }

    .table-details tbody .vendor td {
      padding: 15px;
      color: #0f3d5c;

    }

    .table-details tbody .technical {
      border-bottom: 1pt solid black;

    }

    .table-details tbody .technical thead tr td {
      font-size: 12px;

    }

    .empty-records td {
      text-align: center;
      font-weight: bold;
      font-size: 14px;
      background-color: rgba(0, 0, 0, .075);
    }
  </style>
</head>

<body>

  <table class="header-table">
    <thead>
      <tr class="table-logo">
        <td>Date: {{ date('Y-m-d', strtotime(now())) }}</td>
        <td rowspan="2" style="">
          <div class="logo">
            <span>Tarlac Electric Inc.</span>
            {{-- <img src="{{ public_path('img/tei-logo-no-name.png') }}" alt="" height="40"> --}}
          </div>
        </td>
        <td>Company: TEI</td>
      </tr>
      <tr>
        <td>Time: {{ date('h:i A', strtotime(now())) }}</td>
      </tr>
      <tr>
        <td>User: {{ Auth::user()->name }}</td>
        <td><span>Mabini St. Tarlac, Philippines</span></td>
        <td></td>
      </tr>
    </thead>
  </table>

  <hr>

  <table class="table-module">
    <thead>
      <tr>
        <th>{{ $bid->title }}</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th>Financial Envelope</th>
      </tr>
    </tbody>
  </table>

  <table class="table-details">
    <thead>
      <tr>
        <td>Vendor Name</td>
        <td>Email</td>
        <td>Address</td>
        <td>Number</td>
        <td>Total Amount</td>
        @if ($bid->score_method == 'Rating')
          <td>Score</td>
        @endif
        <td>Status</td>
      </tr>
    </thead>
    <tbody>
      @forelse ($data as $vendor)
        <tr class="vendor">
          <td>{{ $vendor['name'] }}</td>
          <td>{{ $vendor['email'] }}</td>
          <td>{{ $vendor['address'] }}</td>
          <td>{{ $vendor['number'] }}</td>
          <td>PHP {{ number_format($vendor['grand_total'], 2) }}</td>
          @if ($bid->score_method == 'Rating')
            <td>
              {{ number_format($vendor['vendor_rating_score'], 2) }}%
            </td>
          @endif
          <td>
            @if ($vendor['result'])
              <span style="color: green">PASSED</span>
            @else
              <span style="color: red">FAILED</span>
            @endif
          </td>
        </tr>

        <tr class="technical">
          <td colspan="10">
            <table style="width: 100%">
              <thead>
                <tr>
                  <td>Inventory Id</td>
                  <td>Description</td>
                  <td>UOM</td>
                  <td>Quantity</td>
                  <td>Reserved Price</td>
                  <td>Vendor Price</td>
                  <td>Tax/Duties/Fees/Levies</td>
                  <td>Amount</td>
                </tr>
              </thead>
              <tbody>
                @foreach ($vendor['data'] as $response)
                  <tr>
                    <td>{{ $response['inventory_id'] }}</td>
                    <td style="white-space: normal; !important;">{{ $response['description'] }}</td>
                    <td>{{ $response['uom'] }}</td>
                    <td>{{ $response['quantity'] }}</td>
                    <td>PHP {{ number_format($response['reserved_price'], 2) }}</td>
                    <td>
                      @if ($response['admin_price'])
                        <span style="color: #E76727">PHP {{ number_format($response['price'], 2) }} - admin</span>
                      @else
                        <span>PHP {{ number_format($response['price'], 2) }}</span>
                      @endif
                    </td>
                    <td>
                      @if ($response['admin_fees'])
                        <span style="color: #E76727">PHP {{ number_format($response['other_fees'], 2) }} - admin</span>
                      @else
                        <span>PHP {{ number_format($response['other_fees'], 2) }}</span>
                      @endif
                    </td>
                    <td>
                      PHP {{ number_format($response['amount'], 2) }}
                    </td>
                  </tr>
                @endforeach
                <tr>
                  <td colspan="100"></td>
                </tr>
                <tr>
                  <td style="color: #E76727; font-style: bold;">
                    Attachments:
                  </td>
                  <td style="color: #E76727; font-style: bold;">
                    @foreach ($vendor['files'] as $file)
                      <span style="display: block">{{ $file }}</span>
                    @endforeach
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>

      @empty
        <tr class="empty-records">
          <td colspan='100'>No Records to show</td>
        </tr>
      @endforelse
    </tbody>
  </table>
  <div style="margin-top: 20px; font-style: italic;">
    @php
      $remarks = $bid->envelopeRemarks->where('envelope', 'financial')->first();
    @endphp
    <label for="">Remarks:</label>
    <p>{{ $remarks ? $remarks->remarks : null }}</p>
  </div>
</body>

</html>
