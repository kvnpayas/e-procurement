<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
  <table class="w-full text-sm text-left rtl:text-right text-gray-500">
    <caption class="p-5 text-lg font-semibold text-left rtl:text-right tei-text-secondary bg-white ">
      Bid Result Lists
      <p class="mt-1 text-sm font-normal text-gray-500 ">
        The lists of all vendor's project bid results.
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
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between w-96">
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
          Sales
        </th>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Result</span>
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
          <th scope="row" class="px-6 py-4">
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
            {{ $bidding->scrap ? 'Yes' : 'No' }}
          </td>
          <td class="px-6 py-4">
            @php
              // $vendor = $bidding->vendors->where('vendor_id', Auth::user()->id)
              //     ->first();
              //     dd(Auth::user()->id);
              $vendorStatus = $bidding->pivot->status;
            @endphp
            <span
              class="
                    {{ $vendorStatus == 'Winning Bidder' ? 'text-green-500' : '' }} 
                    {{ $vendorStatus == 'Lost' || $vendorStatus == 'Cancel Bidding' || $vendorStatus == 'Bid Failure'  || $vendorStatus == 'Unsuccessful Bidding' || $vendorStatus == 'Unpublished Bididng' ? 'text-red-500' : '' }}
                    fw-bold text-truncate">{{ $vendorStatus }}
            </span>
          </td>
          <td class="px-6 py-4">
            {{-- @if ($vendorStatus == 'Lost')
              @php
                $awardDate = $bidding->winnerApproval->awarded_date;
                $protestDeadlineDate = $this->protestDeadlineDate($awardDate);
                $protest = $bidding->protest
                    ? $bidding->protest->vendors->where('pivot.vendor_id', Auth::user()->id)->first()
                    : null;
              @endphp
              @if ($protest && $protest->pivot->status == 'Pending')
                <button wire:click.prevent="viewProtest({{ $bidding->id }})"
                  class="hover:scale-110 transition-transform duration-300 mr-4 bg-green-500 rounded-md px-2 py-1 text-white text-xs">View
                  Protest</button>
              @else
                @if ($protestDeadlineDate > now())
                  <button wire:click.prevent="protestDate({{ $bidding->id }})"
                    class="hover:scale-110 transition-transform duration-300 mr-4 bg-yellow-500 rounded-md px-2 py-1 text-white text-xs">Protest</button>
                @endif

              @endif
            @endif --}}
          </td>
        </tr>
      @empty
        <tr class="bg-white border-b">
          <th scope="row" colspan="100" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center">
            <span class="font-black text-lg tei-text-primary">No bid has been accepted yet.</span>
          </th>
        </tr>
      @endforelse
    </tbody>
  </table>
  {{ $biddings->links('livewire.layout.pagination') }}
  {{-- <x-action-message class="me-3" on="vendor-message">
    {{ __($messageAction) }}
  </x-action-message> --}}

  {{-- protest Modal --}}
  <div id="protest-date-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-lg max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button" wire:click="closeProtestDateModal"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        <div class="p-4 md:p-5 text-center">
          <svg class="mx-auto mb-4 text-yellow-500 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          @if ($protestBid)
            <p class="text-xs tei-text-accent italic">Please be aware that there is a deadline for filing protests, and
              you must submit your protest within that period.</p>
            <h3 class="mb-5 mt-5 font-semibold tei-text-primary uppercase">Project title: <span
                class="text-xs tei-text-accent">{{ $protestBid->title }}</span></h3>
            <h3 class="mb-5 mt-5 font-semibold tei-text-primary uppercase">Protest Deadline Date: <span
                class="text-xs tei-text-accent">{{ date('F j,Y @ h:i A', strtotime($protestDeadline)) }}</span></h3>
            <div class="mb-5">
              <textarea id="message" rows="8"
                class="text-gray-900 {{ $errors->has('protestMessage') ? ' border border-red-500  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 focus:ring-gray-500 focus:border-gray-500 ' }} block p-2.5 w-full text-sm rounded-lg border"
                placeholder="Message" wire:model="protestMessage"></textarea>
              @error('protestMessage')
                <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                  {{ $message }}
                </p>
              @enderror
            </div>
          @endif
          <button data-modal-hide="hold-modal" type="button"
            class="text-white bg-green-500 hover:bg-green-600 focus:outline-none  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
            wire:click= "nextProcess">
            Next
          </button>
          <button wire:click="closeProtestDateModal" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10 focus:ring-4 focus:ring-gray-100">
            Close</button>

        </div>
      </div>
    </div>
  </div>
  {{-- END protest Modal --}}

  {{-- Preview Protest Modal --}}
  <div id="protest-preview-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-lg max-h-full">
      <div class="relative bg-white rounded-lg shadow" wire:loading.remove wire:target="fileProtest">
        <button type="button" wire:click="closePreviewProtestModal"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        <div class="p-4 md:p-5 text-center">
          <svg class="mx-auto mb-4 text-yellow-500 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          @if ($protestBid)
            <p class="text-xs tei-text-accent italic">Please review your protest message</p>
            <h3 class="mb-5 mt-5 font-semibold tei-text-primary uppercase">Project title: <span
                class="text-xs tei-text-accent">{{ $protestBid->title }}</span></h3>
            <h3 class="mb-5 mt-5 font-semibold tei-text-primary uppercase">Protest Deadline Date: <span
                class="text-xs tei-text-accent">{{ date('F j,Y @ h:i A', strtotime($protestDeadline)) }}</span></h3>
            <div class="mb-5 text-justify border-2 p-5 rounded-md">
              @if ($paragraphs)
                @foreach ($this->paragraphs as $paragraph)
                  <p class="text-xs font-semibold tei-text-accent">{{ $paragraph }}</p>
                @endforeach
              @endif

            </div>
          @endif
          <button data-modal-hide="hold-modal" type="button"
            class="text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
            wire:click= "fileProtest">
            File Protest
          </button>
          <button wire:click="closePreviewProtestModal" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10 focus:ring-4 focus:ring-gray-100">
            Cancel</button>

        </div>
      </div>
      <div class="bg-white w-full rounded-md px-32 py-14" wire:loading wire:target="fileProtest">
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
  {{-- END Preview Protest Modal --}}

  {{-- View protest Modal --}}
  <div id="protest-view-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-lg max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button" wire:click="closeProtestViewModal"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        <div class="p-4 md:p-5">
          @if ($viewProtestBid)
            {{-- <p class="text-xs tei-text-accent italic">Please be aware that there is a deadline for filing protests, and
              you must submit your protest within that period.</p> --}}
            <h3 class="mb-5 mt-5 font-semibold tei-text-primary uppercase">Project title: <span
                class="text-xs tei-text-accent">{{ $viewProtestBid->title }}</span></h3>
            <h3 class="mb-5 mt-5 font-semibold tei-text-primary uppercase">Status: <span
                class="text-xs 
                {{ $viewVendorStatus == 'Pending' ? 'text-yellow-500' : '' }}
                {{ $viewVendorStatus == 'Cancelled' ? 'text-red-500' : '' }}
                ">{{ $viewVendorStatus }}</span>
            </h3>
            {{-- <h3 class="mb-5 mt-5 font-semibold tei-text-primary uppercase">Protest Deadline Date: <span
                class="text-xs tei-text-accent">{{ date('F j,Y @ h:i A', strtotime($protestDeadline)) }}</span></h3> --}}
            <div class="mb-5">
              <h3 class="mb-2 mt-5 font-semibold tei-text-primary uppercase block">Message</h3>
              <div class="mb-5 text-justify border-2 p-5 rounded-md">
                @if ($protestVendorMessage)
                  @foreach ($protestVendorMessage as $message)
                    <p class="text-xs font-semibold tei-text-accent">{{ $message }}</p>
                  @endforeach
                @endif

              </div>
            </div>
          @endif
          <div class="text-center">
            <button wire:click="closeProtestViewModal" type="button"
              class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10 focus:ring-4 focus:ring-gray-100">
              Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  {{-- END View protest Modal --}}
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
    $wire.on('openProtestDateModal', () => {
      var modalElement = document.getElementById('protest-date-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });

    $wire.on('closeProtestDateModal', () => {
      var modalElement = document.getElementById('protest-date-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });

    $wire.on('openPreviewProtestModal', () => {
      var modalElement = document.getElementById('protest-preview-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });

    $wire.on('closePreviewProtestModal', () => {
      var modalElement = document.getElementById('protest-preview-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });

    $wire.on('openProtestViewModal', () => {
      var modalElement = document.getElementById('protest-view-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });

    $wire.on('closeProtestViewModal', () => {
      var modalElement = document.getElementById('protest-view-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });
  </script>
@endscript
