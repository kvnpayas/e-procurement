@include('reports.newExcel.multiSheets.tei-header')
@php
  $allEnvelopes = [
      'eligibility' => (bool) $project->eligibility,
      'technical' => (bool) $project->technical,
      'financial' => (bool) $project->financial,
  ];

  $filterEnvelopes = array_filter($allEnvelopes, function ($value) {
      return $value === true;
  });
  $envelopes = [];

  foreach (array_keys($filterEnvelopes) as $key) {
      $envelopes[] = $key;
  }

  $vendors = $project->vendors;
  $joinedVendors = $vendors ? $vendors->whereNotIn('pivot.status', ['No Response', 'Declined', 'Awarded'])->count() : 0;
  $declinedVendors = $vendors ? $vendors->where('pivot.status', 'Declined')->count() : 0;
  $noResponseVendors = $vendors ? $vendors->where('pivot.status', 'No Response')->count() : 0;
  $declinedNoResponse = $vendors ? $vendors->where('pivot.status', 'Awarded')->count() : 0;
  $vendorLists = $vendors
      ->map(function ($vendor) use ($project) {
          $vendor->vendorStatus = $vendor->vendorStatus->where('bidding_id', $project->id)->first();
          return $vendor;
      })
      ->sortBy(function ($vendor) {
          // If submission_date is null, return a very large value (e.g., PHP_INT_MAX)
          return $vendor->vendorStatus && $vendor->vendorStatus->submission_date
              ? $vendor->vendorStatus->submission_date
              : PHP_INT_MAX;
      })
      ->values();
@endphp

<table>
  {{-- <thead>
    <tr>
      <td colspan="3">Date: {{ date('Y-m-d', strtotime(now())) }}</td>
      <td rowspan="3" colspan="14" valign="center" align="center">
        Tarlac Electric Inc.
      </td>
      <td colspan="3" align="right">Company: TEI</td>
    </tr>
    <tr>
      <td colspan="3">Time: {{ date('h:i A', strtotime(now())) }}</td>
      <td colspan="3" align="right"></td>
    </tr>
    <tr>
      <td colspan="3">User: {{ Auth::user()->name }}</td>
      <td colspan="3"></td>
    </tr>
    <tr>
      <td colspan='20'></td>
    </tr>
  </thead> --}}
  <tbody>
    <tr>
      <td colspan="20"></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="18">PROJECT INFORMATION</td>
    </tr>
    <tr>
      <td></td>
      <td colspan="3">Project No.</td>
      <td colspan="6">{{ $project->project_id }}</td>
      <td colspan="3">Scrap</td>
      <td colspan="6">{{ $project->scrap ? 'Yes' : 'No' }}</td>
    </tr>
    <tr>
      <td></td>
      <td colspan="3">Project Title</td>
      <td colspan="6">{{ $project->title }}</td>
      <td colspan="3">Score Method</td>
      <td colspan="6">{{ ucfirst($project->score_method) }}</td>
    </tr>
    <tr>
      <td></td>
      <td colspan="3">Project Type</td>
      <td colspan="6">{{ ucfirst($project->type) }}</td>
      <td colspan="3">Reserved Price</td>
      <td colspan="6">
        {{ $project->reserved_price_switch ? 'PHP ' . number_format($project->reserved_price, 2) : 'No Maximum Limit' }}
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="3">Project Status</td>
      <td colspan="6">{{ ucwords($project->status) }}</td>
      <td colspan="3">Envelope(s)</td>
      <td colspan="6">
        {{ strtoupper(implode(', ', $envelopes)) }}
      </td>
    </tr>

    <tr>

    </tr>

    <tr>
      <td></td>
      <td colspan="18">TIMING INFORMATION</td>
    </tr>
    <tr>
      <td></td>
      <td colspan="3">Name of the Buyer</td>
      <td colspan="6">{{ ucwords($project->created_user->name) }}</td>
      <td colspan="3">Publication Date</td>
      <td colspan="6">{{ date('F j, Y h:i A', strtotime($project->start_date)) }}</td>
    </tr>
    <tr>
      <td></td>
      <td colspan="3">Bid Opened Date</td>
      <td colspan="6">{{ date('F j, Y h:i A', strtotime($project->progress->envelope_open_date)) }}</td>
      <td colspan="3">Submission Due Date</td>
      <td colspan="6">
        {{ $project->extend_date ? date('F j, Y h:i A', strtotime($project->extend_date)) : date('F j, Y h:i A', strtotime($project->deadline_date)) }}
      </td>
    </tr>

    <tr>

    </tr>
    <tr>
      <td></td>
      <td colspan="18">VENDOR RESPONSE SUMMARY</td>
    </tr>
    <tr>
      <td></td>
      <td colspan="3">No. of Invited Vendor(s)</td>
      <td colspan="6">{{ $vendors ? $vendors->count() : 0 }}</td>
      <td colspan="3">
        @if ($project->status == 'Awarded')
          Declined/No Response
        @else
          Declined Vendor(s)
        @endif
      </td>
      <td colspan="6">
        @if ($project->status == 'Awarded')
          {{ $declinedNoResponse }}
        @else
          {{ $declinedVendors }}
        @endif
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="3">Joined Vendor(s)</td>
      <td colspan="6">{{ $joinedVendors }}</td>
      <td colspan="3">
        @if ($project->status != 'Awarded')
          No Response
        @endif
      </td>
      <td colspan="6">
        @if ($project->status != 'Awarded')
          {{ $noResponseVendors }}
        @endif
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="9">Vendor Name</td>
      <td colspan="6">Address</td>
      <td colspan="3">Response Date</td>
    </tr>
    @foreach ($vendorLists as $vendor)
      <tr>
        <td></td>
        <td colspan="9">{{ $vendor->name }}</td>
        <td colspan="6">{{ $vendor->address }}</td>
        <td colspan="3">
          @php
            $vendorStatus = $vendor->vendorStatus ? $vendor->vendorStatus->submission_date : '';
          @endphp
          {{ $vendorStatus ? date('F j,Y h:i A', strtotime($vendorStatus)) : 'No Response' }}
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
