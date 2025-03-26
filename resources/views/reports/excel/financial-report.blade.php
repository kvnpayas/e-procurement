<table>
  <thead>
    <tr>
      <td colspan="3" style="border: 0px solid white">Date: {{ date('Y-m-d', strtotime(now())) }}</td>
      <td rowspan="3" colspan="15" valign="center" align="center"
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
      <td colspan='21' bgcolor="#808080"></td>
    </tr>
  </thead>

  <thead>
    <tr>
      <td colspan="21" rowspan="2" valign="center" align="center" style="font-weight: bolder; font-size: 18px;">
        {{ $data['projectbid']['title'] }}
      </td>
    </tr>
    <tr>
      <td></td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th colspan="21" align="center">Financial Envelopes</th>
    </tr>
    <tr>
      <td colspan='21' bgcolor="#808080"></td>
    </tr>
  </tbody>
</table>

<table>
  @forelse ($data['vendors'] as $vendor)
    <thead>
      <tr>
        <td colspan="3" style="font-weight: bolder; font-size: 14px;">Vendor Name</td>
        <td colspan="3" style="font-weight: bolder; font-size: 14px;">Email</td>
        <td colspan="3" style="font-weight: bolder; font-size: 14px;">Address</td>
        <td colspan="3" style="font-weight: bolder; font-size: 14px;">Number</td>
        <td colspan="3" style="font-weight: bolder; font-size: 14px;">Total Amount</td>
        @if ($data['projectbid']['score_method'] == 'Rating')
          <td colspan="3" style="font-weight: bolder; font-size: 14px;">Score</td>
        @endif
        <td colspan="3" style="font-weight: bolder; font-size: 14px;">Status</td>
      </tr>
    </thead>
    <tbody>

      <tr>
        <td colspan="3">{{ $vendor['name'] }}</td>
        <td colspan="3">{{ $vendor['email'] }}</td>
        <td colspan="3">{{ $vendor['address'] }}</td>
        <td colspan="3">{{ $vendor['number'] }}</td>
        <td colspan="3">PHP {{ number_format($vendor['grand_total'], 2) }}</td>
        @if ($data['projectbid']['score_method'] == 'Rating')
          <td colspan="3">
            {{ number_format($vendor['vendor_rating_score'], 2) }}%
          </td>
        @endif
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
        <td colspan="2" style="font-weight: bolder; font-size: 12px;">Inventory Id</td>
        <td colspan="5" style="font-weight: bolder; font-size: 12px;">Description</td>
        <td colspan="1" style="font-weight: bolder; font-size: 12px;">UOM</td>
        <td colspan="1" style="font-weight: bolder; font-size: 12px;">Quantity</td>
        <td colspan="3" style="font-weight: bolder; font-size: 12px;">Reserved Price</td>
        <td colspan="3" style="font-weight: bolder; font-size: 12px;">Vendor Price</td>
        <td colspan="3" style="font-weight: bolder; font-size: 12px;">Tax/Duties/Fees/Levies</td>
        <td colspan="3" style="font-weight: bolder; font-size: 12px;">Amount</td>
      </tr>
      @foreach ($vendor['data'] as $response)
        <tr>
          <td colspan="2">{{ $response['inventory_id'] }}</td>
          <td colspan="5">{{ $response['description'] }}</td>
          <td colspan="1">{{ $response['uom'] }}</td>
          <td colspan="1">{{ $response['quantity'] }}</td>
          <td colspan="3">PHP {{ number_format($response['reserved_price'], 2) }}</td>
          <td colspan="3">
            @if ($response['admin_price'])
              PHP {{ number_format($response['price'], 2) }} - ADMIN
            @else
              PHP {{ number_format($response['price'], 2) }}
            @endif
          </td>
          <td colspan="3">
            @if ($response['admin_fees'])
              PHP {{ number_format($response['other_fees'], 2) }} - ADMIN
            @else
              PHP {{ number_format($response['other_fees'], 2) }}
            @endif
          </td>
          <td colspan="3">PHP {{ number_format($response['amount'], 2) }}</td>
        </tr>
      @endforeach
      <tr>
        <td colspan="2" style="font-weight: bolder; font-size: 12px;">Attachments:</td>
        <td colspan="19">
          {!! nl2br(e(implode(', ', $vendor['files']))) !!}
        </td>
      </tr>
      <tr>
        <td colspan="21"></td>
      </tr>
    @empty
      <thead>
        <tr>
          <td align="center" bgcolor="#C0C0C0" colspan='21'>No Records to show</td>
        </tr>
      </thead>
    </tbody>
  @endforelse
  <tr>
    <td colspan="2" style="font-weight: bold">
      Remarks:
    </td>
    <td colspan="8" rowspan="2" align="left" valign="top">
      {{ $data['remarks'] }}
    </td>
  </tr>
</table>
