<table style="border-collapse: collapse;">
  <thead>
    <tr>
      <td colspan="3" style="border: 0px solid white">Date: {{ date('Y-m-d', strtotime(now())) }}</td>
      <td rowspan="3" colspan="12" valign="center" align="center"
        style="text-align: center; font-weight: bolder; font-size: 24px;border: 0px solid white">
        {{-- <span><img src="{{ public_path('img/tei-logo-no-name.png') }}" alt="" height="30"></span> --}}
        Tarlac Electric Inc.
      </td>
      <td colspan="3" align="right" style="border: 0px solid white">Company: TEI</td>
    </tr>
    <tr>
      <td colspan="3" style="border: 0px solid white">Time: {{ date('h:i A', strtotime(now())) }}</td>
      <td colspan="3" align="right" style="border: 0px solid white"></td>
    </tr>
    <tr>
      <td colspan="3" style="border: 0px solid white">User: {{ Auth::user()->name }}</td>
      {{-- <td colspan="12" align="center"><span>Tarlac Electric Company</span></td> --}}
      <td colspan="3" style="border: 0px solid white"></td>
    </tr>
    <tr>
      <td colspan='18'></td>
    </tr>
  </thead>

  {{-- <thead>
    <tr>
      <td colspan="17" rowspan="2" valign="center" align="center" style="font-weight: bolder; font-size: 18px;">
        {{ $data['projectbid']['title'] }}
      </td>
    </tr>
    <tr>
      <td></td>
    </tr>
  </thead> --}}
  <thead class="project-info">
    <tr>
      <td bgcolor="#D9D9D9" colspan="18" style="font-weight: bolder; font-size: 18px; border: 1px solid #000;">
        Project Information
      </td>
    </tr>
    <tr>
      <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">
        Project No.
      </td>
      <td colspan="7" style="border: 1px solid #000;">
        {{ $data['projectbid']->project_id }}
      </td>
      <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">
        Scrap
      </td>
      <td colspan="7" style="border: 1px solid #000;">
        {{ $data['projectbid']->scrap ? 'Yes' : 'No' }}
      </td>
    </tr>
    <tr>
      <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">
        Project Title
      </td>
      <td colspan="7" style="border: 1px solid #000;">
        {{ $data['projectbid']->title }}
      </td>
      <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">
        Score Method
      </td>
      <td colspan="7" style="border: 1px solid #000;">
        {{ strtoupper($data['projectbid']->score_method) }}
      </td>
    </tr>
    <tr>
      <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">
        Project Type
      </td>
      <td colspan="7" style="border: 1px solid #000;">
        {{ strtoupper($data['projectbid']->type) }}
      </td>
      <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">
        Reserved Price
      </td>
      <td colspan="7" style="border: 1px solid #000;">
        {{ $data['projectbid']->reserved_price_switch ? 'PHP ' . number_format($data['projectbid']->reserved_price, 2) : 'No Maximum Limit' }}
      </td>
    </tr>
    <tr>
      <td colspan='18'></td>
    </tr>
  </thead>
  <thead>
    @php
      $envelopeResult = $envelope . 'Result';
      $vendors = $data['projectbid']->{$envelopeResult};
      $passedVendors = $vendors ? $vendors->where('result', true)->count() : 0;
      $faliedVendors = $vendors ? $vendors->where('result', false)->count() : 0;
      $remarks = $data['projectbid']->envelopeRemarks->where('envelope', $envelope)->first();
    @endphp
    <tr>
      <td bgcolor="#D9D9D9" colspan="18" style="font-weight: bolder; font-size: 18px; border: 1px solid #000;">
        Envelope Information
      </td>
    </tr>
    <tr>
      <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">
        Envelope
      </td>
      <td colspan="7" style="border: 1px solid #000;">
        {{ ucfirst($envelope) }}
      </td>
      <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">
        Passed Vendor(s)
      </td>
      <td align="left" colspan="7" style="border: 1px solid #000;">
        {{ $passedVendors }}
      </td>
    </tr>
    <tr>
      <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">
        No. of Vendor(s)
      </td>
      <td align="left" colspan="7" style="border: 1px solid #000;">
        {{ $vendors ? $vendors->count() : 0 }}
      </td>
      <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">
        Failed Vendor(s)
      </td>
      <td align="left" colspan="7" style="border: 1px solid #000;">
        {{ $faliedVendors }}
      </td>
    </tr>
    <tr>
      <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">
        Remarks
      </td>
      <td colspan="16" style="border: 1px solid #000;">
        {{ $remarks ? $remarks->remarks : null }}
      </td>
    </tr>
    <tr>
      <td colspan='18'></td>
    </tr>
  </thead>
  <thead>
    <tr>
      <td bgcolor="#D9D9D9" colspan="18" style="font-weight: bolder; font-size: 18px; border: 1px solid #000;">
        Timing Information
      </td>
    </tr>
    <tr>
      <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">
        Opened By
      </td>
      <td colspan="7" style="border: 1px solid #000;">
        {{ ucfirst($data['projectbid']->progress->open_user->name) }}
      </td>
      @php
        $allEnvelopes = [
            'eligibility' => (bool) $data['projectbid']->eligibility,
            'technical' => (bool) $data['projectbid']->technical,
            'financial' => (bool) $data['projectbid']->financial,
        ];

        $envelopes = array_filter($allEnvelopes, function ($value) {
            return $value === true;
        });
        $activeEnvelopes = array_keys(array_filter($envelopes));
        $keys = array_keys($activeEnvelopes, $envelope)[0] - 1;
        $method = $keys < 0 ? 'envelope_open_date' : $activeEnvelopes[$keys] . '_submit_date';
      @endphp
      <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">
        Publication Date
      </td>
      <td colspan="7" style="border: 1px solid #000;">
        {{ date('F j, Y h:i A', strtotime($data['projectbid']->start_date)) }}
      </td>
    </tr>
    <tr>
      <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">
        Opened Date
      </td>
      <td colspan="7" style="border: 1px solid #000;">
        {{ date('F j, Y h:i A', strtotime($data['projectbid']->progress->{$method})) }}
      </td>
      <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">
        Submission Due Date
      </td>
      <td colspan="7" style="border: 1px solid #000;">
        {{ $data['projectbid']->extend_date ? date('F j, Y h:i A', strtotime($data['projectbid']->extend_date)) : date('F j, Y h:i A', strtotime($data['projectbid']->deadline_date)) }}
      </td>
    </tr>
    <tr>
      <td colspan='18'></td>
    </tr>
  </thead>
</table>
