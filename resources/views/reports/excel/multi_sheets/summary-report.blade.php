<table>
  <thead>
    <tr></tr>
    <tr></tr>
    <tr></tr>
  </thead>

  <thead>
    <tr>
      <td>RANK</td>
      <td colspan="6">BIDDER</td>
      @php
        $allEnvelopes = [
            'eligibility' => (bool) $data['projectbid']->eligibility,
            'technical' => (bool) $data['projectbid']->technical,
            'financial' => (bool) $data['projectbid']->financial,
        ];
        $firstEnvelope = array_search(true, $allEnvelopes, true);

        $envelopes = array_filter($allEnvelopes, function ($value) {
            return $value === true;
        });
      @endphp
      @foreach ($envelopes as $envelope => $value)
        <td colspan="3">
          {{ strtoupper($envelope) . ' EVALUATION' }}
          @if ($data['projectbid']->score_method == 'Rating' && $envelope != 'eligibility')
            @php
              $weight = $data['projectbid']->weights->where('envelope', $envelope)->first();
            @endphp
            {{ $weight ? '(' . $weight->weight . '%)' : '' }}
          @endif
        </td>
      @endforeach
      @if ($data['projectbid']->score_method == 'Rating')
        <td>TOTAL</td>
      @endif
    </tr>
  </thead>
  <tbody>
    @foreach ($data['vendors'] as $vendor)
      <tr>
        <td>{{ $vendor['rank'] }}</td>
        <td colspan="6">{{ strtoupper(string: $vendor['name']) }}</td>
        @if (isset($vendor['eligibility']))
          <td colspan="3">
            @if ($vendor['eligibility'])
              {{ $vendor['eligibility']['result'] ? 'Passed' : 'Failed' }}
            @else
              Failed
            @endif
          </td>
        @endif
        @if (isset($vendor['technical']))
          <td colspan="3">
            @if ($vendor['technical'])
              @if ($data['projectbid']->score_method == 'Rating')
                {{ number_format($vendor['technical']['vendor_total_rating'], 2) }}%
              @else
                {{ $vendor['technical']['result'] ? 'Passed' : 'Failed' }}
              @endif
            @else
              Failed
            @endif
          </td>
        @endif
        @if (isset($vendor['financial']))
          <td colspan="3">
            @if ($vendor['financial'])
              @if ($data['projectbid']->score_method == 'Rating')
                {{ number_format($vendor['financial']['vendor_rating_score'], 2) }}%
              @else
                PHP {{ number_format($vendor['financial']['grand_total'], 2) }}
              @endif
            @else
              Failed
            @endif
          </td>
        @endif
        @if ($data['projectbid']->score_method == 'Rating')
          <td>{{ number_format($vendor['score'], 2) }}%</td>
        @endif

        {{-- @foreach ($data['vendors'] as $vendor)
          @foreach ($vendor['eligibility']['data'] as $id => $vendorEligibility)
            @if ($id == $eligbility->id)
              <td>
                {{ $vendorEligibility['result'] ? 'Passed' : 'Failed' }}
              </td>
            @endif
          @endforeach
        @endforeach --}}
      </tr>
    @endforeach
    <tr></tr>
    <tr></tr>

    <tr>
      <td colspan="2">Score Method</td>
      <td colspan="5">{{ strtoupper($data['projectbid']->score_method) }}</td>
    </tr>
    <tr>
      <td colspan="2">Winning Bidder</td>
      <td colspan="5">
        @if ($data['projectbid']->status == 'Awarded' || $data['projectbid']->status == 'For Approval')
          {{ strtoupper($data['projectbid']->winnerApproval->winnerVendor->name) }}
        @endif
      </td>
    </tr>
    {{-- @if ($data['projectbid']->status == 'Awarded') --}}
    <tr>
      <td colspan="2">Approved By</td>
      <td colspan="5">
        @if ($data['projectbid']->winnerApproval && $data['projectbid']->winnerApproval->approverUser)
          {{ strtoupper($data['projectbid']->winnerApproval->approverUser->name) }}
        @endif
      </td>
    </tr>
    <tr>
      <td colspan="2">Final Approval By</td>
      <td colspan="5">
          @if ($data['projectbid']->winnerApproval && $data['projectbid']->winnerApproval->finalApproverUser)
          {{ strtoupper($data['projectbid']->winnerApproval->finalApproverUser->name) }}
          @endif
        </td>
    </tr>

    {{-- <tr></tr>
    <tr></tr>
    <tr></tr>
    @if ($data['projectbid']->status == 'Awarded')
      @if ($data['projectbid']->created_user->role_id != 4)
        <tr>
          <td>APPROVED BY</td>
        </tr>
        <tr>
          <td>Name</td>
          <td>{{ $data['projectbid']->winnerApproval->approverUser->name }}</td>
        </tr>
        <tr></tr>
      @endif
      <tr>
        <td>FINAL APPROVED</td>
      </tr>
      <tr>
        <td>Name</td>
        <td>{{ $data['projectbid']->winnerApproval->finalApproverUser->name }}</td>
      </tr>
    @endif --}}
  </tbody>

</table>
