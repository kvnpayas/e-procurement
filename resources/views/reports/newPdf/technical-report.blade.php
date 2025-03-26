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

    .table-details thead tr:nth-child(1) td {
      border: 0px solid #fff !important;
      padding: 0px !important;
      opacity: 0%;
    }

    .table-details tbody tr td {
      padding: 5px;
      border: 1px solid #000;
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
  @include('reports.newPdf.envelope-header-report', ['envelope' => 'technical'])

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
        </tr>
        <tr>
          <td colspan="2">Vendor Name</td>
          <td colspan="4">{{ $vendor['name'] }}</td>
          <td colspan="2">Email</td>
          <td colspan="4">{{ $vendor['email'] }}</td>
        </tr>
        <tr>
          <td colspan="2">Address</td>
          <td colspan="4">{{ $vendor['address'] }}</td>
          <td colspan="2">Contact Number</td>
          <td colspan="4">{{ $vendor['number'] }}</td>
        </tr>
        <tr>
          <td colspan="2">Total Rating({{ $vendor['total_rating'] }}%)</td>
          <td colspan="4">{{ number_format($vendor['vendor_total_rating'], 2) }}%</td>
          <td colspan="2">Status</td>
          <td colspan="4">
            @if ($vendor['result'])
              <span style="color: green">PASSED</span>
            @else
              <span style="color: red">FAILED</span>
            @endif
          </td>
        </tr>
        <tr>
          <td colspan="12">Technical Envelope</td>
        </tr>
        <tr>
          <td colspan="3">Question</td>
          <td colspan="3">Response</td>
          <td colspan="1">Score %</td>
          <td colspan="3">Attachment(s)</td>
          <td colspan="2">Result</td>
        </tr>
      </thead>
      <tbody>
        @foreach ($vendor['data'] as $technical)
          <tr>
            <td colspan="3">{{ $technical['question'] }}</td>
            {{-- <td>{{ $technical['passing'] }}</td> --}}
            <td colspan="3">
              @if ($technical['admin_answer'])
                <span style="color: #E76727">{{ $technical['admin_answer'] }} - admin</span>
              @else
                <span>{{ $technical['answer'] ? $technical['answer'] : 'Null' }}</span>
              @endif
            </td>
            <td colspan="1">{{ number_format($technical['rating_score'], 2) }}%</td>
            <td colspan="3">
              @foreach ($technical['files'] as $file)
                <span style="display: block">{{ $file }}</span>
              @endforeach
            </td>
            <td colspan="2">
              @if ($technical['score'] == 'Fully Compliant')
                <span style="color: green">{{ $technical['score'] }}</span>
              @elseif($technical['score'] == 'Partially Compliant')
                <span style="color: #E76727">{{ $technical['score'] }}</span>
              @else
                <span style="color: red">{{ $technical['score'] }}</span>
              @endif
            </td>
          </tr>
        @endforeach
        <tr>
          <td colspan="2">Remarks</td>
          <td colspan="10">{{ $vendor['vendor_remarks'] }}</td>
        </tr>
      </tbody>
    </table>
  @endforeach
</body>

</html>
