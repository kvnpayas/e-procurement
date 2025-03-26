<table>
  <thead>
    <tr></tr>
    <tr></tr>
    <tr></tr>
  </thead>

  <thead>
    <tr>
      <td>ITEM NBR</td>
      <td>QUESTION</td>
      @foreach ($data['vendors'] as $vendor)
        <td colspan="2">{{ $vendor['name'] }}</td>
      @endforeach
    </tr>
  </thead>
  <tbody>
    <tr>
      <td></td>
      <td></td>
      @foreach ($data['vendors'] as $vendor)
        <td>Response</td>
        <td>Score</td>
      @endforeach
    </tr>
    @foreach ($data['projectbid']->technicals as $index => $technical)
      <tr>
        <td>{{ ++$index }}</td>
        <td>{{ $technical->question }}</td>
        @foreach ($data['vendors'] as $vendor)
          @if ($vendor['technical'])
            @foreach ($vendor['technical']['data'] as $id => $vendorTechnical)
              @if ($id == $technical->id)
                <td>
                  {{ $vendorTechnical['answer'] }}
                </td>
                {{-- <td>
            {{ number_format($vendorTechnical['rating_score'], 2)% }}
          </td> --}}
                <td>
                  {{ $vendorTechnical['score'] }}
                </td>
              @endif
            @endforeach
          @endif
        @endforeach
      </tr>
    @endforeach
    <tr></tr>
    <tr></tr>
    <tr></tr>
  </tbody>

</table>
