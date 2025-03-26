<div>
  {{-- review Modal --}}
  <div id="history-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-7xl max-h-full">
      <!-- Modal content -->
      <div class="relative tei-bg-primary rounded-3xl shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-white">
          <h3 class="text-xl font-extrabold text-white">
            Historical Price Data
          </h3>
          <button type="button" wire:click="closeHistoryModal"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <!-- Modal body -->
        <div>
          @if ($inventory)
            <div class="p-5">
              <h3 class="text-lg uppercase font-extrabold text-white">
                {{ $inventory ? $inventory->inventory_id : '' }}</h3>
              <table class="w-full text-xs tei-text-accent text-left rounded-lg bg-white mt-5">
                <thead>
                  <tr class="tei-bg-light">
                    <td class="px-2 py-2">Trans Date</td>
                    <td class="px-2 py-2">Inventory ID</td>
                    <td class="px-2 py-2">Description</td>
                    <td class="px-2 py-2">Class ID</td>
                    <td class="px-2 py-2">Unit Cost</td>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($inventoryHistories as $item)
                    <tr class="border-b">
                      <td class="px-2 py-2">
                        {{ date('F j,Y', strtotime($item->trans_date)) }}
                      </td class="px-2 py-2">
                      <td class="px-2 py-2">{{ $item->inventory_id }}</td>
                      <td class="px-2 py-2">{{ $item->description }}</td>
                      <td class="px-2 py-2">{{ $item->class_id }}</td>
                      <td class="px-2 py-2">PHP {{ number_format($item->unit_cost, 2) }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>

            </div>
          @endif
          <hr>
        </div>

        <div class="flex justify-center p-4 md:p-5 border-t border-white rounded-b">
          <button wire:click="closeHistoryModal"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-300 focus:z-10 focus:ring-4 focus:ring-gray-100 hover:scale-110 transition-transform duration-300">
            Close</button>
          {{-- <div class="rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading.delay
          wire:target="evaluateBidding">
          <div class="content-center">
            <div class="loading-small loading-main">
              <span></span>
              <span></span>
              <span></span>
              <span></span>
              <span></span>
            </div>
          </div>
        </div> --}}
        </div>
      </div>
    </div>
  </div>
</div>
{{-- END review Modal --}}
@script
  <script>
    $wire.on('openHistoryModal', () => {
      console.log(1);
      var modalElementOpen = document.getElementById('history-modal');
      var modalOpen = new Modal(modalElementOpen, {
        backdrop: 'static'
      });
      modalOpen.show();
    });

    $wire.on('closeHistoryModal', () => {
      var modalElement = document.getElementById('history-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });

    $wire.on('closeReviewVendorModal', () => {
      var modalElement = document.getElementById('review-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });
  </script>
@endscript
