@include('reports.newExcel.multiSheets.tei-header')

<table>
  <thead>
    <tr>
      <td colspan="2">ITEM NBR</td>
      <td colspan="4">PARTICULAR</td>
      <td colspan="4">SPECIFICATIONS</td>
      @foreach ($data['vendors'] as $vendor)
        <td colspan="3">{{ $vendor['name'] }}</td>
      @endforeach
    </tr>
  </thead>
  <tbody>
    @foreach ($data['projectbid']->eligibilities as $index => $eligbility)
      <tr>
        <td colspan="2">{{ ++$index }}</td>
        <td colspan="4">{{ $eligbility->name }}</td>
        <td colspan="4"></td>
        @foreach ($data['vendors'] as $vendor)
          @foreach ($vendor['eligibility']['data'] as $id => $vendorEligibility)
            @if ($id == $eligbility->id)
              <td colspan="3">
                {{ $vendorEligibility['result'] ? 'Passed' : 'Failed' }}
              </td>
            @endif
          @endforeach
        @endforeach
      </tr>
    @endforeach
    <tr></tr>
    <tr></tr>
    <tr>
      <td colspan="2">Opened By</td>
      <td colspan="5">{{ ucwords($data['projectbid']->progress->open_user->name) }}</td>
    </tr>
    <tr>
      <td colspan="2">Opened Date</td>
      <td colspan="5">{{ date('F j, Y h:i A', strtotime($data['projectbid']->progress->envelope_open_date)) }}</td>
    </tr>
  </tbody>

</table>
