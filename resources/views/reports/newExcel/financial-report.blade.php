@include('reports.newExcel.envelope-header-report', ['envelope' => 'financial'])
<table>
  <thead>
    <tr>
      <td bgcolor="#D9D9D9" colspan="18" style="font-weight: bolder; font-size: 18px; border: 1px solid #000;">
        Vendor Details
      </td>
    </tr>
  </thead>
</table>

@foreach ($data['vendors'] as $vendor)
  <table>
    <thead>
      <tr>
        <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">
          Vendor Name
        </td>
        <td colspan="7" style="border: 1px solid #000;">
          {{ $vendor['name'] }}
        </td>
        <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">
          Email
        </td>
        <td align="left" colspan="7" style="border: 1px solid #000;">
          {{ $vendor['email'] }}
        </td>
      </tr>
      <tr>
        <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">
          Address
        </td>
        <td colspan="7" style="border: 1px solid #000;">
          {{ $vendor['address'] }}
        </td>
        <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">
          Contact Number
        </td>
        <td align="left" colspan="7" style="border: 1px solid #000;">
          {{ $vendor['number'] }}
        </td>
      </tr>
      <tr>
        <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">
          Total Amount
        </td>
        <td colspan="7" style="border: 1px solid #000;">
          PHP {{ number_format($vendor['grand_total'], 2) }}
        </td>
        <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">
          Status
        </td>
        <td colspan="7" style="border: 1px solid #000;">
          @if ($vendor['result'])
            <span style="color: green">PASSED</span>
          @else
            <span style="color: red">FAILED</span>
          @endif
        </td>
      </tr>
      @if ($data['projectbid']->score_method == 'Rating')
        <tr>
          <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">
            Score
          </td>
          <td colspan="7" style="border: 1px solid #000;">
            {{ number_format($vendor['vendor_rating_score'], 2) }}%
          </td>
          <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">
          </td>
          <td colspan="7" style="border: 1px solid #000;">
          </td>
        </tr>
      @endif
      <tr>
        <td bgcolor="#D9D9D9" colspan="18" style="font-weight: bolder; border: 1px solid #000;">
          Financial Envelope
        </td>
      </tr>
      <tr>
        <td colspan="2" style="font-weight: bolder; border: 1px solid #000;">Inventory ID</td>
        <td colspan="5" style="font-weight: bolder; border: 1px solid #000;">Description</td>
        <td colspan="1" style="font-weight: bolder; border: 1px solid #000;">UOM</td>
        <td colspan="1" style="font-weight: bolder; border: 1px solid #000;">Quantity</td>
        <td colspan="2" style="font-weight: bolder; border: 1px solid #000;">Reserved Price</td>
        <td colspan="2" style="font-weight: bolder; border: 1px solid #000;">Vendor Price</td>
        <td colspan="2" style="font-weight: bolder; border: 1px solid #000;">Tax/Duties/Fees/Levies</td>
        <td colspan="3" style="font-weight: bolder; border: 1px solid #000;">Amount</td>
      </tr>
    </thead>
    <tbody>
      @foreach ($vendor['data'] as $response)
        <tr>
          <td colspan="2" style="border: 1px solid #000;">{{ $response['inventory_id'] }}</td>
          <td colspan="5" style="border: 1px solid #000;">{{ $response['description'] }}</td>
          <td colspan="1" style="border: 1px solid #000;">{{ $response['uom'] }}</td>
          <td colspan="1" style="border: 1px solid #000;">{{ $response['quantity'] }}</td>
          <td colspan="2" style="border: 1px solid #000;">PHP {{ number_format($response['reserved_price'], 2) }}
          </td>
          <td colspan="2" style="border: 1px solid #000;">
            @if ($response['admin_price'])
              PHP {{ number_format($response['price'], 2) }} - ADMIN
            @else
              PHP {{ number_format($response['price'], 2) }}
            @endif
          </td>
          <td colspan="2" style="border: 1px solid #000;">
            @if ($response['admin_fees'])
              PHP {{ number_format($response['other_fees'], 2) }} - ADMIN
            @else
              PHP {{ number_format($response['other_fees'], 2) }}
            @endif
          </td>
          <td colspan="3" style="border: 1px solid #000;">PHP {{ number_format($response['amount'], 2) }}</td>
        </tr>
      @endforeach
      <tr>
        <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">Attachment(s):</td>
        <td colspan="16" style="border: 1px solid #000;">
          {!! nl2br(e(implode(', ', $vendor['files']))) !!}
        </td>
      </tr>
      <tr>
        <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">Remarks</td>
        <td colspan="16" style="border: 1px solid #000;">{{ $vendor['vendor_remarks'] }}</td>
      </tr>
    </tbody>

  </table>
@endforeach
