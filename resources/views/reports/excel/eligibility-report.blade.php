<table>
  <thead>
    <tr>
      <td colspan="3" style="border: 0px solid white">Date: {{ date('Y-m-d', strtotime(now())) }}</td>
      <td rowspan="3" colspan="11" valign="center" align="center"
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
      <td colspan='17' bgcolor="#808080"></td>
    </tr>
  </thead>

  <thead>
    <tr>
      <td colspan="17" rowspan="2" valign="center" align="center" style="font-weight: bolder; font-size: 18px;">
        {{ $data['projectbid']['title'] }}
      </td>
    </tr>
    <tr>
      <td></td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th colspan="17" align="center">Eligibility Envelopes</th>
    </tr>
    <tr>
      <td colspan='17' bgcolor="#808080"></td>
    </tr>
  </tbody>
</table>

<table>
  <thead>
    <tr>
      <td colspan="3" style="font-weight: bolder; font-size: 14px;">Vendor Name</td>
      <td colspan="3" style="font-weight: bolder; font-size: 14px;">Email</td>
      <td colspan="3" style="font-weight: bolder; font-size: 14px;">Number</td>
      <td colspan="5" style="font-weight: bolder; font-size: 14px;">Address</td>
      <td colspan="3" style="font-weight: bolder; font-size: 14px;">Status</td>
    </tr>
  </thead>
  <tbody>
    @forelse ($data['vendors'] as $vendor)
      <tr>
        <td colspan="3">{{ $vendor['name'] }}</td>
        <td colspan="3">{{ $vendor['email'] }}</td>
        <td colspan="3">{{ $vendor['number'] }}</td>
        <td colspan="5">{{ $vendor['address'] }}</td>
        <td colspan="3">
          @if ($vendor['result'])
            <span style="color: #008000">PASSED</span>
          @else
            <span style="color: #ff0000">FAILED</span>
          @endif
        </td>

        {{-- @foreach ($items as $item)
          <td>{{ $item }}</td>
        @endforeach --}}
      </tr>
      <tr>
        <td colspan="5" style="font-weight: bolder; font-size: 12px;">Eligbiility Name</td>
        <td colspan="5" style="font-weight: bolder; font-size: 12px;">Eligbiility Description</td>
        <td colspan="5" style="font-weight: bolder; font-size: 12px;">Attachments</td>
        <td colspan="2" style="font-weight: bolder; font-size: 12px;">Status</td>
      </tr>
      @foreach ($vendor['data'] as $eligibility)
        <tr>
          <td colspan="5">{{ $eligibility['name'] }}</td>
            <td colspan="5">{{ $eligibility['description'] }}</td>
            <td colspan="5" style="">
              {!! nl2br(e(implode(", ", $eligibility['files']))) !!}
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
        <td colspan="15"></td>
      </tr>
    @empty
      <tr>
        <td align="center" bgcolor="#C0C0C0" colspan='15'>No Records to show</td>
      </tr>
    @endforelse
    <tr>
      <td colspan="2" style="font-weight: bold">
        Remarks:
      </td>
      <td colspan="8" rowspan="2" align="left" valign="top">
        {{ $data['remarks'] }}
      </td>
    </tr>
  </tbody>
</table>
