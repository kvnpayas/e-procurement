<div class="overflow-x-auto shadow-md sm:rounded-lg">
  <table class="w-full text-sm text-left rtl:text-right text-gray-500 boder-collapse">
    <caption class="p-5 text-lg font-semibold text-left rtl:text-right tei-text-secondary bg-white ">
      Pending Protest
      <p class="mt-1 text-sm font-normal text-gray-500 ">List of all bids that have pending protests from a vendor.
      </p>
      <div class="flex pt-5 gap-4">
        <div class="flex">
          <span
            class="inline-flex items-center px-3 text-sm tei-text-light tei-bg-primary border rounded-e-0 border-gray-300 border-e-0 rounded-s-md ">
            Search
          </span>
          <div class="relative">
            <input type="text" wire:model.live.debounce.500ms="search"
              class="block w-full text-xs tei-text-accent bg-transparent rounded-e-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 tei-focus-secondary peer"
              placeholder=" " />
            <label
              class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-orange-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Search
              here</label>
          </div>
        </div>
      </div>
    </caption>
    <thead class="text-xs tei-text-primary uppercase tei-bg-light font-extrabold">
      @foreach ($tableHeader as $index => $header)
        <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
          <div class="flex justify-between">
            <span>{{ $header }}</span>
            @if ($index != 'action')
              <button wire:click.prevent="selectedFilters('{{ $index }}')">
                <div class="flex flex-col pt-0.5 text-gray-900">
                  <i class="fa-solid fa-sort-up {{ $orderBy == $index && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                    style="line-height: 0"></i>
                  <i class="fa-solid fa-sort-down {{ $orderBy == $index && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                    style="line-height: 0"></i>
                </div>
              </button>
            @endif
          </div>
        </td>
      @endforeach

    </thead>
    <tbody>
      @forelse ($biddings as $bidding)
        <tr class="bg-white border-b font-extrabold hover:bg-neutral-200 hover:shadow-xl transition duration-300">
          <th scope="row" class="px-6 py-2 font-medium tei-text-secondary whitespace-nowrap ">
            {{ $bidding->id }}
          </th>
          <td class="px-6 py-2">
            {{ $bidding->title }}
          </td>
          <td class="px-6 py-2">
            <span
              class="{{ $bidding->status == 'Awarded' ? 'text-green-500' : 'text-yellow-500' }}">{{ $bidding->status }}</span>
          </td>
          <td class="px-6 py-2">
            {{ $bidding->protest_count }}
          </td>
          <td class="px-6 py-2">
            @if ($bidding->status == 'Awarded')
              <button wire:click.prevent="protesModal({{ $bidding->id }})"
                class="hover:scale-110 transition-transform duration-300 mr-4 bg-green-500 rounded-md px-2 py-1 text-white text-xs">show
                details</button>
            @endif
          </td>
        </tr>
        @php
          $check = [];
        @endphp
      @empty
        <tr class="bg-white border-b">
          <th scope="row" colspan="100" class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap text-center">
            <span class="font-black text-lg tei-text-primary">There is no pending protest on project bid.</span>
          </th>
        </tr>
      @endforelse
    </tbody>
  </table>
  {{-- <x-action-message class="me-3" on="alert-eligibility">
    {{ __($alertMessage) }}
    </x-action-message> --}}

  {{ $biddings->links('livewire.layout.pagination') }}

  {{-- Protest Modal --}}
  <div id="protest-modal" tabindex="-1" data-modal-backdrop="static" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-7xl max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">
            Protest Submission List
          </h3>
          <button type="button" wire:click="closeProtestModal"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        @if ($protestVendors)
          <div class="p-4 md:p-5 bg-[#f3f3f3]" wire:loading.remove wire:target="awardWinner">

            <p class="mt-1 text-sm font-normal text-gray-500 ">List of all individuals or vendors who have submitted
              protests for review.
            </p>
            @php
              $awardDate = $selectedProtestBid->winnerApproval->awarded_date;
              $protestDeadlineDate = $this->protestDeadlineDate($awardDate);
            @endphp
            <div class="mt-4">
              <label class="text-sm uppercase tei-text-primary font-semibold">Bid title: </label>
              <span class="text-sm font-semibold tei-text-accent">{{ $selectedProtestBid->title }}</span>
            </div>
            <div class="mt-4">
              <label class="text-sm uppercase tei-text-primary font-semibold">Protest Deadline Date: </label>
              <span
                class="text-sm uppercase font-semibold tei-text-accent">{{ date('F j,Y @ h:i A', strtotime($protestDeadlineDate)) }}</span>
            </div>
            <div class="flex justify-center">
              @error('vendorResults')
                <div class="w-1/4 bg-red-500 p-2 text-center shadow-md rounded-md mt-4">
                  <p class="text-xs text-white font-semibold">
                    {{ $message }}
                  </p>
                </div>
              @enderror
            </div>
            @php
              $vendors = $selectedProtestBid->protest->vendors->where('pivot.status', 'Pending');
            @endphp
            @forelse ($vendors as $protestVendor)
              @php
                // dd($protestVendor);
                // $vendorDetails = $protestVendor->vendors->where('id', $protestVendor->vendor_id)->first();
                // $vendor = $vendorDetails ? $vendorDetails->name : null;
                $messages = explode('"\n"', $protestVendor->pivot->protest_message);
              @endphp
              <div class="hover:shadow-xl p-4 rounded-md mt-5 bg-white transition-shadow ease-in-out delay-150">
                <div class="w-full grid grid-cols-4 gap-4">
                  <div>
                    <label class="text-xs uppercase tei-text-secondary font-semibold">Vendor Name: </label>
                    <span class="text-xs uppercase font-semibold tei-text-accent">{{ $protestVendor->name }}</span>
                  </div>
                  <div>
                    <label class="text-xs uppercase tei-text-secondary font-semibold">Status: </label>
                    <span
                      class="text-xs uppercase font-semibold tei-text-accent">{{ $protestVendor->pivot->status }}</span>
                  </div>
                  <div class="col-span-2">
                    <label class="text-xs uppercase tei-text-secondary font-semibold">Continue protest: </label>
                    <div class="items-center mb-4 inline-block ml-2">
                      <input id="default-checkbox" type="checkbox" value=""
                        wire:model="vendorProtest.{{ $protestVendor->pivot->vendor_id }}"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-0">
                      <label for="default-checkbox" class="ms-2 text-xs tei-text-accent font-semibold">Check if you want
                        the protest to contine.</label>
                    </div>
                  </div>
                  <div class="col-span-2">
                    <label class="text-xs uppercase tei-text-secondary font-semibold">Vendor Message</label>
                    <div class="mb-5 text-justify border-2 p-5 rounded-md">
                      @if ($messages)
                        @foreach ($messages as $message)
                          <p class="text-xs font-semibold tei-text-accent">{{ $message }}</p>
                        @endforeach
                      @endif

                    </div>
                  </div>
                </div>
              </div>
            @empty
              <div class="text-center">No protests to show</div>
            @endforelse

          </div>
          <div class="flex justify-center p-4 md:p-5 border-t border-gray-200 rounded-b">
            {{-- @if ($selectedProtestBid->protest->protest_deadline_date > now())
              <button data-tooltip-target="tooltip-protest"
                class="py-2.5 px-5 ms-3 text-sm font-medium text-white focus:outline-none bg-gray-300 rounded-lg border focus:z-10 focus:ring-0 disabled">
                Hold Bid</button>
              <div id="tooltip-protest" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-secondary rounded-lg shadow-sm opacity-0 tooltip">
                Hold bid button is disabled until the protest deadline date is met.
                <div class="tooltip-arrow" data-popper-arrow></div>
              </div>
            @else --}}
            <button wire:click="holdBidModal({{ $selectedProtestBid->id }})"
              class="py-2.5 px-5 ms-3 text-sm font-medium text-white focus:outline-none bg-yellow-500 rounded-lg border hover:bg-yellow-600 focus:z-10 focus:ring-0 hover:scale-110 transition-transform duration-300">
              Hold Bid</button>
            {{-- @endif --}}

            <button wire:click="endProtestModal({{ $selectedProtestBid->id }})"
              class="py-2.5 px-5 ms-3 text-sm font-medium text-white focus:outline-none bg-red-500 rounded-lg border hover:bg-red-600 focus:z-10 focus:ring-0 hover:scale-110 transition-transform duration-300">
              End protest</button>
            <button wire:click="closeProtestModal"
              class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-300 focus:z-10 focus:ring-4 focus:ring-gray-100 hover:scale-110 transition-transform duration-300">
              Cancel</button>
          </div>
        @endif
        <div class="bg-white w-full rounded-md px-32 py-14" wire:loading wire:target="awardWinner">
          <div class="text-center">
            <span class="tei-text-primary font-extrabold">Please wait</span>
          </div>
          <div class="flex justify-center">
            <div class="loading loading-main">
              <span></span>
              <span></span>
              <span></span>
              <span></span>
              <span></span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
  {{-- END Protest Modal --}}

  {{-- End Protest Modal --}}
  <div id="end-protest-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button" wire:click="closeEndProtestModal"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        <div class="p-4 md:p-5 text-center">
          <svg class="mx-auto mb-4 tei-text-primary w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          <h3 class="mb-5 text-lg font-normal tei-text-primary ">Are you sure you want to end the protest process of
            <span class="font-semibold underline">{{ $endProtestBid ? $endProtestBid->title : '' }}</span>?
          </h3>
          <button wire:click="endProtest"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-white focus:outline-none bg-red-500 rounded-lg border hover:bg-red-600 focus:z-10 focus:ring-0 hover:scale-110 transition-transform duration-300">
            End Protest</button>
          <button wire:click="closeEndProtestModal" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10 focus:ring-4 focus:ring-gray-100">
            Cancel</button>
        </div>
      </div>
    </div>
  </div>
  {{-- END End Protest Modal --}}

  {{-- End continue protest Modal --}}
  <div id="continue-protest-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button" wire:click="closeContinueProtestModal"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        <div class="p-4 md:p-5 text-center">
          <svg class="mx-auto mb-4 tei-text-primary w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          <h3 class="mb-5 text-lg font-normal tei-text-primary ">Are you sure you want to continue the protest process
            of
            <span class="font-semibold underline">{{ $holdProtestBid ? $holdProtestBid->title : '' }}</span>?
          </h3>
          <button wire:click="continueProtest" wire:loading.remove wire:target="continueProtest"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-white focus:outline-none bg-green-500 rounded-lg border hover:bg-green-600 focus:z-10 focus:ring-0 hover:scale-110 transition-transform duration-300">
            Proceed</button>
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
            wire:target="continueProtest">
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
          <button wire:click="closeContinueProtestModal" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10 focus:ring-4 focus:ring-gray-100">
            Cancel</button>
        </div>
      </div>
    </div>
  </div>
  {{-- END End continue protest Modal --}}

  {{-- @livewire('admin.print-reports-modal') --}}
</div>

@script
  <script>
    $wire.on('openProtestModal', () => {
      var modalElement = document.getElementById('protest-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });

    $wire.on('closeProtestModal', () => {
      var modalElement = document.getElementById('protest-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });

    $wire.on('openEndProtestModal', () => {
      var modalElement = document.getElementById('end-protest-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });

    $wire.on('closeEndProtestModal', () => {
      var modalElement = document.getElementById('end-protest-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });

    $wire.on('openContinueProtestModal', () => {
      var modalElement = document.getElementById('continue-protest-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });

    $wire.on('closeContinueProtestModal', () => {
      var modalElement = document.getElementById('continue-protest-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });
  </script>
@endscript
