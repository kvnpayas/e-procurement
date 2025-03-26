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

    .table-details tbody tr:last-child td:nth-child(1) {
      font-weight: bolder !important;
      background-color: #D9D9D9 !important;
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
  @include('reports.newPdf.envelope-header-report', ['envelope' => 'eligibility'])

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
        </tr>
        <tr>
          <td>Vendor Name</td>
          <td colspan="3">{{ $vendor['name'] }}</td>
          <td>Email</td>
          <td colspan="3">{{ $vendor['email'] }}</td>
        </tr>
        <tr>
          <td>Address</td>
          <td colspan="3">{{ $vendor['address'] }}</td>
          <td>Contact Number</td>
          <td colspan="3">{{ $vendor['number'] }}</td>
        </tr>
        <tr>
          <td>Status</td>
          <td colspan="3">
            @if ($vendor['result'])
              <span style="color: green">PASSED</span>
            @else
              <span style="color: red">FAILED</span>
            @endif
          </td>
          <td></td>
          <td colspan="3"></td>
        </tr>
        <tr>
          <td colspan="8">Eligibility Envelope</td>
        </tr>
        <tr>
          <td colspan="2">Name</td>
          <td colspan="2">Description</td>
          <td colspan="2">Attachment(s)</td>
          <td colspan="2">Status</td>
        </tr>
      </thead>
      <tbody>
        @foreach ($vendor['data'] as $eligibility)
          <tr>
            <td colspan="2">
              {{ $eligibility['name'] }}
            </td>
            <td colspan="2">{{ $eligibility['description'] }}</td>
            <td colspan="2">
              @foreach ($eligibility['files'] as $file)
                <span style="display: block">{{ $file }}</span>
              @endforeach
            </td>
            <td colspan="2">
              @if ($eligibility['result'])
                <span style="color: green">PASSED</span>
              @else
                <span style="color: red">FAILED</span>
              @endif
            </td>
          </tr>
        @endforeach
        <tr>
          <td>Remarks</td>
          <td colspan="7">{{ $vendor['vendor_remarks'] }}</td>
        </tr>
      </tbody>
    </table>
  @endforeach


  {{-- <table class="table-details">
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
  </div> --}}
</body>

</html>
