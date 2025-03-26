<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
  <table class="w-full text-sm text-left rtl:text-right text-gray-500">
    <caption class="p-5 text-lg font-semibold text-left rtl:text-right tei-text-secondary bg-white ">
      Bid Invitation
      {{-- <p class="mt-1 text-sm font-normal text-gray-500 ">The lists of invitations to Project on projects.
      </p> --}}
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
      <div class="mt-5">
        <p class="mt-1 text-sm font-normal text-gray-500 ">
          Upon accepting the bidding invitation, please proceed to the 'Bid Lists' menu for the next step.
        </p>
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
        {{-- <th scope="col" class="px-6 py-3">
          Response Date
        </th> --}}
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
          {{-- <td class="px-6 py-4">
            @if ($bidding['project_bid']['reflect_price'])
              PHP {{ number_format($bidding['project_bid']['reserved_price'], 2) }}
            @else
              --------
              <span data-tooltip-target="tooltip-light-{{ $bidding['project_bid']['id'] }}" data-tooltip-style="light">
                <i class="fa-solid fa-eye-slash"></i>
              </span>
              <div id="tooltip-light-{{ $bidding['project_bid']['id'] }}" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white tei-bg-secondary border border-gray-200 rounded-lg shadow-sm opacity-0 tooltip">
                Reserved price is hidden
                <div class="tooltip-arrow" data-popper-arrow></div>
              </div>
            @endif
          </td> --}}
          {{-- <td class="px-6 py-4">
            {{ date('F j, Y', strtotime($bidding['project_bid']['response_date'])) }}
          </td> --}}
          <td class="px-6 py-4">
            @php
              $vendor = Auth::user()->biddings->where('id', $bidding->id)->first();
              $vendorStatus = $vendor->pivot->status;
            @endphp
            <span
              class="font-extrabold {{ $vendorStatus == 'Invited' ? 'text-green-600' : 'text-red-600' }}">{{ ucfirst($vendorStatus) }}</span>
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
              <div>
                <label for="" class="font-extrabold pb-5 tei-text">Instruction Details</label>
                {{-- <div class="overflow-auto pt-3">
                  {!! nl2br(e($bidding->instruction_details ? $bidding->instruction_details : 'N/A')) !!}
                </div> --}}
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
              <div>
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
              <div>
                <label for="" class="font-extrabold pb-5 tei-text">Sales</label>
                <div class="pt-3">{{ $bidding->scrap ? 'Yes' : 'No' }}</div>
              </div>
              <div>
                <label for="" class="font-extrabold pb-5 tei-text">Action</label>
                <div class="pt-3 gap-2 flex">
                  @if ($vendor->pivot->confirm == null)
                    @if (strtotime(now()) > ($bidding->extend_date ? strtotime($bidding->extend_date) : strtotime($bidding->deadline_date)))
                      <span class="text-red-600 font-extrabold">Sorry, you already missed the response date for this
                        bid.</span>
                    @else
                      <button wire:loading.remove wire:target="acceptBid({{ $bidding }})"
                        class="bg-green-600 px-5 py-1.5 rounded-md text-white hover:bg-green-700 font-semibold text-xs hover:scale-110 transition-transform duration-300"
                        wire:click.prevent="acceptBid({{ $bidding }})">Accept</button>
                      <div class="w-20 rounded-lg bg-white flex justify-center p-4 shadow-xl" wire:loading
                        wire:target="acceptBid({{ $bidding }})">
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
                      <button wire:loading.remove wire:target="declineBid({{ $bidding }})"
                        class="bg-red-600 px-5 py-1.5 rounded-md text-white hover:bg-red-700 hover:scale-110 transition-transform duration-300 font-semibold text-xs"
                        wire:click.prevent="declineBid({{ $bidding }})">decline</button>
                      <div class="w-20 rounded-lg bg-white flex justify-center p-4 shadow-xl" wire:loading
                        wire:target="declineBid({{ $bidding }})">
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
                  @else
                    @if ($vendor->pivot->confirm == false)
                      <span class=" font-extrabold">You have already declined this bid. If you want to join this bid,
                        please click the accept button.</span>
                      <div class="mt-2">
                        <button wire:loading.remove wire:target="acceptBid({{ $bidding }})"
                          class="bg-green-600 px-5 py-1.5 rounded-md text-white hover:bg-green-700 hover:scale-110 transition-transform duration-300 font-semibold text-xs"
                          wire:click.prevent="acceptBid({{ $bidding }})">Accept</button>
                        <div class="w-20 rounded-lg bg-white flex justify-center p-4 shadow-xl" wire:loading
                          wire:target="acceptBid({{ $bidding }})">
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
                    @else
                      @if (strtotime(now()) > strtotime($bidding->deadline_date))
                        <span class="text-red-600 font-extrabold">Sorry, you already missed the response date for this
                          bid.</span>
                      @endif
                    @endif
                  @endif
                </div>
              </div>
            </div>
          </td>
        </tr>
      @empty
        <tr class="bg-white border-b">
          <th scope="row" colspan="100"
            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center">
            <span class="font-black text-lg tei-text-primary">No Project Bidding Invitation.</span>
          </th>
        </tr>
      @endforelse
    </tbody>
  </table>

  {{ $biddings->links('livewire.layout.pagination') }}

  {{-- <x-action-message class="me-3" on="invitation-message">
    {{ __($message) }}
  </x-action-message> --}}

  {{-- Accept Modal --}}
  <div id="accept-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
          wire:click="closeAcceptModal">
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
          <h3 class="mb-5 text-lg font-normal tei-text-primary ">Are you sure you want to accept this bidding?</h3>
          <h4 class="mb-5 text-lg font-normal tei-text-primary ">Bidding Title: {{ $biddingTitle }}</h4>
          <button type="button" wire:loading.remove wire:target="accept"
            class="text-white bg-green-600 hover:bg-green-900 focus:outline-none  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
            wire:click.prevent="accept">
            Accept
          </button>
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 shadow-xl" wire:loading
            wire:target="accept">
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
          <button wire:click="closeAcceptModal" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10">
            Cancel</button>
        </div>
      </div>
    </div>
  </div>
  {{-- END Accept Modal --}}

  {{-- Decline Modal --}}
  <div id="decline-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
          wire:click="closeDeclineModal">
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
          <h3 class="mb-5 text-lg font-normal tei-text-primary ">Are you sure you want to decline this bidding?</h3>
          <h4 class="mb-5 text-lg font-normal tei-text-primary ">Bidding Title: {{ $biddingTitle }}</h4>
          <button type="button" wire:loading.remove wire:target="decline"
            class="text-white bg-red-600 hover:bg-red-900 focus:outline-none  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
            wire:click.prevent="decline">
            Decline
          </button>
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 shadow-xl" wire:loading
            wire:target="decline">
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
          <button wire:click="closeDeclineModal" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10">
            Cancel</button>
        </div>
      </div>
    </div>
  </div>
  {{-- END Decline Modal --}}

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


      </div>
    </div>
  </div>
  {{-- END File Modal --}}
</div>

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

@script
  <script>
    $wire.on('openAcceptModal', () => {
      var modalElement = document.getElementById('accept-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });

    $wire.on('closeAcceptModal', () => {
      var modalElement = document.getElementById('accept-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });

    $wire.on('openDeclineModal', () => {
      var modalElement = document.getElementById('decline-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });

    $wire.on('closeDeclineModal', () => {
      var modalElement = document.getElementById('decline-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });

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
