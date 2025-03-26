@include('reports.newExcel.envelope-header-report', ['envelope' => 'technical'])
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
          Total Rating({{ $vendor['total_rating'] }}%)
        </td>
        <td colspan="7" style="border: 1px solid #000;">
          {{ number_format($vendor['vendor_total_rating'], 2) }}%
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
      <tr>
        <td bgcolor="#D9D9D9" colspan="18" style="font-weight: bolder; border: 1px solid #000;">
          Technical Envelope
        </td>
      </tr>
      <tr>
        <td colspan="5" style="font-weight: bolder; border: 1px solid #000;">Question</td>
        <td colspan="4" style="font-weight: bolder; border: 1px solid #000;">Response</td>
        <td colspan="2" style="font-weight: bolder; border: 1px solid #000;">Score %</td>
        <td colspan="4" style="font-weight: bolder; border: 1px solid #000;">Attachment(s)</td>
        <td colspan="3" style="font-weight: bolder; border: 1px solid #000;">Result</td>
      </tr>
    </thead>
    <tbody>
      @foreach ($vendor['data'] as $question)
        <tr>
          <td colspan="5" style="border: 1px solid #000;">{{ $question['question'] }}</td>
          <td colspan="4" style="border: 1px solid #000;">
            @if ($question['admin_answer'])
              <span style="color: green">{{ $question['admin_answer'] }} - ADMIN</span>
            @else
              <span style="color: red">{{ $question['answer'] }}</span>
            @endif
          </td>
          <td colspan="2" style="border: 1px solid #000;">{{ number_format($question['rating_score'], 2) }} %</td>
          <td colspan="4" style="border: 1px solid #000;">
            {!! nl2br(e(implode(', ', $question['files']))) !!}
          </td>
          <td colspan="3" style="border: 1px solid #000;">
            @if ($question['score'] == 'Fully Compliant')
              <span style="color: green">{{ $question['score'] }}</span>
            @elseif($question['score'] == 'Partially Compliant')
              <span style="color: #E76727">{{ $question['score'] }}</span>
            @else
              <span style="color: red">{{ $question['score'] }}</span>
            @endif
          </td>
        </tr>
      @endforeach
      <tr>
        <td bgcolor="#D9D9D9" colspan="2" style="font-weight: bolder; border: 1px solid #000;">Remarks</td>
        <td colspan="16" style="border: 1px solid #000;">{{ $vendor['vendor_remarks'] }}</td>
      </tr>
    </tbody>

  </table>
@endforeach
