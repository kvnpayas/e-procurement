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
    .table-info,
    .vendor-response,
    td {
      width: 100%;
    }

    .header-table thead tr td:nth-child(2) {
      font-weight: bolder;
      text-align: center;
    }

    .header-table thead tr td:nth-child(3),
    .header-table thead tr:nth-child(2) td:nth-child(2) {
      text-align: right;
      font-weight: normal;
    }


    .logo {
      /* display: inline-block; */
      font-size: 24px;
      /* color: #E76727; */
    }

    .table-info {
      margin-top: 30px;
      border-collapse: collapse;
    }

    .table-info thead td,
    .table-info tbody td,
    {
    border: 1px solid #000;
    padding: 5px;
    }

    .table-info thead:nth-child(1) tr td {
      font-weight: bolder;
      font-size: 18px;
      background-color: #D9D9D9;
    }

    .table-info thead tr td:nth-child(1),
    .table-info thead tr td:nth-child(3) {
      font-weight: bolder;
      background-color: #D9D9D9;
      width: 35%;
    }

    .vendor-response {
      border-collapse: collapse;
    }

    .vendor-response thead tr td {
      background-color: #D9D9D9;
    }

    .vendor-response thead td,
    .vendor-response tbody td {
      border: 1px solid #000;
      padding: 5px;
    }

    .vendor-response thead tr:nth-child(1) td {
      font-weight: bolder;
    }

    .table-award {
      width: 100%;
      margin-top: 30px;
      border-collapse: collapse;
    }

    .table-award thead:nth-child(1) tr td {
      font-weight: bolder;
      font-size: 18px;
      background-color: #D9D9D9;
    }

    .table-award thead tr td:nth-child(1),
    .table-award thead tr td:nth-child(3) {
      font-weight: bolder;
      background-color: #D9D9D9;
      width: 35%;
    }

    .table-award thead td {
      border: 1px solid #000;
      padding: 5px;
    }

    /* .table-info-body tr td:nth-child(1),
    .table-info-body tr td:nth-child(3) {
      font-weight: bolder;
      background-color: #D9D9D9;
      width: 35%;
    } */

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

    .table-details tbody td {
      border: 1px solid #000;
      padding: 5px;
    }

    .table-details tbody tr td:nth-child(1),
    .table-details tbody tr td:nth-child(3) {
      font-weight: bolder;
      background-color: #D9D9D9;
    }

    /* .table-details thead tr:last-child td {
      font-weight: bolder !important;
      background-color: #ffff !important;
    } */
    .table-details tbody tr:nth-child(1) td {
      border: 0px solid #fff !important;
      padding: 0px !important;
      opacity: 0%;
    }

    .table-details tbody .evnvelope-header td {
      font-weight: bolder !important;
      background-color: #fff !important;
    }

    .table-details tbody .evnvelope-details td {
      font-weight: normal !important;
      background-color: #fff !important;
    }

    /* .table-details tbody tr td {
      padding: 5px;
      border: 1px solid #000;
    }


    .table-details tbody tr:last-child td:nth-child(1) {
      font-weight: bolder;
      background-color: #D9D9D9;
    } */

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

@php
  $allEnvelopes = [
      'eligibility' => (bool) $bid->eligibility,
      'technical' => (bool) $bid->technical,
      'financial' => (bool) $bid->financial,
  ];

  $envelopes = array_filter($allEnvelopes, function ($value) {
      return $value === true;
  });

  $activeEnvelopes = array_keys(array_filter($envelopes));
  // $keys = array_keys($activeEnvelopes, $envelope)[0] - 1;
  // $method = $keys < 0 ? 'envelope_open_date' : $activeEnvelopes[$keys] . '_submit_date';

  $vendors = $bid->vendors;
  $joinedVendors = $vendors ? $vendors->whereNotIn('pivot.status', ['No Response', 'Declined', 'Awarded'])->count() : 0;
  $declinedVendors = $vendors ? $vendors->where('pivot.status', 'Declined')->count() : 0;
  $noResponseVendors = $vendors ? $vendors->where('pivot.status', 'No Response')->count() : 0;
  $declinedNoResponse = $vendors ? $vendors->where('pivot.status', 'Awarded')->count() : 0;

  $vendorLists = $vendors->map(function ($vendor) use ($bid) {
      $vendorStatus = $vendor->vendorStatus->where('bidding_id', $bid->id)->where('vendor_id', $vendor->id)->first();
      $vendor->submissionDate = $vendorStatus ? $vendorStatus->submission_date : null;
      return $vendor;
  });
  $vendorLists = $vendorLists
      ->sortBy(function ($vendor) {
          // If submission_date is null, return a very large value (e.g., PHP_INT_MAX)
          return $vendor->submissionDate ? $vendor->submissionDate : PHP_INT_MAX;
      })
      ->values();
@endphp

<body>
  <table class="header-table">
    <thead>
      <tr class="table-logo">
        <td>Date: {{ date('Y-m-d', strtotime(now())) }}</td>
        <td rowspan="2" style="">
          <div class="logo" style="">
            {{-- <img src="{{ public_path('img/tei-logo-no-name.png') }}" alt="" height="40"> --}}
            <span>Tarlac Electric Inc.</span>
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

  <table class="table-info">
    <thead class="">
      <tr>
        <td colspan="4">Project Information</td>
      </tr>
    </thead>
    <thead>
      <tr>
        <td>Project No.</td>
        <td>{{ $bid->project_id }}</td>
        <td>Scrap</td>
        <td>{{ $bid->scrap ? 'Yes' : 'No' }}</td>
      </tr>
      <tr>
        <td>Project Title</td>
        <td>{{ $bid->title }}</td>
        <td>Score Method</td>
        <td>{{ strtoupper($bid->score_method) }}</td>
      </tr>
      <tr>
        <td>Project Type</td>
        <td>{{ ucfirst($bid->type) }}</td>
        <td>Reserved Price</td>
        <td>
          {{ $bid->reserved_price_switch ? 'PHP ' . number_format($bid->reserved_price, 2) : 'No Maximum Limit' }}
        </td>
      </tr>
      <tr>
        <td>Project Status</td>
        <td>{{ ucwords($bid->status) }}</td>
        <td>Envelope(s)</td>
        <td>
          {{ ucwords(implode(', ', $activeEnvelopes)) }}
        </td>
      </tr>
    </thead>
  </table>

  <table class="table-info">
    <thead class="">
      <tr>
        <td colspan="4">Timing Information</td>
      </tr>
    </thead>
    <thead>
      <tr>
        <td>Name of the Buyer</td>
        <td>{{ ucwords($bid->created_user->name) }}</td>
        <td>Publication Date</td>
        <td>{{ date('F j, Y h:i A', strtotime($bid->start_date)) }}</td>
      </tr>
      <tr>
        <td>Bid Opened Date</td>
        <td>
          {{ date('F j, Y h:i A', strtotime($bid->progress->envelope_open_date)) }}
        </td>
        <td>Submission Due Date</td>
        <td>
          {{ $bid->extend_date ? date('F j, Y h:i A', strtotime($bid->extend_date)) : date('F j, Y h:i A', strtotime($bid->deadline_date)) }}
        </td>
      </tr>
    </thead>
  </table>

  <table class="table-info">
    <thead class="">
      <tr>
        <td colspan="4">Vendor Response Summary</td>
      </tr>
    </thead>
    <thead>
      <tr>
        <td>No. of Invited Vendor(s)</td>
        <td>{{ $vendors ? $vendors->count() : 0 }}</td>
        <td>
          @if ($bid->status == 'Awarded')
            Declined/No Response
          @else
            Declined Vendor(s)
          @endif
        </td>
        <td>
          @if ($bid->status == 'Awarded')
            {{ $declinedNoResponse }}
          @else
            {{ $declinedVendors }}
          @endif
        </td>
      </tr>
      <tr>
        <td>Joined Vendor(s)</td>
        <td>{{ $joinedVendors }}</td>
        <td>
          @if ($bid->status != 'Awarded')
            No Response
          @endif
        </td>
        <td>
          @if ($bid->status != 'Awarded')
            {{ $noResponseVendors }}
          @endif
        </td>
      </tr>
    </thead>
  </table>
  <table class="vendor-response">
    <thead>
      <tr>
        <td>Vendor Name</td>
        <td>Address</td>
        <td>Response Date</td>
      </tr>
    </thead>
    <tbody>
      @foreach ($vendorLists as $vendor)
        <tr>
          <td>{{ $vendor->name }}</td>
          <td>{{ $vendor->address }}</td>
          <td>
            {{ $vendor->submissionDate ? date('F j,Y h:i A', strtotime($vendor->submissionDate)) : 'No Response' }}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  @if ($bid->status != 'Under Evaluation')
    <table class="table-award page-break">
      <thead class="">
        <tr>
          <td colspan="4">Bid Summary</td>
        </tr>
      </thead>
      <thead>
        <tr>
          <td rowspan="{{ $bid->winnerApproval && $bid->winnerApproval->finalApproverUser ? '2' : '1' }}">
            Winning Bidder</td>
          <td rowspan="{{ $bid->winnerApproval && $bid->winnerApproval->finalApproverUser ? '2' : '1' }}">
            {{ ucwords($bid->winnerApproval->winnerVendor->name) }}</td>

          <td>
            @if ($bid->winnerApproval && $bid->winnerApproval->approverUser)
              Approved By
            @endif
          </td>
          <td>
            @if ($bid->winnerApproval && $bid->winnerApproval->approverUser)
              {{ ucwords($bid->winnerApproval->approverUser->name) }}
            @endif
          </td>
        </tr>
        @if ($bid->winnerApproval && $bid->winnerApproval->finalApproverUser)
          <tr>
            <td>Final Approval By</td>
            <td>
              {{ ucwords($bid->winnerApproval->finalApproverUser->name) }}
            </td>
          </tr>
        @endif
        @if ($bid->status == 'Awarded')
          <tr>
            <td>Awarded By</td>
            <td>
              {{ $bid->bidAward->awardedBy ? ucwords($bid->bidAward->awardedBy->name) : '' }}
            </td>
            <td>Awarded Date</td>
            <td>
              {{ date('F j,Y h:i A', strtotime($bid->bidAward->award_date)) }}
            </td>
          </tr>
        @endif
      </thead>
    </table>
  @endif

  @foreach ($data as $index => $vendor)

    <table
      class="table-details {{ $bid->status == 'Under Evaluation' ? 'page-break' : ($index != 0 ? 'page-break' : '') }}">
      <tbody>
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
          <td colspan="2">Rank</td>
          <td colspan="5">{{ $vendor['rank'] }}</td>
          <td colspan="2">Status</td>
          <td colspan="5">
            @if ($vendor['result'])
              <span style="color: green">PASSED</span>
            @else
              <span style="color: red">FAILED</span>
            @endif
          </td>
        </tr>
        <tr>
          <td colspan="2">
            @if ($bid->score_method == 'Rating')
              Score
            @endif
          </td>
          <td colspan="5">
            @if ($bid->score_method == 'Rating')
              {{ number_format($vendor['score'], 2) }}%
            @endif
          </td>
          <td colspan="2">
            @if ($bid->financial)
              Total Amount
            @endif
          </td>
          <td colspan="5">
            @if ($bid->financial && $vendor['financial'])
              PHP {{ number_format($vendor['financial']['grand_total'], 2) }}
            @endif
          </td>
        </tr>

        @if (isset($vendor['eligibility']))
          <tr>
            <td colspan="14">
              Eligibility Envelope
            </td>
          </tr>
          @if ($vendor['eligibility'])
            <tr class="evnvelope-header">
              <td colspan="4">Name</td>
              <td colspan="4">Description</td>
              <td colspan="4">Attachment(s)</td>
              <td colspan="2">Result</td>
            </tr>
            @foreach ($vendor['eligibility']['data'] as $eligibility)
              <tr class="evnvelope-details">
                <td colspan="4">
                  {{ $eligibility['name'] }}
                </td>
                <td colspan="4">{{ $eligibility['description'] }}</td>
                <td colspan="4">
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
              <td colspan="2">Remarks</td>
              <td colspan="12">{{ $vendor['eligibility']['vendor_remarks'] }}</td>
            </tr>
          @else
            <tr class="evnvelope-details">
              <td colspan="14">
                The vendor failed to pass previous
                envelopes.
              </td>
            </tr>
          @endif
        @endif

        @if (isset($vendor['technical']))
          <tr>
            <td colspan="14">
              Technical Envelope
              @if ($bid->score_method == 'Rating' && $vendor['technical'])
                ({{ number_format($vendor['technical']['total_rating'], 2) }}%)
              @endif
            </td>
          </tr>
          @if ($vendor['technical'])
            <tr class="evnvelope-header">
              <td colspan="4">Question</td>
              <td colspan="3">Response</td>
              <td colspan="2">Score (Total: {{ number_format($vendor['technical']['vendor_total_rating'], 2) }}%)
              </td>
              <td colspan="3">Attachment(s)</td>
              <td colspan="2">Result</td>
            </tr>
            @foreach ($vendor['technical']['data'] as $technical)
              <tr class="evnvelope-details">
                <td colspan="4">{{ $technical['question'] }}</td>
                {{-- <td>{{ $technical['passing'] }}</td> --}}
                <td colspan="3">
                  @if ($technical['admin_answer'])
                    <span style="color: #E76727">{{ $technical['admin_answer'] }} - admin</span>
                  @else
                    <span>{{ $technical['answer'] ? $technical['answer'] : 'Null' }}</span>
                  @endif
                </td>
                <td colspan="2">{{ number_format($technical['rating_score'], 2) }}%</td>
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
              <td colspan="12">{{ $vendor['technical']['vendor_remarks'] }}</td>
            </tr>
          @else
            <tr class="evnvelope-details">
              <td colspan="14">
                The vendor failed to pass previous
                envelopes.
              </td>
            </tr>
          @endif
        @endif

        @if (isset($vendor['financial']))
          <tr>
            <td colspan="14">
              Financial Envelope
              @if ($bid->score_method == 'Rating' && $vendor['financial'])
                ({{ number_format($vendor['financial']['total_rating_score'], 2) }}%)
              @endif
            </td>
          </tr>
          @if ($vendor['financial'])
            <tr class="evnvelope-header">
              <td colspan="2">Inventory ID</td>
              <td colspan="2">Description</td>
              <td colspan="1">UOM</td>
              <td colspan="1">Quantity</td>
              <td colspan="2">Reserved Price</td>
              <td colspan="2">Vendor Price</td>
              <td colspan="2">Tax/Duties/Fees/Levies</td>
              <td colspan="2">Amount</td>
            </tr>
            @foreach ($vendor['financial']['data'] as $response)
              <tr class="evnvelope-details">
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
            <tr class="evnvelope-details">
              <td colspan="10"></td>
              <td colspan="2" align="right">
                Total:<br>
                @if ($bid->score_method == 'Rating' && $vendor['financial'])
                  Total Score:
                @endif
              </td>
              <td colspan="2">
                PHP {{ number_format($vendor['financial']['grand_total'], 2) }}<br>
                @if ($bid->score_method == 'Rating' && $vendor['financial'])
                  ({{ number_format($vendor['financial']['vendor_rating_score'], 2) }}%)
                @endif
              </td>
            </tr>
            <tr>
              <td colspan="2">Remarks</td>
              <td colspan="12">{{ $vendor['financial']['vendor_remarks'] }}</td>
            </tr>
          @else
            <tr class="evnvelope-details">
              <td colspan="14">
                The vendor failed to pass previous
                envelopes.
              </td>
            </tr>
          @endif
        @endif
      </tbody>
    </table>
  @endforeach
</body>

</html>
