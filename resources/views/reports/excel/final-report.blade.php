<table>
  <thead>
    <tr>
      <td colspan="3" style="border: 0px solid white">Date: {{ date('Y-m-d', strtotime(now())) }}</td>
      <td rowspan="3" colspan="14" valign="center" align="center"
        style="text-align: center; font-weight: bolder; font-size: 24px;border: 0px solid white">
        {{-- <span><img src="{{ public_path('img/tei-logo-no-name.png') }}" alt="" height="30"></span> --}}
        Tarlac Electric Inc.
      </td>
      <td colspan="3" align="right" style="border: 0px solid white">Company: TEI</td>
    </tr>
    <tr>
      <td colspan="3" style="border: 0px solid white">Time: {{ date('h:i A', strtotime(now())) }}</td>
      <td colspan="3" align="right" style="border: 0px solid white"></td>
    </tr>
    <tr>
      <td colspan="3" style="border: 0px solid white">User: {{ Auth::user()->name }}</td>
      {{-- <td colspan="12" align="center"><span>Tarlac Electric Company</span></td> --}}
      <td colspan="3" style="border: 0px solid white"></td>
    </tr>
    <tr>
      <td colspan='20' bgcolor="#808080"></td>
    </tr>
  </thead>

  <thead>
    <tr>
      <td colspan="20" rowspan="2" valign="center" align="center" style="font-weight: bolder; font-size: 18px;">
        {{ $data['projectbid']['title'] }}
      </td>
    </tr>
    <tr>
      <td></td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th colspan="20" align="center">Envelopes</th>
    </tr>
    <tr>
      <td colspan='20' bgcolor="#808080"></td>
    </tr>
  </tbody>
</table>

@foreach ($data['vendors'] as $vendor)
  <table>
    <thead>
      <tr>
        <td colspan="2" style="font-weight: bolder;">Vendor Name: </td>
        <td colspan="8">{{ $vendor['name'] }}</td>
        <td colspan="2" style="font-weight: bolder;">Email: </td>
        <td colspan="8">{{ $vendor['email'] }}</td>
      </tr>
      <tr>
        <td colspan="2" style="font-weight: bolder;">Rank: </td>
        <td colspan="8" align="left">{{ $vendor['rank'] }}</td>
        <td colspan="2" style="font-weight: bolder;">Number: </td>
        <td colspan="8">{{ $vendor['number'] }}</td>
      </tr>
      <tr>
        <td colspan="2" style="font-weight: bolder;">Final Result: </td>
        <td colspan="8">{{ $vendor['result'] ? 'PASSED' : 'FAILED' }}</td>
        <td colspan="2" style="font-weight: bolder;">Address: </td>
        <td colspan="8">{{ $vendor['address'] }}</td>
      </tr>
      @if ($data['projectbid']['score_method'] == 'Rating')
        <tr>
          <td colspan="2" style="font-weight: bolder;">Total Score: </td>
          <td colspan="8" align="left">{{ number_format($vendor['score'], 2) }}%</td>
        </tr>
      @endif
    </thead>
    @if ($data['projectbid']['eligibility'])
      <tbody>
        <tr>
          <td colspan="20"></td>
        </tr>
        <tr>
          <td></td>
          <td colspan="19" bgcolor="#BFBFBF" style="font-weight: bolder; color:#ffff; font-size: 14px;">Eligibility
          </td>
        </tr>
        <tr>
          <td></td>
          <td colspan="6" style="font-weight: bolder;">Name</td>
          <td colspan="7" style="font-weight: bolder;">Description</td>
          <td colspan="6" style="font-weight: bolder;">Status</td>
        </tr>
        @foreach ($vendor['eligibility']['data'] as $eligibility)
          <tr>
            <td></td>
            <td colspan="6">{{ $eligibility['name'] }}</td>
            <td colspan="7">{{ $eligibility['description'] }}</td>
            <td colspan="6">
              @if ($eligibility['result'])
                PASSED
              @else
                FAILED
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    @endif
    @if ($data['projectbid']['technical'])
      <tbody>
        <tr>
          <td colspan="20"></td>
        </tr>
        <tr>
          <td></td>
          <td colspan="19" bgcolor="#BFBFBF" style="font-weight: bolder; color:#ffff; font-size: 14px;">Technical</td>
        </tr>
        <tr>
          <td></td>
          <td colspan="7" style="font-weight: bolder;">Question</td>
          <td colspan="4" style="font-weight: bolder;">Response</td>
          <td colspan="4" style="font-weight: bolder;">Rating Score({{ $vendor['technical']['total_rating'] ? $vendor['technical']['total_rating'].'%' : '' }})
          </td>
          <td colspan="4" style="font-weight: bolder;">Result</td>
        </tr>
        @if ($vendor['technical'])
          @foreach ($vendor['technical']['data'] as $technical)
            <tr>
              <td></td>
              <td colspan="7">{{ $technical['question'] }}</td>
              <td colspan="4">
                {{-- @if ($technical['admin_answer'])
                  {{ $technical['admin_answer'] }} - ADMIN
                @else
                  {{ $technical['answer'] }}
                @endif --}}
                {{ $technical['answer'] }}
              </td>
              <td colspan="4">
                {{ number_format($technical['rating_score'], 2) }}%
              </td>
              <td colspan="4">
                {{ $technical['score'] }}
              </td>
            </tr>
          @endforeach
          <tr>
            <td colspan="1"></td>
            <td colspan="9"></td>
            <td colspan="2" style="font-weight: bold">Total Rating:</td>
            <td colspan="4">
              {{ number_format($vendor['technical']['vendor_total_rating'], 2) }}%
            </td>
          </tr>
        @else
          <tr>
            <td></td>
            <td colspan="19" align="center">The vendor failed to pass previous
              envelopes.</td>
          </tr>
        @endif

      </tbody>
    @endif
    @if ($data['projectbid']['financial'])
      <tbody>
        <tr>
          <td colspan="20"></td>
        </tr>
        <tr>
          <td></td>
          <td colspan="19" bgcolor="#BFBFBF" style="font-weight: bolder; color:#ffff; font-size: 14px;">Financial</td>
        </tr>
        <tr>
          <td></td>
          <td colspan="3" style="font-weight: bolder;">Inventory Id</td>
          <td colspan="4" style="font-weight: bolder;">Description</td>
          <td colspan="1" style="font-weight: bolder;">Quantity</td>
          <td colspan="3" style="font-weight: bolder;">Reserved Price</td>
          <td colspan="2" style="font-weight: bolder;">Price({{ $vendor['financial']['total_rating_score'] }}%)
          </td>
          <td colspan="3" style="font-weight: bolder;">Tax/Duties/Fees/Levies</td>
          <td colspan="3" style="font-weight: bolder;">Total Amount</td>
        </tr>
        @if ($vendor['financial'])
          @foreach ($vendor['financial']['data'] as $financial)
            <tr>
              <td></td>
              <td colspan="3">{{ $financial['inventory_id'] }}</td>
              <td colspan="4">{{ $financial['description'] }}</td>
              <td colspan="1">{{ $financial['quantity'] }}</td>
              <td colspan="3">PHP {{ number_format($financial['reserved_price'], 2) }}</td>
              <td colspan="2">
                @if ($financial['admin_price'])
                  PHP {{ number_format($financial['price'], 2) }} - ADMIN
                @else
                  @if ($financial['price'])
                    PHP {{ number_format($financial['price'], 2) }}
                  @else
                    No offer
                  @endif
                @endif
              </td>
              <td colspan="3">
                @if ($financial['admin_fees'])
                  PHP {{ number_format($financial['other_fees'], 2) }} - ADMIN
                @else
                  @if ($financial['other_fees'])
                    PHP {{ number_format($financial['other_fees'], 2) }}
                  @else
                    No offer
                  @endif
                @endif
              </td>
              <td colspan="3">PHP {{ number_format($financial['amount'], 2) }}</td>
            </tr>
          @endforeach
          <tr>
            <td colspan="1"></td>
            <td colspan="14"></td>
            <td colspan="2" style="font-weight: bold">Grand Total
              @if ($data['projectbid']['score_method'] == 'Rating')
                ({{ $vendor['financial']['vendor_rating_score'] }}%)
              @endif
            </td>
            <td colspan="3">
              PHP {{ number_format($vendor['financial']['grand_total'], 2) }}
            </td>
          </tr>
        @else
          <tr>
            <td></td>
            <td colspan="19" align="center">The vendor failed to pass previous
              envelopes.</td>
          </tr>
        @endif

      </tbody>
    @endif
    {{-- <tbody>
    @forelse ($data['vendors'] as $vendor)
      <tr>
        <td colspan="3">{{ $vendor['name'] }}</td>
        <td colspan="3">{{ $vendor['email'] }}</td>
        <td colspan="3">{{ $vendor['address'] }}</td>
        <td colspan="3">{{ $vendor['number'] }}</td>
        <td colspan="3">
          @if ($vendor['result'] == 'PASSED')
            <span style="color: #008000">PASSED</span>
          @else
            <span style="color: #ff0000">FAILED</span>
          @endif
        </td>
      </tr>
      <tr>
        <td colspan="2" style="font-weight: bolder; font-size: 12px;">Inventory Id</td>
        <td colspan="3" style="font-weight: bolder; font-size: 12px;">Description</td>
        <td colspan="1" style="font-weight: bolder; font-size: 12px;">UOM</td>
        <td colspan="1" style="font-weight: bolder; font-size: 12px;">Quantity</td>
        <td colspan="2" style="font-weight: bolder; font-size: 12px;">Reserved Price</td>
        <td colspan="2" style="font-weight: bolder; font-size: 12px;">Vendor Price</td>
        <td colspan="2" style="font-weight: bolder; font-size: 12px;">Tax/Duties/Fees/Levies</td>
        <td colspan="2" style="font-weight: bolder; font-size: 12px;">Amount</td>
      </tr>
      @foreach ($vendor['response'] as $response)
        <tr>
          <td colspan="2">{{ $response['inventory_id'] }}</td>
          <td colspan="3">{{ $response['description'] }}</td>
          <td colspan="1">{{ $response['uom'] }}</td>
          <td colspan="1">{{ $response['quantity'] }}</td>
          <td colspan="2">PHP {{ number_format($response['reserved_price'],2) }}</td>
          <td colspan="2">
            @if ($response['admin_price'])
            PHP {{ number_format($response['admin_price'],2) }} - ADMIN
            @else
            PHP {{ number_format($response['price'],2) }}
            @endif
          </td>
          <td colspan="2">
            @if ($response['admin_fees'])
            PHP {{ number_format($response['admin_fees'],2) }} - ADMIN
            @else
            PHP {{ number_format($response['vendor_other_fees'],2) }}
            @endif
          </td>
          <td colspan="2">PHP {{ number_format($response['amount'],2) }}</td>
        </tr>
      @endforeach
      <tr>
        <td colspan="15"></td>
      </tr>
    @empty
      <tr>
        <td align="center" bgcolor="#C0C0C0" colspan='15'>No Records to show</td>
      </tr>
    @endforelse
  </tbody> --}}
  </table>
@endforeach
