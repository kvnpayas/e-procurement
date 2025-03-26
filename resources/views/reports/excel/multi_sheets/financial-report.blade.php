<table>
  <thead>
    <tr></tr>
    <tr></tr>
    <tr></tr>
  </thead>

  <thead>
    <tr>
      <td>ITEM NBR</td>
      <td>INVENTORY ID</td>
      <td>DESCRIPTION</td>
      <td>QTY</td>
      <td>UOM</td>
      @foreach ($data['vendors'] as $vendor)
        <td colspan="3">{{ $vendor['name'] }}</td>
      @endforeach
    </tr>
  </thead>
  <tbody>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      @foreach ($data['vendors'] as $vendor)
        <td>Price</td>
        <td>Tax/Duties/Fees/Levies</td>
        <td>Total</td>
      @endforeach
    </tr>
    @foreach ($data['projectbid']->financials as $index => $financial)
      <tr>
        <td>{{ ++$index }}</td>
        <td>{{ $financial->inventory_id }}</td>
        <td>{{ $financial->description }}</td>
        <td>{{ $financial->pivot->quantity }}</td>
        <td>{{ $financial->uom }}</td>
        @foreach ($data['vendors'] as $vendor)
          @if ($vendor['financial'])
            @foreach ($vendor['financial']['data'] as $id => $vendorFinancial)
              @if ($id == $financial->id)
                <td>
                  PHP {{ number_format($vendorFinancial['price'], 2, '.', ',') }}
                </td>
                <td>
                  PHP {{ number_format($vendorFinancial['other_fees'], 2, '.', ',') }}
                </td>
                <td>
                  PHP {{ number_format($vendorFinancial['amount'], 2, '.', ',') }}
                </td>
              @endif
            @endforeach
          @endif
        @endforeach
      </tr>
    @endforeach
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      @foreach ($data['vendors'] as $vendor)
        @if ($vendor['financial'])
          <td></td>
          <td align="right">Grand Total</td>
          <td>PHP {{ number_format($vendor['financial']['grand_total'], 2, '.', ',') }}</td>
        @endif
      @endforeach
    </tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
  </tbody>

</table>
