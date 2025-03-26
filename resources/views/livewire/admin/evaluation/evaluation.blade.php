<div class="overflow-x-auto shadow-md sm:rounded-lg">
  {{-- <div wire:loading wire:target="nextEnvelope">
    @include('partial.page-loader')
  </div> --}}
  <style>
    .border-transition::after {
      transition: border-color 1s ease-in-out;
    }

    .envelope-transition {
      transition: background-color 1s ease-in-out, font-size 1s ease-in-out;
    }
  </style>
  <div class="py-4">
    <div class="px-5">
      <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Project No.</label>
      <span class="text-xs font-extrabold tei-text-accent">{{ $bidding->project_id }}</span>
    </div>
    <div class="px-5">
      <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Project Title</label>
      <span class="text-xs font-extrabold tei-text-accent">{{ $bidding->title }}</span>
    </div>
    <div class="px-5">
      <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Score Method</label>
      <span class="text-xs font-extrabold tei-text-accent">{{ ucwords($bidding->score_method) }} Based</span>
    </div>
    <div class="px-5">
      <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Sales</label>
      <span class="text-xs font-extrabold tei-text-accent">{{ $bidding->scrap ? 'Yes' : 'No' }}</span>
    </div>
  </div>

  <div class="py-10 px-10">
    <ol class="flex items-center w-full">
      @foreach ($envelopes as $envelope => $envelopeStep)
        <li
          class="border-transition text-white flex w-full items-center after:content-[''] after:w-full after:h-1 after:border-b after:border-4 after:inline-block {{ $envelopeStep < $bidding->progress->step ? 'after:border-orange-500' : '' }}">
          <span
            class="{{ $envelopeStep == $bidding->progress->step ? 'tei-text-secondary text-sm' : 'tei-text-accent text-xs' }} envelope-transition uppercase font-semibold absolute top-[25rem]">{{ $envelope }}</span>
          @if ($envelopeStep < $bidding->progress->step)
            <span wire:click="warning({{ $envelopeStep }})"
              class="cursor-pointer envelope-transition flex items-center justify-center w-10 h-10 rounded-full lg:h-12 lg:w-12 shrink-0 {{ $envelopeStep <= $bidding->progress->step ? 'tei-bg-secondary' : 'tei-bg-light' }}">
              @if ($envelopeStep <= $bidding->progress->step)
                <i class="fa-solid fa-envelope-open" wire:loading.remove wire:target="warning({{ $envelopeStep }})"></i>
                <x-loading-spinner color="var(--primary)" target="warning({{ $envelopeStep }})" />
              @else
                <i class="fa-regular fa-envelope"></i>
              @endif
            </span>
          @else
            <span
              class="envelope-transition flex items-center justify-center w-10 h-10 rounded-full lg:h-12 lg:w-12 shrink-0 {{ $envelopeStep <= $bidding->progress->step ? 'tei-bg-secondary' : 'tei-bg-light' }}">
              @if ($envelopeStep <= $bidding->progress->step)
                <i class="fa-solid fa-envelope-open"></i>
              @else
                <i class="fa-regular fa-envelope"></i>
              @endif
            </span>
          @endif

        </li>
      @endforeach
      {{-- <li
        class="flex w-full items-center text-white after:content-[''] after:w-full after:h-1 after:border-b after:border-orange-500 after:border-4 after:inline-block">
        <span class="flex items-center justify-center w-10 h-10 tei-bg-secondary rounded-full lg:h-12 lg:w-12 shrink-0">
          <i class="fa-solid fa-envelope"></i>
        </span>
      </li>
      <li
        class="text-white flex w-full items-center after:content-[''] after:w-full after:h-1 after:border-b after:border-4 after:inline-block">
        <span class="flex items-center justify-center w-10 h-10 tei-bg-light rounded-full lg:h-12 lg:w-12 shrink-0">
          <i class="fa-regular fa-envelope"></i>
        </span>
      </li>
      <li
        class="text-white flex w-full items-center after:content-[''] after:w-full after:h-1 after:border-b after:border-4 after:inline-block">
        <span class="flex items-center justify-center w-10 h-10 tei-bg-light rounded-full lg:h-12 lg:w-12 shrink-0">
          <i class="fa-regular fa-envelope"></i>
        </span>
      </li> --}}
      <li class="flex items-center">
        <span
          class="text-white flex items-center justify-center w-10 h-10 rounded-full lg:h-12 lg:w-12 shrink-0 {{ $final ? 'tei-bg-secondary' : 'tei-bg-light' }}">
          <span
            class="uppercase font-semibold absolute top-[25rem] envelope-transition {{ $final ? 'tei-text-secondary text-sm' : 'tei-text-accent text-xs' }}">Final</span>
          @if ($final)
            <i class="fa-solid fa-envelope-open"></i>
          @else
            <i class="fa-regular fa-envelope"></i>
          @endif
        </span>
      </li>
    </ol>

    <div class="text-center p-10 pb-5">
      @if (!$final)
        <button wire:click="openModal" wire:loading.remove wire:target="openModal"
          class="{{ $proceedButton ? 'bg-green-600 hover:bg-green-700 focus:ring-0 focus:outline-none hover:scale-110' : 'bg-gray-400' }} font-medium rounded-lg text-sm px-5 py-2.5 text-center text-white  transition-transform duration-300"
          {{ $proceedButton ? '' : 'disabled' }}>
          Proceed
        </button>
        <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading wire:target="openModal">
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
      @else
        @if ($bidFailure)
          <button wire:click="biddingFailure" wire:loading.remove wire:target="biddingFailure"
            class=" font-medium rounded-lg text-sm px-5 py-2.5 text-center text-white bg-red-600 hover:bg-red-700 focus:ring-0 focus:outline-none hover:scale-110 transition-transform duration-300">
            Bid Failure
          </button>
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
            wire:target="biddingFailure">
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
        @else
          <button wire:click="selectWinnerModal" wire:loading.remove wire:target="selectWinnerModal"
            class=" font-medium rounded-lg text-sm px-5 py-2.5 text-center text-white bg-green-600 hover:bg-green-700 focus:ring-0 focus:outline-none hover:scale-110 transition-transform duration-300">
            Proceed on Awarding
          </button>
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
            wire:target="selectWinnerModal">
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
        @endif

      @endif
    </div>
  </div>
  <hr>
  <div class="p-4 shadow-inner">

    @if ($final)
      <div wire:key="final" class=" p-2">
        @livewire('admin.evaluation.final-evaluation', ['biddingId' => $bidding->id])
      </div>
    @else
      @if ($livewireEnvelope == 'eligibility')
        <div wire:key="eligibility">
          @livewire('admin.evaluation.eligibility-evaluation', ['biddingId' => $bidding->id])
        </div>
      @elseif($livewireEnvelope == 'technical')
        <div wire:key="technical">
          @livewire('admin.evaluation.technical-evaluation', ['biddingId' => $bidding->id])
        </div>
      @elseif($livewireEnvelope == 'financial')
        <div wire:key="financial">
          @livewire('admin.evaluation.financial-evaluation', ['biddingId' => $bidding->id])
        </div>
      @endif
    @endif
  </div>

  {{-- proceed Modal --}}
  <div id="proceed-modal" tabindex="-1" data-modal-backdrop="static" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button" wire:click="closeProceedModal" wire:loading.remove wire:target="nextEnvelope"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        <div class="p-4 md:p-5 text-center" wire:loading.remove wire:target="nextEnvelope">
          <svg class="mx-auto mb-4 tei-text-primary w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          <h3 class="mb-5 text-lg font-normal tei-text-primary ">Are you sure you want to proceed to the next
            envelope?</h3>
          <button type="button"
            class="text-white bg-green-600 hover:bg-green-900 focus:outline-none  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
            wire:click="nextEnvelope">
            Confirm
          </button>
          <button wire:click="closeProceedModal"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10">
            Cancel</button>
        </div>

        <div class="bg-white w-full rounded-md px-32 py-14" wire:loading wire:target="nextEnvelope">
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
  {{-- END proceed Modal --}}

  {{-- award Modal --}}
  <div id="award-modal" tabindex="-1" data-modal-backdrop="static" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-4xl max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <div class="flex items-center justify-between p-2 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">

          </h3>
          <button type="button" wire:click="closeAwardModal"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <div class="p-4">
          <h3 class="mb-2 text-lg font-normal tei-text-primary ">Select winning bidder.</h3>
          <p class="text-xs tei-text-accent">Lists of Vendor(s) who passed the Project Bidding.</p>

          <div class="py-5">
            <table class="w-full text-xs tei-text-accent uppercase text-left shadow-lg rounded-lg">
              <thead class="tei-bg-primary text-white">
                <tr>
                  <th class="px-6 py-2">
                    Rank
                  </th>
                  <th class="px-6 py-2">
                    Vendor Id
                  </th>
                  <th class="px-6 py-2">
                    Vendor Name
                  </th>
                </tr>
              </thead>
              <tbody>
                @if ($vendorResults)
                  @foreach ($vendorResults as $vendor)
                    <tr wire:click="selectBidder({{ $vendor->vendor_id }})"
                      class="hover:shadow-lg hover:bg-neutral-300 border-b cursor-pointer {{ $tempWinner && $tempWinner->vendor_id == $vendor->vendor_id ? 'tei-bg-light' : '' }}">
                      <td class="px-6 py-2 normal-case">
                        {{ $vendor->rank }}
                      </td>
                      <td class="px-6 py-2">
                        {{ $vendor->vendor->id }}
                      </td>
                      <td class="px-6 py-2">
                        {{ $vendor->vendor->name }}
                      </td>
                    </tr>
                  @endforeach
                @endif

              </tbody>
            </table>
            @if ($protest)
              <div class="pt-5">
                <div>
                  <label class="text-sm tei-text-secondary uppercase font-semibold">Previous Winning Bidder:</label>
                  <span
                    class="text-xs tei-text-accent uppercase font-semibold pl-4">{{ $prevWinningBidder ? $prevWinningBidder->name : '' }}</span>
                  @error('tempWinner')
                    <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                      {{ $message }}
                    </p>
                  @enderror
                </div>

                <div class="mt-4">
                  <label class="text-sm tei-text-secondary uppercase font-semibold block">Vendor(s) who
                    protest:</label>
                  @foreach ($vendorsProtest as $vendor)
                    <span
                      class="text-xs tei-text-accent uppercase font-semibold pl-4 block">{{ $vendor ? $vendor->name : '' }}</span>
                  @endforeach

                </div>
                <div class="mt-4 w-1/2">
                  <label class="block text-sm tei-text-secondary uppercase font-semibold mb-2">Remarks:</label>
                  <textarea id="message" rows="4"
                    class="tei-text-accent {{ $errors->has('remarks') ? ' border border-red-500  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 focus:ring-gray-500 focus:border-gray-500 ' }} block p-2.5 w-full text-sm rounded-lg border"
                    placeholder="Enter Remarks" wire:model="remarks"></textarea>
                  @error('remarks')
                    <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                      {{ $message }}
                    </p>
                  @enderror
                </div>

                @if ($rejectVendor && $rejectVendor == $tempWinner->vendor_id)
                  <div class="mb-2">
                    <span class="text-xs uppercase font-semibold text-yellow-500 italic">Warning! This winner has
                      already
                      been
                      rejected.</span>
                  </div>
                  <div>
                    <label class="block text-sm tei-text-secondary uppercase font-semibold mb-2">Approval
                      Remarks:</label>
                    <span class="text-xs uppercase font-semibold text-green-500 italic">{{ $rejectRemarks }}</span>
                  </div>
                @endif
              </div>
            @else
              <div class="pt-5">
                <label class="text-sm tei-text-secondary uppercase font-semibold">Winning Bidder:</label>
                <span
                  class="text-xs tei-text-accent uppercase font-semibold pl-4">{{ $tempWinner ? $tempWinner->vendor->name : '' }}</span>
                @error('tempWinner')
                  <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                    {{ $message }}
                  </p>
                @enderror

                @if ($rejectVendor && $rejectVendor == $tempWinner->vendor_id)
                  <div class="mb-2">
                    <span class="text-xs uppercase font-semibold text-yellow-500 italic">Warning! This winner has
                      already
                      been
                      rejected.</span>
                  </div>
                  <div>
                    <label class="block text-sm tei-text-secondary uppercase font-semibold mb-2">Approval
                      Remarks:</label>
                    <span class="text-xs uppercase font-semibold text-green-500 italic">{{ $rejectRemarks }}</span>
                  </div>
                @endif
              </div>
              {{-- @if ($bidding->winnerApproval || ($tempWinner && $tempWinner->rank != 1)) --}}
              <div class="mt-4 w-1/2">
                <label class="block text-sm tei-text-secondary uppercase font-semibold mb-2">Remarks:</label>
                <textarea id="message" rows="4"
                  class="{{ $errors->has('remarks') ? ' border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} block p-2.5 w-full text-sm rounded-lg border"
                  placeholder="Enter Remarks" wire:model="remarks"></textarea>
                @error('remarks')
                  <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                    {{ $message }}
                  </p>
                @enderror
              </div>
              {{-- @endif --}}
            @endif
          </div>
        </div>
        <div class="flex items-center justify-center p-4 md:p-5 border-t rounded-t">
          <button type="button" wire:loading.remove wire:target="confirmWinnerBidder"
            class="text-white bg-green-600 hover:bg-green-900 focus:outline-none  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
            wire:click="confirmWinnerBidder">
            Confirm
          </button>
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
            wire:target="confirmWinnerBidder">
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
          <button wire:click="closeAwardModal"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10 focus:ring-4 focus:ring-gray-100">
            Cancel</button>
        </div>
        {{-- <div class="bg-white w-full rounded-md px-32 py-14">
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
        </div> --}}

      </div>
    </div>
  </div>
  {{-- END award Modal --}}

  {{-- proceed Modal --}}
  <div id="confirm-modal" tabindex="-1" data-modal-backdrop="static" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button" wire:click="closeConfirmModal"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        <div class="p-4 md:p-5 text-center" wire:loading.remove wire:target="nextEnvelope">
          <svg class="mx-auto mb-4 tei-text-primary w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          <h3 class="mb-5 text-lg font-normal tei-text-primary ">Are you sure you want to proceed and award the bid to
            <span class="font-semibold underline">{{ $tempWinner ? $tempWinner->vendor->name : '' }}</span>?
          </h3>
          <button type="button" wire:loading.remove wire:target="confirmWinner"
            class="text-white bg-green-600 hover:bg-green-900 focus:outline-none  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
            wire:click="confirmWinner">
            Confirm
          </button>
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
            wire:target="confirmWinner">
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
          <button wire:click="closeConfirmModal" wire:loading.attr="disabled" wire:target="confirmWinner"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10 focus:ring-4 focus:ring-gray-100">
            Cancel</button>
        </div>

        <div class="bg-white w-full rounded-md px-32 py-14" wire:loading wire:target="nextEnvelope">
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
  {{-- END proceed Modal --}}

  {{-- Warning Modal --}}
  <div id="warning-modal" tabindex="-1" data-modal-backdrop="static" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button" wire:click="closeWarningModal"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        <div class="p-4 md:p-5 text-center" wire:loading.remove wire:target="nextEnvelope">
          <svg class="mx-auto mb-4 tei-text-primary w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          <h3 class="mb-5 text-lg font-normal tei-text-primary ">Are you sure you want to go back to this envelope?
          </h3>
          <button type="button" wire:loading.remove wire:target="stepBack"
            class="text-white bg-green-600 hover:bg-green-900 focus:outline-none  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
            wire:click="stepBack">
            Confirm
          </button>
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
            wire:target="stepBack">
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
          <button wire:click="closeWarningModal"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10 focus:ring-4 focus:ring-gray-100">
            Cancel</button>
        </div>

        <div class="bg-white w-full rounded-md px-32 py-14" wire:loading wire:target="nextEnvelope">
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
  {{-- END warning Modal --}}

</div>
@script
  <script>
    $wire.on('openProceedModal', () => {
      var modalElementOpen = document.getElementById('proceed-modal');
      var modalOpen = new Modal(modalElementOpen, {
        backdrop: 'static'
      });
      modalOpen.show();
    });

    $wire.on('closeProceedModal', () => {
      var modalElement = document.getElementById('proceed-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });

    $wire.on('openAwardModal', () => {
      var modalElementOpen = document.getElementById('award-modal');
      var modalOpen = new Modal(modalElementOpen, {
        backdrop: 'static'
      });
      modalOpen.show();
    });

    $wire.on('closeAwardModal', () => {
      var modalElement = document.getElementById('award-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });

    $wire.on('openConfirmModal', () => {
      var modalElementOpen = document.getElementById('confirm-modal');
      var modalOpen = new Modal(modalElementOpen, {
        backdrop: 'static'
      });
      modalOpen.show();
    });

    $wire.on('closeConfirmModal', () => {
      var modalElement = document.getElementById('confirm-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });

    $wire.on('openWarningModal', () => {
      var modalElementOpen = document.getElementById('warning-modal');
      var modalOpen = new Modal(modalElementOpen, {
        backdrop: 'static'
      });
      modalOpen.show();
    });

    $wire.on('closeWarningModal', () => {
      var modalElement = document.getElementById('warning-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });
  </script>
@endscript
