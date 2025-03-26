<table>
  <thead>
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
  </thead>
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
      <td colspan="2">Project No.</td>
      <td colspan="7">{{ $project->project_id }}</td>
      <td colspan="3">Publication Date</td>
      <td colspan="6">{{ date('F j,Y h:i A', strtotime($project->start_date)) }}</td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2" rowspan="3">Project Title</td>
      <td colspan="7" rowspan="3">{{ $project->title }}</td>
      <td colspan="3">Bid Opening Date</td>
      <td colspan="6">{{ date('F j,Y h:i A', strtotime($project->progress->envelope_open_date)) }}</td>
    </tr>
    <tr>
      <td></td>
      <td colspan="3">Submission Due Date</td>
      <td colspan="6">
        {{ $project->extend_date ? date('F j,Y h:i A', strtotime($project->extend_date)) : date('F j,Y h:i A', strtotime($project->deadline_date)) }}
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="3">Name of the Buyer</td>
      <td colspan="6">{{ strtoupper($project->created_user->name) }}</td>
    </tr>

    <tr>

    </tr>

    <tr>
      <td></td>
      <td colspan="18">SETTING INFORMATION</td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2">Bid Type</td>
      <td colspan="4">{{ strtoupper($project->type) }}</td>
      <td colspan="2">Scrap</td>
      <td colspan="4">{{ $project->scrap ? 'Yes' : 'No' }}</td>
      <td colspan="2">Score Method</td>
      <td colspan="4">{{ strtoupper($project->score_method) }}</td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2">Reserved Price</td>
      <td colspan="4">
        {{ $project->reserved_price_switch ? 'PHP ' . number_format($project->reserved_price, 2) : 'No Maximum Limit' }}
      </td>
      <td colspan="2">Envelope/s</td>
      <td colspan="4">
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
        @endphp
        {{ strtoupper(implode(', ', $envelopes)) }}
      </td>
      <td colspan="2">No. of Vendor</td>
      <td colspan="4" align="left">{{ $project->invited_vendor }}</td>
    </tr>

    <tr>

    </tr>
    <tr>
      <td></td>
      <td colspan="18">VENDOR LISTS</td>
    </tr>
    <tr>
      <td></td>
      <td colspan="9">Vendor Name</td>
      <td colspan="6">Address</td>
      <td colspan="3">Response Date</td>
    </tr>
    @php
      $vendors = $project
          ->vendors()
          ->get()
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
    @foreach ($vendors as $vendor)
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
