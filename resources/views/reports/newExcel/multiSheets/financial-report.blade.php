@include('reports.newExcel.multiSheets.tei-header')
<table>
  <thead>
    <tr>
      <td colspan="2">ITEM NBR</td>
      <td colspan="2">INVENTORY ID</td>
      <td colspan="5">DESCRIPTION</td>
      <td colspan="2">QTY</td>
      <td colspan="1">UOM</td>
      @foreach ($data['vendors'] as $vendor)
        <td colspan="6">{{ $vendor['name'] }}</td>
      @endforeach
    </tr>
  </thead>
  <tbody>
    <tr>
      <td colspan="2"></td>
      <td colspan="2"></td>
      <td colspan="5"></td>
      <td colspan="2"></td>
      <td colspan="1"></td>
      @foreach ($data['vendors'] as $vendor)
        <td colspan="2">Price</td>
        <td colspan="2">Tax/Duties/Fees/Levies</td>
        <td colspan="2">Total</td>
      @endforeach
    </tr>
    @foreach ($data['projectbid']->financials as $index => $financial)
      <tr>
        <td colspan="2">{{ ++$index }}</td>
        <td colspan="2">{{ $financial->inventory_id }}</td>
        <td colspan="5">{{ $financial->description }}</td>
        <td colspan="2">{{ $financial->pivot->quantity }}</td>
        <td colspan="1">{{ $financial->uom }}</td>
        @foreach ($data['vendors'] as $vendor)
          @if ($vendor['financial'])
            @foreach ($vendor['financial']['data'] as $id => $vendorFinancial)
              @if ($id == $financial->id)
                <td colspan="2">
                  PHP {{ number_format($vendorFinancial['price'], 2, '.', ',') }}
                </td>
                <td colspan="2">
                  PHP {{ number_format($vendorFinancial['other_fees'], 2, '.', ',') }}
                </td>
                <td colspan="2">
                  PHP {{ number_format($vendorFinancial['amount'], 2, '.', ',') }}
                </td>
              @endif
            @endforeach
          @endif
        @endforeach
      </tr>
    @endforeach
    <tr>
      <td colspan="2"></td>
      <td colspan="2"></td>
      <td colspan="5"></td>
      <td colspan="2"></td>
      <td colspan="1"></td>
      @foreach ($data['vendors'] as $vendor)
        @if ($vendor['financial'])
          <td colspan="2"></td>
          <td align="right" colspan="2">Grand Total</td>
          <td colspan="2">PHP {{ number_format($vendor['financial']['grand_total'], 2, '.', ',') }}</td>
        @endif
      @endforeach
    </tr>
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
      $keys = array_keys($activeEnvelopes, 'financial')[0] - 1;
      $method = $keys < 0 ? 'envelope_open_date' : $activeEnvelopes[$keys] . '_submit_date';
      $methodUser = $keys < 0 ? 'open_user' : $activeEnvelopes[$keys] . '_user';
    @endphp
    <tr></tr>
    <tr></tr>
    <tr>
      <td colspan="2">Opened By</td>
      <td colspan="5">
        {{ $data['projectbid']->progress->{$methodUser} ? ucwords($data['projectbid']->progress->{$methodUser}->name) : '' }}
      </td>
    </tr>
    <tr>
      <td colspan="2">Opened Date</td>
      <td colspan="5">{{ date('F j, Y h:i A', strtotime($data['projectbid']->progress->{$method})) }}</td>
    </tr>
  </tbody>

</table>
