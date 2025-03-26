@include('reports.newExcel.multiSheets.tei-header')

<table>
  <thead>
    <tr>
      <td colspan="2">ITEM NBR</td>
      <td colspan="5">QUESTION</td>
      @foreach ($data['vendors'] as $vendor)
        <td colspan="4">{{ $vendor['name'] }}</td>
      @endforeach
    </tr>
  </thead>
  <tbody>
    <tr>
      <td colspan="2"></td>
      <td colspan="5"></td>
      @foreach ($data['vendors'] as $vendor)
        <td colspan="2">Response</td>
        <td colspan="2">Result</td>
      @endforeach
    </tr>
    @foreach ($data['projectbid']->technicals as $index => $technical)
      <tr>
        <td colspan="2">{{ ++$index }}</td>
        <td colspan="5">{{ $technical->question }}</td>
        @foreach ($data['vendors'] as $vendor)
          @if ($vendor['technical'])
            @foreach ($vendor['technical']['data'] as $id => $vendorTechnical)
              @if ($id == $technical->id)
                <td colspan="2">
                  {{ $vendorTechnical['answer'] }}
                </td>
                {{-- <td>
            {{ number_format($vendorTechnical['rating_score'], 2)% }}
          </td> --}}
                <td colspan="2">
                  {{ $vendorTechnical['score'] }}
                </td>
              @endif
            @endforeach
          @endif
        @endforeach
      </tr>
    @endforeach
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
      $keys = array_keys($activeEnvelopes, 'technical')[0] - 1;
      $method = $keys < 0 ? 'envelope_open_date' : $activeEnvelopes[$keys] . '_submit_date';
      $methodUser = $keys < 0 ? 'open_user' :  $activeEnvelopes[$keys] . '_user';
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
