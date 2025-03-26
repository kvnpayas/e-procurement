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
      font-weight: normal;
      font-size: 12px;
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

    .table-details tbody .eligibilities {
      border-bottom: 1pt solid black;

    }
    .table-details tbody .eligibilities thead tr td {
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
        <th>Eligibility Envelopes</th>
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
          <td>
            @if ($vendor['result'])
              <span style="color: green">PASSED</span>
            @else
              <span style="color: red">FAILED</span>
            @endif
          </td>
        </tr>
        {{-- <tr>
          <td colspan="5" style="font-weight: bolder; font-size: 14px">Elgibilities</td>
        </tr> --}}

        <tr class="eligibilities">
          <td colspan="5">
            <table style="width: 100%">
              <thead>
                <tr>
                  <td>Eligibility Name</td>
                  <td>Eligibility Description</td>
                  <td>Attachments</td>
                  <td>Status</td>
                </tr>
              </thead>
              <tbody>
                @foreach ($vendor['data'] as $eligibility)
                  <tr>
                    <td>
                      {{ $eligibility['name'] }}
                    </td>
                    <td>{{ $eligibility['description'] }}</td>
                    <td>
                      @foreach ($eligibility['files'] as $file)
                          <span style="display: block">{{ $file }}</span>
                      @endforeach
                    </td>
                    <td>
                      @if ($eligibility['result'])
                        <span style="color: green">PASSED</span>
                      @else
                        <span style="color: red">FAILED</span>
                      @endif
                    </td>
                  </tr>
                @endforeach
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
      $remarks = $bid->envelopeRemarks->where('envelope', 'eligibility')->first()
    @endphp
    <label for="">Remarks:</label>
    <p>{{$remarks ? $remarks->remarks : null}}</p>
  </div>
</body>

</html>
