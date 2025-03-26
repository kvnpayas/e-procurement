@include('reports.newExcel.envelope-header-report', ['envelope' => 'eligibility'])
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
        <td bgcolor="#D9D9D9" colspan="18" style="font-weight: bolder; border: 1px solid #000;">
          Eligibility Envelope
        </td>
      </tr>
      <tr>
        <td colspan="5" style="font-weight: bolder; border: 1px solid #000;">Name</td>
        <td colspan="5" style="font-weight: bolder; border: 1px solid #000;">Description</td>
        <td colspan="5" style="font-weight: bolder; border: 1px solid #000;">Attachment(s)</td>
        <td colspan="3" style="font-weight: bolder; border: 1px solid #000;">Status</td>
      </tr>
    </thead>
    <tbody>
      @foreach ($vendor['data'] as $eligibility)
        <tr>
          <td colspan="5" style="border: 1px solid #000;">{{ $eligibility['name'] }}</td>
          <td colspan="5" style="border: 1px solid #000;">{{ $eligibility['description'] }}</td>
          <td colspan="5" style="border: 1px solid #000;">
            {!! nl2br(e(implode(', ', $eligibility['files']))) !!}
          </td>
          <td colspan="3" style="border: 1px solid #000;">
            @if ($eligibility['result'])
              <span style="color: green">PASSED</span>
            @else
              <span style="color: red">FAILED</span>
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
