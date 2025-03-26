<style>
  body {
    font-size: 12px;
  }

  .header-table,
  .table-info,
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

  .table-info thead td {
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
</style>

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
  </thead>
</table>

@php
  $envelopeResult = $envelope . 'Result';
  $vendors = $bid->{$envelopeResult};
  $passedVendors = $vendors ? $vendors->where('result', true)->count() : 0;
  $faliedVendors = $vendors ? $vendors->where('result', false)->count() : 0;
  $remarks = $bid->envelopeRemarks->where('envelope', $envelope)->first();
@endphp

<table class="table-info">
  <thead class="">
    <tr>
      <td colspan="4">Envelope Information</td>
    </tr>
  </thead>
  <thead>
    <tr>
      <td>Envelope</td>
      <td>{{ ucfirst($envelope) }}</td>
      <td>Passed Vendor(s)</td>
      <td>{{ $passedVendors }}</td>
    </tr>
    <tr>
      <td>No. of Vendor(s)</td>
      <td>{{ $vendors ? $vendors->count() : 0 }}</td>
      <td>Failed Vendor(s)</td>
      <td>{{ $faliedVendors }}</td>
    </tr>
    <tr>
      <td>Remarks</td>
      <td colspan="3">{{ $remarks ? $remarks->remarks : null }}</td>
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
      <td>Opened By</td>
      <td>{{ ucwords($bid->progress->open_user->name) }}</td>
      <td>Publication Date</td>
      <td>{{ date('F j, Y h:i A', strtotime($bid->start_date)) }}</td>
    </tr>
    <tr>
      <td>Opened Date</td>
      <td>
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
          $keys = array_keys($activeEnvelopes, $envelope)[0] - 1;
          $method = $keys < 0 ? 'envelope_open_date' : $activeEnvelopes[$keys] . '_submit_date';
        @endphp

        {{ date('F j, Y h:i A', strtotime($bid->progress->{$method})) }}
      </td>
      <td>Submission Due Date</td>
      <td>
        {{ $bid->extend_date ? date('F j, Y h:i A', strtotime($bid->extend_date)) : date('F j, Y h:i A', strtotime($bid->deadline_date)) }}
      </td>
    </tr>
  </thead>
</table>
