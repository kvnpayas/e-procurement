<div class="shadow-xl">
  <div class="overflow-x-auto shadow-md sm:rounded-lg">
    <div class="py-4">
      <div class="px-5">
        <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Project No:</label>
        <span class="text-xs uppercase font-extrabold tei-text-accent">{{ $bidding->project_id }}</span>
      </div>
      <div class="px-5">
        <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Project Title:</label>
        <span class="text-xs uppercase font-extrabold tei-text-accent">{{ $bidding->title }}</span>
      </div>
      <div class="px-5">
        <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Score Method:</label>
        <span class="text-xs uppercase font-extrabold tei-text-accent">{{ $bidding->score_method }} Based</span>
      </div>
      <div class="px-5">
        <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Sales:</label>
        <span class="text-xs uppercase font-extrabold tei-text-accent">{{ $bidding->scrap ? 'Yes' : 'No' }}</span>
      </div>
    </div>
  </div>
  <div class="p-2 flex justify-end tei-bg-light gap-4">
    <button wire:click="printReport" wire:loading.remove wire:target="printReport"
      class="text-xs bg-green-500 hover:bg-green-600 uppercase font-semibold text-white py-1.5 px-4 rounded-md shadow-lg hover:scale-110 transition-transform duration-300"><i
        class="fa-solid fa-file-lines"></i> Prints</button>
    <div class="w-20 rounded-lg bg-white flex justify-center p-3 shadow-xl" wire:loading wire:target="printReport">
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
    <button wire:click="bidPackage" wire:loading.remove wire:target="bidPackage"
      class="text-xs tei-bg-primary uppercase font-semibold text-white py-1.5 px-4 rounded-md shadow-lg hover:scale-110 transition-transform duration-300"><i
        class="fa-solid fa-folder-closed"></i> Bid Package</button>
    <div class="w-20 rounded-lg bg-white flex justify-center p-3 shadow-xl" wire:loading wire:target="bidPackage">
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
            {{ $envelope }}
            @if ($bidding->score_method == 'Rating')
              {{ $envelope == 'eligibility' ? $weight : '(' . $weight . '%)' }}
            @endif
          </th>
        @endforeach
        <th scope="col" class="px-6 py-3">
          {{ $bidding->score_method == 'Rating' ? 'Total Score' : 'Total Score/Total Offer' }}
        </th>
        <th scope="col" class="px-6 py-3">
          Status
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
                    class="{{ $vendor->{$envResult} ? 'text-green-500' : 'text-red-500' }}">{{ $vendor->{$envResult} ? number_format($vendor->{$envScore}, 1) . '%' : 'Failed' }}</span>
                @endif
              @endif
            </td>
          @endforeach
          <td class="px-6 py-4">
            @if ($vendor->final_result)
              {{ $bidding->score_method == 'Cost' ? 'PHP ' . number_format($vendor->total_score, 2) : number_format($vendor->total_score, 1) . '%' }}
            @else
              <span class="text-red-500">FAILED</span>
            @endif
          </td>
          <td class="px-6 py-4">
            @php
              $result = $vendor->winBids->where('bidding_id', $bidding->id)->first();
              $winner = $result ? $result->winner_id : null;
            @endphp
            @if ($winner == $vendor->id)
              <i class="fa-solid fa-trophy tei-text-secondary"></i> <span class="uppercase font-semibold">
                Winner</span>
            @endif
          </td>
          <td>
            <button wire:click.prevent="reviewModal({{ $vendor->id }})" wire:loading.remove
              wire:target="reviewModal({{ $vendor->id }})"
              class="hover:scale-110 transition-transform duration-300 mr-4 tei-bg-primary rounded-md px-2 py-1 text-white text-xs">Review</button>
            <div class="w-20 rounded-lg tei-bg-light flex justify-center p-3 shadow-xl" wire:loading
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


  @livewire('admin.modal.vendor-review')

  @livewire('admin.print-reports-modal')

  @livewire('admin.modal.bid-package')
</div>

@script
  <script>
    $wire.on('openPackageModal', () => {
      var modalElementOpen = document.getElementById('bid-package-modal');
      var modalOpen = new Modal(modalElementOpen, {
        backdrop: 'static'
      });
      modalOpen.show();
    });

    $wire.on('closePackageModal', () => {
      var modalElement = document.getElementById('bid-package-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });
  </script>
@endscript
