<div class="overflow-x-auto shadow-md sm:rounded-lg">
  <table class="w-full text-sm text-left rtl:text-right text-gray-500 boder-collapse">
    <caption class="p-5 text-lg font-semibold text-left rtl:text-right tei-text-secondary bg-white ">
      Project Bidding Lists
      <p class="mt-1 text-sm font-normal text-gray-500 ">List of all projects that have been put out to bid.
      </p>
      <div class="flex pt-5 gap-4">
        <div class="flex">
          <span
            class="inline-flex items-center px-3 text-sm tei-text-light tei-bg-primary border rounded-e-0 border-gray-300 border-e-0 rounded-s-md ">
            Search
          </span>
          <div class="relative">
            <input type="text" wire:model.live="search"
              class="block w-full text-xs tei-text-accent bg-transparent rounded-e-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 tei-focus-secondary peer"
              placeholder=" " />
            <label
              class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-orange-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Search
              here</label>
          </div>
        </div>

        <div class="flex">
          <span
            class="inline-flex items-center px-3 text-sm tei-text-light tei-bg-primary border rounded-e-0 border-gray-300 border-e-0 rounded-s-md ">
            Status
          </span>
          <select wire:model.live="selectedStatus"
            class="bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-e-lg border-s-gray-100 border-s-2 tei-focus-secondary focus:ring-0 block w-full py-1.5">
            <option value=''>--Select Status--</option>
            <option value="Active">Active</option>
            <option value="Approved">Approved</option>
            <option value="For Approval">For Approval</option>
            <option value="Bid Published">Bid Published</option>
            <option value="Publication Extended">Publication Extended</option>
            <option value="For Evaluation">For Evaluation</option>
            <option value="Under Evaluation">Under Evaluation</option>
            <option value="On Hold">On Hold</option>
            <option value="On Hold Due To Protest">On Hold Due To Protest</option>
            <option value="Awarded">Awarded</option>
            <option value="Cancelled(Unpublished)">Cancelled(Unpublished)</option>
            <option value="Bid Failure">Bid Failure</option>
            <option value="Cancelled(Published)">Cancelled(Published)</option>
          </select>
        </div>
        <div class="flex">
          <label class="inline-flex items-center cursor-pointer">
            <input type="checkbox" value="" class="sr-only peer" wire:model.live="showAll">
            <div
              class="relative w-9 h-5 bg-gray-500 peer-focus:outline-none  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-700">
            </div>
            <span class="ms-1 text-xs tei-text-secondary  font-semibold">Show All</span>
          </label>
        </div>
        @if (roleAccessRights('create'))
          <div class="ml-auto">
            <a href="{{ route('project-bidding.create-project-bidding') }}"
              class="text-white tei-bg-primary hover:bg-sky-900 font-medium rounded-lg text-xs px-5 py-2 me-2 mb-2 hover:scale-125 transition-transform duration-300">
              Create Bidding
            </a>
          </div>
        @endif
      </div>
    </caption>

    <thead class="text-xs tei-text-primary uppercase tei-bg-light font-extrabold" wire:loading.remove
      wire:target="showAll, search, selectedStatus">
      @foreach ($tableHeader as $index => $header)
        <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900 truncate">
          <div class="flex justify-between">
            <span>{{ $header }}</span>
            @if ($index == 'status')
              <span data-tooltip-target="tooltip-status-info" data-tooltip-placement="bottom"
                data-tooltip-trigger="click" class="cursor-pointer">
                <i class="fa-solid fa-circle-info text-sky-500"></i>
              </span>
              <div id="tooltip-status-info" role="tooltip"
                class="border border-gray-400 absolute z-10 invisible inline-block px-5 py-2 text-sm tei-text-accent transition-opacity duration-300 rounded-lg shadow-lg opacity-0 tooltip bg-white">
                <ul>
                  <li><small><span class="text-green-700 font-extrabold">Active</span> - Configuration Phase</small>
                  </li>
                  <li><small><span class="tei-text-primary font-extrabold">Bid Published</span> - Bidding/RFX is already
                      published</small></li>
                  <li><small><span class="tei-text-primary font-extrabold">Bid Published (Extended)</span> - Bidding/RFX
                      is
                      extended</small></li>
                  <li><small><span class="text-sky-700 font-extrabold">For Evaluation</span> - Bidding/RFX meets deadline and
                      ready for
                      evaluation</small></li>
                  <li><small><span class="text-sky-700 font-extrabold">Under Evaluation</span> - Bidding/RFX is already
                      in
                      evaulation</small></li>
                  <li><small><span class="text-yellow-500 font-extrabold">Hold</span> - Bidding/RFX is on hold</small>
                  </li>
                  <li><small><span class="text-green-700 font-extrabold">Awarded</span> - Bidding/RFX is awarded</small>
                  </li>
                  <li><small><span class="text-red-700 font-extrabold">Cancelled(Unpublished)</span> - Unpublished
                      Bidding/RFX that
                      are
                      cancelled</small></li>
                  <li><small><span class="text-red-700 font-extrabold">Bid Failure</span> - Bidding/RFX is
                      failure</small>
                  </li>
                  <li><small><span class="text-red-700 font-extrabold">Cancelled(Published)</span> - Cancelled
                      Bidding/RFX
                      during
                      published</small></li>
                </ul>
                <div class="tooltip-arrow" data-popper-arrow></div>
              </div>
            @endif
            @if ($index != 'envelopes' && $index != 'action')
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
    <tbody wire:loading.remove wire:target="showAll, search, selectedStatus">
      @forelse ($biddings as $bidding)
        <tr
          class="bg-white border-b font-extrabold hover:bg-neutral-200 hover:shadow-xl transition duration-300  text-xs">
          <th scope="row" class="px-4 py-2 font-medium tei-text-secondary truncate text-xs">
            {{ $bidding->project_id }}
          </th>
          <td class="px-4 py-2 w-96">
            {{ $bidding->title }}
          </td>
          <td class="px-4 py-2">
            {{ strtoupper($bidding->type) }}
          </td>
          <td class="px-4 py-2 whitespace-nowrap">
            @if ($bidding->reserved_price)
              <span class="font-extrabold ">PHP {{ number_format($bidding->reserved_price, 2) }}</span>
            @else
              <span class="font-extrabold ">No ceiling</span>
            @endif
          </td>
          <td class="px-4 py-2 truncate">
            @php
              $allEnvelopes = [
                  'eligibility' => $bidding->eligibility,
                  'technical' => $bidding->technical,
                  'financial' => $bidding->financial,
              ];

              $envelopes = array_keys(array_filter($allEnvelopes));
            @endphp
            <span>
              {{ ucwords(implode(', ', $envelopes)) }}
            </span>
          </td>
          <td class="px-4 py-2">
            {{ $bidding->vendors->count() }}
          </td>
          <td class="px-4 py-2 truncate text-xs">
            <span
              class="
                    {{ $bidding->status == 'Active' || $bidding->status == 'For Evaluation' || $bidding->status == 'Awarded' || $bidding->status == 'Approved' || $bidding->status == 'For Approval' ? 'text-green-500' : '' }} 
                    {{ $bidding->status == 'On Hold' || $bidding->status == 'On Hold Due To Protest' ? 'text-yellow-500' : '' }}
                    {{ $bidding->status == 'Bid Published' || strpos($bidding->status, 'Publication Extended') === 0 ? 'tei-text-primary' : '' }}
                    {{ $bidding->status == 'Cancelled(Published)' || $bidding->status == 'Bid Failure' ? 'text-red-500' : '' }}
                    {{ $bidding->status == 'Cancelled(Unpublished)' ? 'text-red-500' : '' }}
                    {{ $bidding->status == 'Under Evaluation' ? 'text-sky-500' : '' }}
                    fw-bold text-truncate">{{ strtoupper($bidding->status) }}
            </span>
            @if ($bidding->status == 'Under Evaluation' && $bidding->winnerApproval)
              <button data-tooltip-target="tooltip-approval-{{ $bidding->id }}"><i
                  class="fa-solid fa-circle-exclamation text-yellow-500"></i></button>
              <div id="tooltip-approval-{{ $bidding->id }}" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-secondary rounded-lg shadow-sm opacity-0 tooltip">
                The winner of this bid has been rejected.
                <div class="tooltip-arrow" data-popper-arrow></div>
              </div>
            @endif
          </td>
          <td class="px-4 py-2 truncate">
            {{ ucfirst($bidding->created_user->name) }}
          </td>
          <td class="px-4 py-2 truncate text-xs">
            {{ $bidding->extend_date ? date('F j,Y @ h:i A', strtotime($bidding->extend_date)) : date('F j,Y @ h:i A', strtotime($bidding->deadline_date)) }}
          </td>
          <td class="w-48">
            <div class="px-4 py-2">
              <button id="bidding-button-{{ $bidding->id }}"
                class="truncate shadow-xl w-full text-white tei-bg-primary hover:bg-sky-900 font-medium rounded-sm text-xs px-10 py-1.5 transition delay-300 duration-150 ease-in-out">View
                more</button>
              <div class="rounded-b-lg bg-white shadow-xl bidding-hide bidding-action-menu"
                id="action-{{ $bidding->id }}" wire:ignore.self>
                <div class="flex justify-around p-4 ">
                  @if ($bidding->status == 'Active' || $bidding->status == 'On Hold')
                    @if (roleAccessRights('update'))
                      <a href="{{ route('project-bidding.edit-project-bidding', $bidding->id) }}"
                        class="tei-bg-primary px-1.5 py-1 rounded-sm text-white hover:scale-125 transition-transform duration-300"
                        data-tooltip-target="tooltip-edit-bidding-{{ $bidding->id }}">
                        <i class="fa-solid fa-pen-to-square"></i>
                      </a>
                      <div id="tooltip-edit-bidding-{{ $bidding->id }}" role="tooltip"
                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-secondary rounded-lg shadow-sm opacity-0 tooltip">
                        Edit Bidding
                        <div class="tooltip-arrow" data-popper-arrow></div>
                      </div>
                    @endif
                  @else
                    @if (roleAccessRights('view'))
                      <a href="{{ route('project-bidding.view-project-bidding', $bidding->id) }}"
                        class="tei-bg-primary px-1.5 py-1 rounded-sm text-white hover:scale-125 transition-transform duration-300"
                        data-tooltip-target="tooltip-view-bidding-{{ $bidding->id }}">
                        <i class="fa-solid fa-eye"></i>
                      </a>
                      <div id="tooltip-view-bidding-{{ $bidding->id }}" role="tooltip"
                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-secondary rounded-lg shadow-sm opacity-0 tooltip">
                        View Bid details
                        <div class="tooltip-arrow" data-popper-arrow></div>
                      </div>
                    @endif
                  @endif
                  <button wire:click.prevent="envelopeRoute({{ $bidding->id }})"
                    class="tei-bg-secondary px-1.5 py-1 rounded-sm text-white hover:scale-125 transition-transform duration-300"
                    data-tooltip-target="tooltip-envelope-bidding-{{ $bidding->id }}">
                    <i class="fa-solid fa-envelopes-bulk"></i>
                  </button>
                  <div id="tooltip-envelope-bidding-{{ $bidding->id }}" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-secondary rounded-lg shadow-sm opacity-0 tooltip">
                    Add requirements on envelopes
                    <div class="tooltip-arrow" data-popper-arrow></div>
                  </div>

                  @php
                    $check = [];
                    foreach ($envelopes as $env) {
                        if ($env == 'eligibility') {
                            $check[$env] = $bidding->eligibilities->count() == 0 ? false : true;
                        } elseif ($env == 'technical') {
                            $check[$env] = $bidding->technicals->count() == 0 ? false : true;
                        } elseif ($env == 'financial') {
                            $check[$env] = $bidding->financials->count() == 0 ? false : true;
                        }
                    }
                  @endphp
                  @if (in_array(false, $check, true))
                    <button class="bg-gray-400 px-1.5 py-1 rounded-sm text-white"
                      data-tooltip-target="tooltip-vendor-bidding-false-{{ $bidding->id }}">
                      <i class="fa-solid fa-users"></i>
                    </button>
                    <div id="tooltip-vendor-bidding-false-{{ $bidding->id }}" role="tooltip"
                      class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-secondary rounded-lg shadow-sm opacity-0 tooltip">
                      Disabled: need to add requirements on all envelopes
                      <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                  @else
                    <a href="{{ route('project-bidding.vendor-lists', $bidding->id) }}"
                      class="bg-blue-700 px-1.5 py-1 rounded-sm text-white hover:scale-125 transition-transform duration-300"
                      data-tooltip-target="tooltip-vendor-bidding-{{ $bidding->id }}">
                      <i class="fa-solid fa-users"></i>
                    </a>
                    <div id="tooltip-vendor-bidding-{{ $bidding->id }}" role="tooltip"
                      class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-secondary rounded-lg shadow-sm opacity-0 tooltip">
                      {{ $bidding->status == 'Active' ? 'Invite vendors to bid.' : 'View Vendors.' }}
                      <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                  @endif
                </div>
                <div class="p-4 border-t-2 border-gray-100">
                  @if ($bidding->status == 'Active' || $bidding->status == 'On Hold')
                    @if (roleAccessRights('review'))
                      <button wire:click="startBid({{ $bidding->id }})" wire:loading.remove
                        wire:target="startBid({{ $bidding->id }})"
                        class="shadow-lg w-full text-white bg-green-600 hover:bg-green-800 font-medium rounded-sm text-xs py-1.5 transition delay-150 duration-100 ease-in-out mb-4">Start
                        Bid</button>
                      <div class="w-full rounded tei-bg-light flex justify-center py-3.5 mb-4" wire:loading
                        wire:target="startBid({{ $bidding->id }})">
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

                      <button wire:click="cancelBid({{ $bidding->id }})" wire:loading.remove
                        wire:target="cancelBid({{ $bidding->id }})"
                        class="shadow-lg w-full text-white bg-red-600 hover:bg-red-800 font-medium rounded-sm text-xs py-1.5 transition delay-150 duration-100 ease-in-out">Cancel</button>
                      <div class="w-full rounded tei-bg-light flex justify-center py-3.5 mb-4" wire:loading
                        wire:target="cancelBid({{ $bidding->id }})">
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
                  @elseif($bidding->status == 'Bid Published' || strpos($bidding->status, 'Publication Extended') === 0)
                    @if (roleAccessRights('review'))
                      <button wire:click="holdBid({{ $bidding->id }})" wire:loading.remove
                        wire:target="holdBid({{ $bidding->id }})"
                        class="shadow-lg w-full text-white bg-yellow-500 hover:bg-yellow-600 font-medium rounded-sm text-xs py-1.5 transition delay-150 duration-100 ease-in-out mb-4">Hold</button>
                      <div class="w-full rounded tei-bg-light flex justify-center py-3.5 mb-4" wire:loading
                        wire:target="holdBid({{ $bidding->id }})">
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
                      @if ($bidding->status != 'Awarded')
                        <button wire:click="bulletin({{ $bidding->id }})"
                          class="shadow-lg w-full text-white bg-green-600 hover:bg-green-800 font-medium rounded-sm text-xs py-1.5 transition delay-150 duration-100 ease-in-out mb-4">Bid
                          Bulletin</button>
                      @endif
                    @endif
                  @elseif(
                      $bidding->status == 'For Evaluation' ||
                          $bidding->status == 'Under Evaluation' ||
                          $bidding->status == 'On Hold Due To Protest')
                    @if (roleAccessRights('review'))
                      <button wire:click="evaluateBidModal({{ $bidding->id }})" wire:loading.remove
                        wire:target="evaluateBidModal({{ $bidding->id }})"
                        class="shadow-lg w-full text-white bg-sky-500 hover:bg-sky-600 font-medium rounded-sm text-xs py-1.5 transition delay-150 duration-100 ease-in-out mb-4">{{ $bidding->status == 'For Evaluation' ? 'Evaluate' : 'Continue Evaluate' }}</button>
                        <div class="w-full rounded tei-bg-light flex justify-center py-3.5 mb-4" wire:loading
                        wire:target="evaluateBidModal({{ $bidding->id }})">
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
                  @if (
                      $bidding->status == 'Awarded' ||
                          $bidding->status == 'For Approval' ||
                          $bidding->status == 'Approved' ||
                          $bidding->status == 'Bid Failure')
                    @if (roleAccessRights('view'))
                      <button wire:click="results({{ $bidding->id }})"
                        class="shadow-lg w-full text-white bg-green-500 hover:bg-green-600 font-medium rounded-sm text-xs py-1.5 transition delay-150 duration-100 ease-in-out mb-4">Results</button>
                    @endif
                  @endif
                </div>
              </div>
            </div>
          </td>
        </tr>
        @php
          $check = [];
        @endphp
      @empty
        <tr class="bg-white border-b">
          <th scope="row" colspan="100"
            class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap text-center">
            <span class="font-black text-lg tei-text-primary">There is no project bidding on record.</span>
          </th>
        </tr>
      @endforelse
    </tbody>
  </table>
  {{-- pre loading --}}
  <div class=" w-full flex justify-center">
    <div class="rounded-lg flex justify-center p-4 md:p-5" wire:loading wire:target="showAll, search, selectedStatus">
      <div class="content-center">
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
  {{-- <x-action-message class="me-3" on="alert-eligibility">
    {{ __($alertMessage) }}
    </x-action-message> --}}
  <div wire:loading.remove wire:target="showAll">
    {{ $biddings->links('livewire.layout.pagination') }}
  </div>

  {{-- Start Bidding Modal --}}
  <div id="start-bid-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-5xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">
            Are you sure you want to proceed and start the bid?
          </h3>
          <button type="button" wire:click="closeStartBidModal"
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
        @if ($selectedBid)
          <div class="mx-5 my-4">
            <div>
              <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Project Title:</label>
              <span class="text-xs uppercase font-extrabold tei-text-accent">{{ $selectedBid->title }}</span>
            </div>
            <div>
              <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Start Date:</label>
              <span
                class="text-xs uppercase font-extrabold tei-text-accent">{{ date('F j,Y', strtotime(now())) }}</span>
            </div>
            <div>
              <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Deadline Date:</label>
              <span class="text-xs uppercase font-extrabold tei-text-accent">
                {{ $selectedBid->extend_date ? date('F j,Y @ h:i A', strtotime($selectedBid->extend_date)) : date('F j,Y @ h:i A', strtotime($selectedBid->deadline_date)) }}
              </span>
              @error('bidDeadlineDate')
                <span class="mt-2 text-xs text-red-600 font-extrabold ml-4">
                  {{ $message }}
                </span>
              @enderror
            </div>
            <div>
              <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Invited Vendors</label>
              <span
                class="text-xs uppercase font-extrabold tei-text-accent mr-2">{{ $selectedBid->vendors->count() }}</span>
              @if ($selectedBid->vendors->count() == 0)
                <i class="fa-solid text-xs fa-circle-xmark text-red-500"></i>
              @else
                <i class="fa-solid text-xs fa-circle-check text-green-500"></i>
              @endif
            </div>

            <div class="mt-4">
              <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary block">Active
                Envelopes:</label>
              <small class="italic text-yellow-500">Please add requirements to all envelopes before starting the
                bid.</small>
              <div class=" mt-2">
                @foreach ($activeEnvelopes as $envelope)
                  <div class="flex flex-col tei-text-accent">
                    <span
                      class="text-xs uppercase font-extrabold tei-text-accent mr-2">{{ ucfirst($envelope) }}</span>
                    @if ($selectedBid->{$envelope}()->count() == 0)
                      <i class="fa-solid text-xs fa-circle-xmark text-red-500"></i>
                    @else
                      <i class="fa-solid text-xs fa-circle-check text-green-500"></i>
                      {{-- <div>
                        <button
                          class="shadow-lg w-full text-white tei-bg-primary hover:bg-sky-900 rounded-sm text-xs py-1 transition delay-150 duration-100 ease-in-out mb-4"
                          wire:click.prevent="{{ $envelope }}Preview({{ $selectedBid->id }})">Preview
                          Req.</button>
                      </div> --}}
                      @if ($envelope == 'eligibilities')
                        <div class="flex flex-col py-2 px-5">
                          @foreach ($selectedBid->eligibilities as $eligibility)
                            <span class="text-xs font-extrabold mr-2">{{ $eligibility->name }}</span>
                          @endforeach
                        </div>
                      @elseif ($envelope == 'technicals')
                        <div class="flex flex-col py-2 px-5">
                          @foreach ($selectedBid->technicals as $technical)
                            <div class="grid grid-cols-3">
                              <span class="text-xs font-extrabold mr-2">{{ $technical->question }}</span>
                              <span class="text-xs font-extrabold mr-2">{{ $technical->question_type }}</span>
                              <span
                                class="text-xs font-extrabold mr-2">{{ number_format($technical->pivot->weight, 2) }}%</span>
                            </div>
                          @endforeach
                        </div>
                      @elseif ($envelope == 'financials')
                        <div class="flex flex-col py-2 px-5">
                          <div class="text-xs font-extrabold mb-2">
                            @if ($selectedBid->reserved_price_switch)
                              <div>
                                <span class="tei-text-secondary">Reserved Price: </span>
                                <span class="{{ $errors->has('financialTotalAmount') ? 'text-red-500' : '' }}">PHP
                                  {{ number_format($selectedBid->reserved_price, 2) }}</span>
                              </div>
                            @endif
                            <div>
                              <span class="tei-text-secondary">Total Amount: </span>
                              <span class="{{ $errors->has('financialTotalAmount') ? 'text-red-500' : '' }}">PHP
                                {{ number_format($financialTotalAmount, 2) }}</span>
                            </div>
                            @error('financialTotalAmount')
                              <span class="mt-2 text-xs text-red-600 font-extrabold">
                                ** {{ $message }} **
                              </span>
                            @enderror
                          </div>


                          @foreach ($selectedBid->financials as $financial)
                            <div class="grid grid-cols-3">
                              <span class="text-xs font-extrabold mr-2">{{ $financial->inventory_id }}</span>
                              <span class="text-xs font-extrabold mr-2">{{ $financial->description }}</span>
                              <span class="text-xs font-extrabold mr-2">PHP
                                {{ number_format($financial->pivot->bid_price * $financial->pivot->quantity, 2) }}</span>
                            </div>
                          @endforeach
                        </div>
                      @endif
                    @endif
                  </div>
                @endforeach
              </div>
            </div>

            <div class="mt-4 mb-2">
              <div>
                <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary block">Additional
                  Instructions:</label>
                @if ($selectedBid->instruction_details)
                  <span class="text-xs uppercase font-extrabold tei-text-accent">
                    {!! nl2br(e($selectedBid->instruction_details ? $selectedBid->instruction_details : 'N/A')) !!}
                  </span>
                @else
                  <span class="text-xs uppercase font-extrabold tei-text-accent">N/A</span>
                @endif
              </div>
              <div>
                <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary block">Instructional
                  Attachment:</label>
                @if (!$selectedBid->projectBidFiles->isEmpty())
                  @foreach ($selectedBid->projectBidFiles as $file)
                    <button wire:click.prevent="downloadPdf('{{ $file->file_name }}')"
                      class="mb-5 block hover:scale-110 transition-transform duration-300 mr-4 tei-bg-primary rounded-md px-2 py-1 text-white text-xs"><i
                        class="fa-solid fa-file-pdf"></i> {{ $file->file_name }}</button>
                  @endforeach
                @else
                  <span class="text-xs uppercase font-extrabold tei-text-accent">N/A</span>
                @endif
              </div>
            </div>
          </div>
        @endif
        <div class="flex justify-center p-4 md:p-5 border-t border-gray-200 rounded-b">
          <button wire:click="startBidding" wire:loading.remove wire:target="startBidding"
            class=" font-medium rounded-lg text-sm px-5 py-2.5 text-center {{ $errors->has('envRequirements') || $errors->has('envVendors') || $errors->has('bidDeadlineDate') || $errors->has('financialTotalAmount') ? 'tei-bg-light text-white' : 'text-white bg-green-600 hover:bg-green-700 focus:ring-0 focus:outline-none hover:scale-110 transition-transform duration-300' }}"
            {{ $errors->has('envRequirements') || $errors->has('envVendors') || $errors->has('bidDeadlineDate') || $errors->has('financialTotalAmount') ? 'disabled' : '' }}>
            Proceed
          </button>
          <div class="rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading.delay
            wire:target="startBidding">
            <div class="content-center">
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
      </div>
    </div>
  </div>
  {{-- Start Bidding Modal --}}

  {{-- Hold Modal --}}
  <div id="hold-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button" wire:click="closeHoldModal"
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
          <h3 class="mb-5 text-lg font-normal tei-text-primary ">Are you sure you want to hold this bid?</h3>
          @if ($this->bidHold)
            <div class="mb-4">
              <span class="tei-text-secondary font-extrabold">Project Bid Title: </span>
              <span class="tei-text-accent">{{ $this->bidHold->title }}</span>
            </div>
          @endif
          <button wire:loading.remove wire:target="holdBidding" type="button"
            class="text-white bg-green-600 hover:bg-green-900 focus:outline-none  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
            wire:click= "holdBidding">
            Confirm
          </button>
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
            wire:target="holdBidding">
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
          <button wire:click="closeHoldModal" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10">
            Cancel</button>
        </div>
      </div>
    </div>
  </div>
  {{-- END Hold Modal --}}

  {{-- Cancel Modal --}}
  <div id="cancel-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button" wire:click="closeCancelModal"
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
          <h3 class="mb-5 text-lg font-normal tei-text-primary ">Are you sure you want to cancel this bid?</h3>
          @if ($this->bidCancel)
            <div class="mb-4">
              <span class="tei-text-secondary font-extrabold">Project Bid Title: </span>
              <span class="tei-text-accent">{{ $this->bidCancel->title }}</span>
            </div>
          @endif
          <button type="button" wire:loading.remove wire:target="cancelBidding"
            class="text-white bg-green-600 hover:bg-green-900 focus:outline-none  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
            wire:click= "cancelBidding">
            Confirm
          </button>
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
            wire:target="cancelBidding">
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
          <button wire:click="closeCancelModal" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10">
            Cancel</button>
        </div>
      </div>
    </div>
  </div>
  {{-- END Cancel Modal --}}

  {{-- Evaluate Bid Modal --}}
  <div id="evaluate-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">
            Evaluate Bid
          </h3>
          <button type="button" wire:click="closeEvaluateModal" wire:loading.remove wire:target="evaluateBidding"
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
        @if ($evaluateBid)
          <div class="p-4">
            <div>
              <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Bid Title:</label>
              <span class="text-xs uppercase font-extrabold tei-text-accent">{{ $evaluateBid->title }}</span>
            </div>
            <p class="mt-1 text-sm font-normal text-gray-500 ">Lists of participating vendors.
            </p>
            <div class="mt-5">
              @php
                $allEnvelopes = [
                    'eligibility' => (bool) $evaluateBid->eligibility,
                    'technical' => (bool) $evaluateBid->technical,
                    'financial' => (bool) $evaluateBid->financial,
                ];
                $firstEnvelope = array_search(true, $allEnvelopes, true);

                $envelopes = array_filter($allEnvelopes, function ($value) {
                    return $value === true;
                });
              @endphp
              @foreach ($evaluateBid->vendors as $vendor)
                <div class="mt-5">
                  <h3 class="text-lg font-extrabold tei-text-primary">{{ $vendor->name }}</h3>
                  <div>
                    <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Status:</label>
                    <span
                      class="text-xs uppercase font-extrabold tei-text-accent {{ in_array($vendor->pivot->status, ['Under Evaluation', 'For Evaluation']) ? 'text-green-500' : 'text-red-500' }}">{{ in_array($vendor->pivot->status, ['Under Evaluation', 'For Evaluation']) ? 'Joined' : $vendor->pivot->status }}</span>
                  </div>
                  @if (in_array($vendor->pivot->status, ['Under Evaluation', 'For Evaluation']))
                    <div>
                      <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Envelopes</label>
                      @foreach ($envelopes as $envelope => $value)
                        <div class="pl-4">
                          <span class="text-xs tei-text-accent uppercase font-semibold">{{ $envelope }}:</span>
                          @php
                            $result = $vendor->envelopeStatus
                                ->where('bidding_id', $evaluateBid->id)
                                ->where('envelope', $envelope)
                                ->first()->status;
                          @endphp
                          <span
                            class="text-xs font-seimbold {{ $result ? 'text-green-500' : 'text-red-500' }}">{{ $result ? 'The Vendor submit or respond to all requirements.' : 'The vendor failed to submit all the requirements needed.' }}</span>
                        </div>
                      @endforeach
                    </div>
                  @endif
                </div>
              @endforeach

            </div>
          </div>
          <hr>
          <div class="p-5">
            <div>
              <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Vendor joined the
                bid:</label>
              <span
                class="text-xs uppercase font-extrabold tei-text-accent">{{ $evaluateBid->vendors()->wherePivotIn('status', ['For Evaluation', 'Under Evaluation'])->count() }}</span>
            </div>
            <div>
              <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Vendor declined the
                bid:</label>
              <span
                class="text-xs uppercase font-extrabold tei-text-accent">{{ $evaluateBid->vendors()->wherePivot('status', 'Declined')->count() != 0 ? $evaluateBid->vendors()->wherePivot('status', 'Declined')->count() : 'None' }}</span>
            </div>
          </div>
        @endif

        <div class="flex justify-center p-4 md:p-5 border-t border-gray-200 rounded-b">
          <button wire:click="evaluateBidding" wire:loading.remove wire:target="evaluateBidding"
            class=" font-medium rounded-lg text-sm px-5 py-2.5 text-center {{ $errors->has('envRequirements') || $errors->has('envVendors') ? 'tei-bg-light text-white' : 'text-white bg-sky-500 hover:bg-sky-600 focus:ring-0 focus:outline-none hover:scale-110 transition-transform duration-300' }}"
            {{ $errors->has('envRequirements') || $errors->has('envVendors') ? 'disabled' : '' }}>
            Evaluate
          </button>
          <button wire:click="closeEvaluateModal" type="button" wire:loading.remove wire:target="evaluateBidding"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-300 focus:z-10 hover:scale-110 transition-transform duration-300">
            Cancel</button>
          <div class="rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading.delay
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
          </div>
        </div>
      </div>
    </div>
  </div>
  {{-- Evaluate Bid Modal --}}

  {{-- EligbilityPreview Modal --}}
  <div id="eligibility-preview-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button" wire:click="closeHoldModal"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
      </div>
      <div class="p-4 md:p-5 text-center">
        <svg class="mx-auto mb-4 tei-text-primary w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
          fill="none" viewBox="0 0 20 20">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <h3 class="mb-5 text-lg font-normal tei-text-primary ">Are you sure you want to hold this bid?</h3>
        @if ($this->bidHold)
          <div class="mb-4">
            <span class="tei-text-secondary font-extrabold">Project Bid Title: </span>
            <span class="tei-text-accent">{{ $this->bidHold->title }}</span>
          </div>
        @endif
        <button data-modal-hide="hold-modal" type="button"
          class="text-white bg-green-600 hover:bg-green-900 focus:outline-none  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
          wire:click= "holdBidding">
          Confirm
        </button>
        <button wire:click="closeHoldModal" type="button"
          class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10 ">
          Cancel</button>
      </div>

    </div>
  </div>
  {{-- END Hold Modal --}}

</div>

@section('content-script')
  <script>
    // Maintenance
    document.addEventListener("click", (e) => {
      var buttonId = e.target.id;
      var id = buttonId.split('-')[2]

      var actionMenu = document.getElementById("action-" + id);
      if (actionMenu.classList.contains('bidding-hide')) {
        actionMenu.classList.add('bidding-show')
        actionMenu.classList.remove('bidding-hide')
      } else {
        actionMenu.classList.remove('bidding-show')
        actionMenu.classList.add('bidding-hide')
      }
    })
    // end Maintenance
  </script>
@endsection
@script
  <script>
    $wire.on('openStartBidModal', () => {
      var modalElement = document.getElementById('start-bid-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });

    $wire.on('closeStartBidModal', () => {
      var modalElement = document.getElementById('start-bid-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });

    $wire.on('openHoldModal', () => {
      var modalElement = document.getElementById('hold-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });

    $wire.on('closeHoldModal', () => {
      var modalElement = document.getElementById('hold-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });

    $wire.on('openCancelModal', () => {
      var modalElement = document.getElementById('cancel-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });

    $wire.on('closeCancelModal', () => {
      var modalElement = document.getElementById('cancel-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });

    $wire.on('openEvaluateModal', () => {
      var modalElement = document.getElementById('evaluate-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });

    $wire.on('closeEvaluateModal', () => {
      var modalElement = document.getElementById('evaluate-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });

    $wire.on('openEligbilityPreviewModal', () => {
      var modalElement = document.getElementById('eligibility-preview-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });

    $wire.on('closeEligbiltyPreviewModal', () => {
      var modalElement = document.getElementById('eligibility-preview-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });
  </script>
@endscript
