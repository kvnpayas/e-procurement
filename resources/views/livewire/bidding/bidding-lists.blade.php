<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
  <table class="w-full text-sm text-left rtl:text-right text-gray-500">
    <caption class="p-5 text-lg font-semibold text-left rtl:text-right tei-text-secondary bg-white ">
      Bid List and Envelopes
      <p class="mt-1 text-sm font-normal text-gray-500 ">
        The lists of all vendor's project bid.
      </p>
      <div class="flex pt-5">
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
    </caption>
    <thead class="text-xs text-gray-700 uppercase tei-bg-light truncate">
      <tr>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Project No.</span>
            <button wire:click.prevent="selectedFilters('project_id')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'project_id' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'project_id' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button>
          </div>
        </th>
        <th scope="col" class="px-6 py-3 w-96">
          <div class="flex justify-between">
            <span>Project Name</span>
            <button wire:click.prevent="selectedFilters('title')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'title' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'title' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button>
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Project Start Date</span>
            <button wire:click.prevent="selectedFilters('start_date')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'start_date' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'start_date' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button>
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Project End Date</span>
            <button wire:click.prevent="selectedFilters('deadline_date')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'deadline_date' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'deadline_date' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button>
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Reserved Price(PHP)</span>
            <button wire:click.prevent="selectedFilters('reserved_price')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'reserved_price' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'reserved_price' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button>
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          Sales
        </th>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Status</span>
            <button wire:click.prevent="selectedFilters('projectbid_vendors.status')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'projectbid_vendors.status' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'projectbid_vendors.status' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button>
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          Action
        </th>
      </tr>
    </thead>
    <tbody class="text-xs">
      @forelse ($biddings as $bidding)
        <tr class="bg-white border-b font-extrabold">
          <th scope="row" class="px-6 py-4 tei-text-secondary whitespace-nowrap ">
            {{ $bidding->project_id }}
          </th>
          <th scope="row" class="px-6 py-4 ">
            {{ $bidding->title }}
          </th>
          <td class="px-6 py-4">
            {{ date('F j, Y', strtotime($bidding->start_date)) }}
          </td>
          <td class="px-6 py-4">
            @if ($bidding->extend_date)
              {{ date('F j,Y @ h:i A', strtotime($bidding->extend_date)) }}
            @else
              {{ date('F j,Y @ h:i A', strtotime($bidding->deadline_date)) }}
            @endif
          </td>
          <td class="px-6 py-4">
            @if ($bidding->reflect_price)
              {{ $bidding->reserved_price ? 'PHP ' . number_format($bidding->reserved_price, 2) : 'No Cieling' }}
            @else
              {{-- --------
              <span data-tooltip-target="tooltip-light-{{ $bidding['project_bid']['id'] }}" data-tooltip-style="light">
                <i class="fa-solid fa-eye-slash"></i>
              </span>
              <div id="tooltip-light-{{ $bidding['project_bid']['id'] }}" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white tei-bg-secondary border border-gray-200 rounded-lg shadow-sm opacity-0 tooltip">
                Reserved price is hidden
                <div class="tooltip-arrow" data-popper-arrow></div>
              </div> --}}
            @endif
          </td>
          <td class="px-6 py-4">
            {{ $bidding->scrap ? 'Yes' : 'No' }}
          </td>
          <td class="px-6 py-4">
            @php
              $vendor = Auth::user()->biddings->where('id', $bidding->id)->first();
              $vendorStatus = $vendor->pivot->status;
            @endphp
            {{-- <span class="font-extrabold text-green-600">{{ $vendorStatus }}</span> --}}
            <span
              class="
                    {{ $vendorStatus == 'Joined' ? 'text-green-500' : '' }} 
                    {{ $vendorStatus == 'For Evaluation' || $vendorStatus == 'Under Evaluation' ? 'text-sky-500' : '' }}
                     {{ $vendorStatus == 'On Hold' ? 'text-yellow-500' : '' }}
                    {{ $vendorStatus == 'Cancel Bidding' || $vendorStatus == 'Bid Failure' ? 'text-red-500' : '' }}
                    fw-bold text-truncate">{{ $vendorStatus }}
            </span>
          </td>
          <td class="px-6 py-4">
            <span id="icon.{{ $bidding->id }}" class="cursor-pointer">
              <i class="fa-solid fa-circle-plus text-green-800"></i> show
              more
            </span>
          </td>
        </tr>
        <tr id="biddingDetails.{{ $bidding->id }}" class="bidding-details details-hide shadow-inner tei-bg-light"
          wire:ignore.self>
          <td colspan="100">
            <div class="displayNone more-details w-full gap-4 grid grid-cols-1 sm:grid-cols-4 px-10" wire:ignore.self>
              <div class="border-e-2 pt-5">
                <label for="" class="font-extrabold pb-5 tei-text">Instruction Details</label>
                @if ($showFullText[$bidding->id])
                  <div>
                    {!! nl2br(e($bidding->instruction_details)) !!}
                  </div>
                  <div>
                    @if (strlen($bidding->instruction_details) > 100)
                      <button class="text-xs uppercase font-extrabold tei-text-secondary"
                        wire:click="toggleText({{ $bidding->id }})">Read
                        Less</button>
                    @endif
                  </div>
                @else
                  <div>
                    {!! nl2br(e(Str::limit($bidding->instruction_details, 100))) !!}
                  </div>
                  <div>
                    @if (strlen($bidding->instruction_details) > 100)
                      <button class="text-xs uppercase font-extrabold tei-text-secondary"
                        wire:click="toggleText({{ $bidding->id }})">Read
                        More</button>
                    @endif
                  </div>
                @endif
              </div>
              <div class="border-e-2 pt-5">
                <label for="" class="font-extrabold pb-5 tei-text">Instruction Attachment</label>
                <div class="pt-3">
                  @if (!$bidding->projectBidFiles->isEmpty())
                    @foreach ($bidding->projectBidFiles as $file)
                      <button wire:click.prevent="showFile('{{ $file->file_name }}')"
                        class="block mb-5 hover:scale-110 transition-transform duration-300 bg-green-600 rounded-md px-2 py-1 text-white text-xs"><i
                          class="fa-solid fa-file-pdf text-xs"></i> {{ $file->file_name }}</button>
                    @endforeach
                  @endif
                </div>
              </div>
              <div class="pt-5 border-e-2">
                <label for="" class="font-extrabold pb-5 tei-text">Envelope(s) Status</label>
                @if ($bidding->status == 'Bid Published' || strpos($bidding->status, 'Publication Extended') === 0)
                  <div class="grid gap-4 grid-cols-3 pt-3">
                    <div>
                      @if ($bidding->eligibility)
                        @php
                          $elStatus = $bidding
                              ->bidEnvelopeStatus()
                              ->where('envelope', 'eligibility')
                              ->where('vendor_id', Auth::user()->id)
                              ->first();
                        @endphp
                        <span>Eligibility</span>
                        @if ($elStatus && $elStatus->status)
                          <span class="text-green-700 fw-bolder"><i class="fa-solid fa-circle-check"></i></span>
                        @else
                          <span class="text-red-700 fw-bolder"><i class="fa-solid fa-circle-xmark"></i></span>
                        @endif
                      @endif
                    </div>
                    <div>
                      @if ($bidding->technical)
                        @php
                          $techStatus = $bidding
                              ->bidEnvelopeStatus()
                              ->where('envelope', 'technical')
                              ->where('vendor_id', Auth::user()->id)
                              ->first();
                        @endphp
                        <span>Technical</span>
                        @if ($techStatus && $techStatus->status)
                          <span class="text-green-700 fw-bolder"><i class="fa-solid fa-circle-check"></i></span>
                        @else
                          <span class="text-red-700 fw-bolder"><i class="fa-solid fa-circle-xmark"></i></span>
                        @endif
                      @endif
                    </div>
                    <div>
                      @if ($bidding->financial)
                        @php
                          $finStatus = $bidding
                              ->bidEnvelopeStatus()
                              ->where('envelope', 'financial')
                              ->where('vendor_id', Auth::user()->id)
                              ->first();
                        @endphp
                        <span>Financial</span>
                        @if ($finStatus && $finStatus->status)
                          <span class="text-green-700 fw-bolder"><i class="fa-solid fa-circle-check"></i></span>
                        @else
                          <span class="text-red-700 fw-bolder"><i class="fa-solid fa-circle-xmark"></i></span>
                        @endif
                      @endif
                    </div>
                  </div>
                  <div class="mt-2">
                    @php
                      $envelopeStatus = $bidding->bidEnvelopeStatus
                          ->where('vendor_id', Auth::user()->id)
                          ->pluck('status')
                          ->toArray();

                      $bidComplete = $this->vendorStatus->where('bidding_id', $bidding->id)->first();
                      // dd($envelopeStatus);
                    @endphp
                    @if (in_array(false, $envelopeStatus, false))
                      <span>Please complete all the envelopes.</span>
                    @else
                      @if ($bidComplete->complete)
                        <span class="text-green-700">The Envelope(s) has been submitted.</span>
                      @else
                        <div class="flex justify-center">
                          <div wire:loading.delay wire:target="tagComplete({{ $bidding->id }})" class="mt-5">
                            <div class="loading loading-main">
                              <span></span>
                              <span></span>
                              <span></span>
                              <span></span>
                              <span></span>
                            </div>
                          </div>
                        </div>
                        <button wire:loading.remove
                          class="bg-green-700 px-5 py-1 rounded-md text-white hover:scale-110 transition-transform duration-300 font-semibold text-xs"
                          wire:click.prevent="tagComplete({{ $bidding->id }})">Submit Bid</button>
                      @endif
                    @endif
                  </div>
                @elseif($bidding->status == 'For Evaluation')
                  <div class="pt-3">
                    <p>
                      Bidding date is already met. Please wait for the evaluation.
                    </p>
                  </div>
                @endif
              </div>
              <div class="pt-5 flex gap-4">
                <div>
                  <label for="" class="font-extrabold pb-5 tei-text">Envelope(s)</label>
                  @if ($bidding->status == 'Bid Published' || strpos($bidding->status, 'Publication Extended') === 0)
                    <div class="pt-3">

                      {{-- @if (!$bidComplete->complete) --}}
                      <p>
                        Click to open envelope(s).
                      </p>

                      <div class="mt-4">
                        <button wire:click.prevent="opeEnvelopes({{ $bidding->id }})"
                          class="tei-btn-secondary px-5 py-1 rounded-md text-white transition ease-in-out duration-150 font-semibold text-xs">Open
                          Envelope(s)</button>
                      </div>
                      {{-- @else
                        <div class="mt-4">
                          <button
                            class="bg-gray-400 px-5 py-1 rounded-md text-white transition ease-in-out duration-150 font-semibold text-xs"
                            disabled>Open
                            Envelopes</button>
                        </div>
                      @endif --}}

                    </div>
                  @else
                    <div class="mt-4">
                      <button wire:click.prevent="openSummary({{ $bidding->id }})"
                        class="tei-btn-secondary px-5 py-1 rounded-md text-white transition ease-in-out duration-150 font-semibold text-xs">Bid Summary</button>
                    </div>
                  @endif
                </div>
                @if ($bidding->status == 'Bid Published' || strpos($bidding->status, 'Publication Extended') === 0)
                  <div>
                    <label for="" class="font-extrabold pb-5 tei-text">Bid Bulletin</label>
                    <p class="mt-3">
                      Click to open Bid Bulletin.
                    </p>
                    <div class="mt-4">
                      <button wire:click.prevent="openBulletin({{ $bidding->id }})"
                        class="{{ $bidding->bulletins->count() == 0 ? 'bg-gray-500' : 'bg-green-500 hover:bg-green-600' }}  px-5 py-1 rounded-md text-white transition ease-in-out duration-150 font-semibold text-xs"
                        {{ $bidding->bulletins->count() == 0 ? 'disabled' : '' }}>Open
                        Bid Bulletin</button>
                    </div>
                  </div>
                @endif
              </div>
            </div>
          </td>
        </tr>
      @empty
        <tr class="bg-white border-b">
          <th scope="row" colspan="100"
            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center">
            <span class="font-black text-lg tei-text-primary">No bid has been accepted yet.</span>
          </th>
        </tr>
      @endforelse
    </tbody>
  </table>
  {{ $biddings->links('livewire.layout.pagination') }}
  <x-action-message class="me-3" on="vendor-message">
    {{ __($messageAction) }}
  </x-action-message>

  {{-- File Modal --}}
  <div id="view-file" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-7xl max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
          wire:click.prevent="closeFileModal">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        @if ($bidFile)
          <div class="p-4 md:p-5 text-center">
            <div class="my-4">
              <label for="title"
                class=" mr-2 text-2xl font-extrabold tei-text-secondary block">{{ $bidFile }}</label>
              {{-- <span class="text-xs font-extrabold tei-text-accent">asdsa</span> --}}
            </div>
            <div class=" flex justify-center">
              <iframe src="{{ $bidFileAttachment }}" frameborder="1" width="1000" height="850"></iframe>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
  {{-- END File Modal --}}
</div>

@section('page-script')
  <script>
    document.addEventListener("click", (e) => {
      const elementId = e.target.id;
      const id = elementId.split('.')[1]
      const detailId = document.getElementById('biddingDetails.' + id)
      if (detailId.classList.contains('details-hide')) {
        detailId.classList.add('details-show')
        detailId.classList.remove('details-hide')
        setTimeout(() => {
          detailId.querySelector('.more-details').classList.remove('displayNone')
        }, 300);
      } else {
        detailId.classList.remove('details-show')
        detailId.classList.add('details-hide')
        setTimeout(() => {
          detailId.querySelector('.more-details').classList.add('displayNone')
        }, 200);
      }

    })
  </script>
@endsection

@script
  <script>
    $wire.on('openFileModal', () => {
      var modalElement = document.getElementById('view-file');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });

    $wire.on('closeFileModal', () => {
      var modalElement = document.getElementById('view-file');
      var modal = new Modal(modalElement);
      modal.hide();
    });
  </script>
@endscript
