<div class="shadow-xl">
  <div class="p-2 flex justify-end tei-bg-light">
    <button wire:click="printReport" wire:loading.remove wire:target="printReport"
      class="text-xs bg-green-500 hover:bg-green-600 uppercase font-semibold text-white py-1.5 px-4 rounded-md shadow-lg hover:scale-110 transition-transform duration-300"><i
        class="fa-solid fa-file-lines"></i> Prints</button>
    <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4" wire:loading wire:target="printReport">
      <div class="flex justify-center ">
        <div class="loading-small loading-main">
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
    </div>
  </div>
  <table class="w-full text-sm text-left rtl:text-right text-gray-500">
    <caption class="p-5 text-lg font-semibold text-left rtl:text-right tei-text-secondary bg-white ">
      Final Results
      <p class="mt-1 text-sm font-normal text-gray-500 ">Here is the summary of the evaluation.
      </p>

    </caption>
    <thead class="text-xs text-gray-700 uppercase tei-bg-light ">
      <tr>
        <th colspan="2" class="bg-white"></th>
        <th colspan="{{ count($envelopes) }}" class="text-center px-6 py-2 tei-text-secondary border-white border">
          Envelopes
        </th>
      </tr>
      <tr>
        <th scope="col" class="px-6 py-3">
          Overall Rank
        </th>
        <th scope="col" class="px-6 py-3">
          Vendor Name
        </th>
        @foreach ($envelopes as $envelope => $weight)
          <th scope="col" class="px-6 py-3">
            {{ $envelope }} {{ $envelope == 'eligibility' ? $weight : '(' . $weight . '%)' }}
          </th>
        @endforeach
        <th scope="col" class="px-6 py-3">
          Total Score
        </th>
        <th scope="col" class="px-6 py-3">
          Action
        </th>
      </tr>
    </thead>
    <tbody class="shadow-lg rounded-lg bg-white text-xs uppercase font-semibold">
      @foreach ($vendors as $vendor)
        <tr>
          <td class="px-6 py-4">
            {{ $vendor->rank }}
          </td>
          <td class="px-6 py-4">
            {{ $vendor->name }}
          </td>
          @foreach ($envelopes as $envelope => $weight)
            <td class="px-6 py-4">
              @php
                $envResult = $envelope . '_result';
                $envScore = $envelope . '_score';
              @endphp
              @if ($envelope == 'eligibility')
                <span
                  class="{{ $vendor->{$envResult} ? 'text-green-500' : 'text-red-500' }}">{{ $vendor->{$envResult} ? 'Passed' : 'Failed' }}</span>
              @else
                @if ($bidding->score_method == 'Cost')
                  <span
                    class="{{ $vendor->{$envResult} ? 'text-green-500' : 'text-red-500' }}">{{ $vendor->{$envResult} ? 'Passed' : 'Failed' }}</span>
                @else
                  <span
                    class="{{ $vendor->{$envResult} ? 'text-green-500' : 'text-red-500' }}">{{ $vendor->{$envResult} ? number_format($vendor->{$envScore}, 2) . '%' : 'Failed' }}</span>
                @endif
              @endif
            </td>
          @endforeach
          <td class="px-6 py-4">
            @if ($vendor->final_result)
              {{ $bidding->score_method == 'Cost' ? 'PHP ' . number_format($vendor->total_score, 2) : number_format($vendor->total_score, 2) . '%' }}
            @else
              <span class="text-red-500">FAILED</span>
            @endif
          </td>
          <td>
            <button wire:click.prevent="reviewModal({{ $vendor->id }})" wire:loading.remove
              wire:target="reviewModal({{ $vendor->id }})"
              class="hover:scale-110 transition-transform duration-300 mr-4 tei-bg-primary rounded-md px-2 py-1 text-white text-xs">Review</button>
            <div class="w-14 rounded tei-bg-light flex justify-center py-2.5 mr-4" wire:loading
              wire:target="reviewModal({{ $vendor->id }})">
              <div class="flex justify-center ">
                <div class="loading-small loading-main">
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                </div>
              </div>
            </div>
          </td>
        </tr>
      @endforeach

    </tbody>
  </table>


  @livewire('admin.print-reports-modal')
  @livewire('admin.modal.vendor-review')
</div>

