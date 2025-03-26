<table>
  <thead>
    <tr></tr>
    <tr></tr>
    <tr></tr>
  </thead>

  <thead>
    <tr>
      <td>ITEM NBR</td>
      <td>PARTICULAR</td>
      <td>SPECIFICATIONS</td>
      @foreach ($data['vendors'] as $vendor)
        <td>{{ $vendor['name'] }}</td>
      @endforeach
    </tr>
  </thead>
  <tbody>
    @foreach ($data['projectbid']->eligibilities as $index => $eligbility)
      <tr>
        <td>{{ ++$index }}</td>
        <td>{{ $eligbility->name }}</td>
        <td></td>
        @foreach ($data['vendors'] as $vendor)
          @foreach ($vendor['eligibility']['data'] as $id => $vendorEligibility)
            @if ($id == $eligbility->id)
              <td>
                {{ $vendorEligibility['result'] ? 'Passed' : 'Failed' }}
              </td>
            @endif
          @endforeach
        @endforeach
      </tr>
    @endforeach
    <tr></tr>
    <tr></tr>
    <tr></tr>
  </tbody>

</table>
