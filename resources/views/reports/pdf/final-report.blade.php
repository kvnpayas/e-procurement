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

    /* --------------------------------------- */
    table.vendor-header {
      width: 75%;
      margin-top: 20px;
      margin-bottom: 20px;
    }

    table.vendor-header thead tr td {
      color: #56565A;
      font-size: 14px;
    }

    table.vendor-header thead tr td span {
      font-weight: bolder;
      color: black;
    }

    table.envelopes {
      width: 100%;
      margin-left: 25px;
      margin-bottom: 20px;
    }

    table.envelopes thead.envelope-title {
      font-size: 18px;
      font-weight: bolder;
      color: #0f3d5c;
    }

    table.envelopes .envelope-header td {
      font-size: 14px;
      font-weight: bolder;
      /* color: #0f3d5c; */
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
        <th>{{ $bid['title'] }}</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th>Envelopes</th>
      </tr>
    </tbody>
  </table>

  @forelse ($data as $vendor)
    <table class="vendor-header">
      <thead>
        <tr>
          <td><span>Vendor Name:</span> {{ $vendor['name'] }}</td>
          <td><span>Email:</span> {{ $vendor['email'] }}</td>
        </tr>
        <tr>
          <td><span>Rank:</span> {{ $vendor['rank'] }}</td>
          <td><span>Number:</span> {{ $vendor['number'] }}</td>
          {{-- <td>Number: {{ $vendor['number'] }}</td> --}}
        </tr>
        <tr>
          <td>
            <span>Final Result:</span>
            @if ($vendor['result'])
              <span style="color: green">PASSED</span>
            @else
              <span style="color: red">FAILED</span>
            @endif
          </td>
          <td><span>Address:</span> {{ $vendor['address'] }}</td>
          {{-- <td>Number: {{ $vendor['number'] }}</td> --}}
        </tr>
        @if ($bid['score_method'] == 'Rating')
          <tr>
            <td><span>Total Score:</span> {{ $vendor['score'] }}%</td>
          </tr>
        @endif
      </thead>
    </table>

    @if (isset($vendor['eligibility']))
      @if ($vendor['eligibility'])
        <table class="envelopes">
          <tbody class="envelope-title">
            <td colspan="3">Eligibility</td>
          </tbody>
          <tbody class="envelope-header">
            <td>Name</td>
            <td>Description</td>
            <td>Status</td>
          </tbody>
          <tbody>
            @foreach ($vendor['eligibility']['data'] as $eligibility)
              <tr>
                <td>{{ $eligibility['name'] }}</td>
                <td>{{ $eligibility['description'] }}</td>
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
      @else
        <table class="envelopes">
          <tbody class="envelope-title">
            <td colspan="3">Eligibility</td>
          </tbody>
          <tbody>
            <td colspan="3">The vendor failed to pass previous
              envelopes.</td>
          </tbody>
        </table>
      @endif
    @endif

    @if (isset($vendor['technical']))
      @if ($vendor['technical'])
        <table class="envelopes">
          <tbody class="envelope-title">
            <td colspan="4">Technical</td>
          </tbody>
          <tbody class="envelope-header">
            <tr>
              <td>Question</td>
              <td>Response</td>
              <td>Rating Score({{ $vendor['technical']['total_rating'] }}%)</td>
              <td>Results</td>
            </tr>
          </tbody>
          <tbody>
            @foreach ($vendor['technical']['data'] as $technical)
              <tr>
                <td style="white-space: normal; !important;">{{ $technical['question'] }}</td>
                <td>
                  {{-- @if ($technical['admin_answer'])
                    <span style="color: #E76727">{{ $technical['answer'] }} - admin</span>
                  @else
                    <span>{{ $technical['answer'] }}</span>
                  @endif --}}
                  <span style="white-space: normal; !important;">{{ $technical['answer'] }}</span>
                </td>
                <td>
                  {{ number_format($technical['rating_score'], 2) }}%
                </td>
                <td>
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
              <td colspan="2"></td>
              <td colspan="2">
                <span style="font-weight: bold">Total Rating:</span>
                {{ number_format($vendor['technical']['vendor_total_rating'], 2) }}%
              </td>
            </tr>
          </tbody>
        </table>
      @else
        <table class="envelopes">
          <tbody class="envelope-title">
            <td colspan="3">Technical</td>
          </tbody>
          <tbody>
            <td colspan="3" style="color: red">The vendor failed to pass previous
              envelopes.</td>
          </tbody>
        </table>
      @endif
    @endif

    @if (isset($vendor['financial']))
      @if ($vendor['financial'])
        <table class="envelopes">
          <tbody class="envelope-title">
            <td colspan="4">Financial</td>
          </tbody>
          <tbody class="envelope-header">
            <tr>
              <td>Inventory Id</td>
              <td>Description</td>
              <td>Quantity</td>
              <td>Reserved Price</td>
              <td>Price({{ $vendor['financial']['total_rating_score'] }}%)</td>
              <td>Tax/Duties/Fees/Levies</td>
              <td>Total Amount</td>
              {{-- <td>Status</td> --}}
            </tr>
          </tbody>
          <tbody>
            @foreach ($vendor['financial']['data'] as $financial)
              <tr>
                <td>{{ $financial['inventory_id'] }}</td>
                <td style="white-space: normal; !important;">{{ $financial['description'] }}</td>
                <td>{{ $financial['quantity'] }}</td>
                <td>
                  PHP {{ number_format($financial['reserved_price'], 2) }}
                </td>
                <td>
                  @if ($financial['admin_price'])
                    <span style="color: #E76727">PHP {{ number_format($financial['price'], 2) }} - admin</span>
                  @else
                    <span style="{{ $financial['price'] ? '' : 'color: red' }}">
                      {{ $financial['price'] ? 'PHP ' . number_format($financial['price'], 2) : 'No Offer' }}</span>
                  @endif
                </td>
                <td>
                  @if ($financial['admin_fees'])
                    <span style="color: #E76727">PHP {{ number_format($financial['other_fees'], 2) }} - admin</span>
                  @else
                    <span style="{{ $financial['other_fees'] ? '' : 'color: red' }}">
                      {{ $financial['other_fees'] ? 'PHP ' . number_format($financial['other_fees'], 2) : 'No Offer' }}</span>
                  @endif
                </td>
                @if ($financial['amount'])
                  <td>
                    PHP {{ number_format($financial['amount'], 2) }}</td>
                @else
                  <td>
                    {{ $financial['amount'] ? 'PHP ' . number_format($financial['amount'], 2) : 'No Offer' }}</td>
                @endif
                {{-- <td>
              @if ($technical['result'])
                <span style="color: green">PASSED</span>
              @else
                <span style="color: red">FAILED</span>
              @endif
            </td> --}}
              </tr>
            @endforeach
            <tr>
              <td colspan="6"></td>
              <td>
                <span style="font-weight: bold">
                PHP {{ number_format($vendor['financial']['grand_total'], 2) }}
                @if ($bid['score_method'] == 'Rating')
                  ({{ $vendor['financial']['vendor_rating_score'] }}%)
                @endif
                </span>
              </td>
            </tr>
          </tbody>
        </table>
        <hr>
      @else
        <table class="envelopes">
          <tbody class="envelope-title">
            <td colspan="3">Technical</td>
          </tbody>
          <tbody>
            <td colspan="3" style="color: red">The vendor failed to pass previous
              envelopes.</td>
          </tbody>
        </table>
      @endif
    @endif
  @empty
    No records found
  @endforelse
</body>

</html>
