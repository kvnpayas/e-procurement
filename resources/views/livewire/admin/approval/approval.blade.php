<div class="overflow-x-auto shadow-md sm:rounded-lg">
  <table class="w-full text-sm text-left rtl:text-right text-gray-500 boder-collapse">
    <caption class="p-5 text-lg font-semibold text-left rtl:text-right tei-text-secondary bg-white ">
      Project Bidding Approval Lists
      <p class="mt-1 text-sm font-normal text-gray-500 ">List of all bids that are already approved or ready for
        approval.
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
        <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900 whitespace-nowrap">
          <div class="flex justify-between">
            <span>{{ $header }}</span>
            @if ($index != 'envelopes' && $index != 'action' && $index != 'winner' && $index != 'created' && $index != 'bid_price')
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
        <tr
          class="text-xs bg-white border-b font-extrabold hover:bg-neutral-200 hover:shadow-xl transition duration-300">
          <th scope="row" class="px-6 py-2 font-medium tei-text-secondary whitespace-nowrap ">
            {{ $bidding->project_id }}
          </th>
          <td class="px-6 py-2 w-72 whitespace-normal">
            {{ $bidding->title }}
          </td>
          <td class="px-6 py-2">
            {{ strtoupper($bidding->type) }}
          </td>
          {{-- <td class="px-6 py-2 whitespace-nowrap">
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
          </td> --}}
          <td class="px-6 py-2 whitespace-nowrap ">
            @if ($bidding->reserved_price)
              <span class="font-extrabold ">PHP {{ number_format($bidding->reserved_price, 2) }}</span>
            @else
              <span class="font-extrabold ">No ceiling</span>
            @endif
          </td>
          <td class="px-6 py-2 whitespace-nowrap ">
            @if ($bidding->financial)
              PHP
              {{ number_format($this->getVendorTotalAmount($bidding, $bidding->winnerApproval->winnerVendor->id), 2) }}
            @else
              <span>NULL</span>
            @endif
          </td>
          <td class="px-6 py-2 whitespace-nowrap">
            {{ $bidding->winnerApproval->winnerVendor->name }}
          </td>
          <td class="px-6 py-2">
            {{ ucwords($bidding->created_user->name) }}
          </td>
          <td class="px-6 py-2">
            @if (roleAccessRights('view'))
              <button wire:click.prevent="reviewModal({{ $bidding->id }})" wire:loading.remove
                wire:target="reviewModal({{ $bidding->id }})"
                class="flex gap-2 hover:scale-110 transition-transform duration-300 mr-4 tei-bg-primary rounded-md px-2 py-1 text-white text-xs"><i
                  class="fa-solid fa-file-lines mt-0.5"></i> Review</button>
              <div class="w-20 rounded-lg tei-bg-light flex justify-center p-3 shadow-xl" wire:loading
                wire:target="reviewModal({{ $bidding->id }})">
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
          </td>
          <td class="px-6 py-2 flex ">
            @php
              $approved = $bidding->winnerApproval ? $bidding->winnerApproval->final_approver : 0;
            @endphp
            @if (roleAccessRights('review'))
              @if (!$approved)
                <button wire:click.prevent="acceptModal({{ $bidding->id }})" wire:loading.remove
                  wire:target="acceptModal({{ $bidding->id }})"
                  class="hover:scale-110 transition-transform duration-300 mr-4 bg-green-500 rounded-md px-2 py-1 text-white text-xs">Approve</button>
                <div class="w-20 rounded-lg tei-bg-light flex justify-center p-3 shadow-xl" wire:loading
                  wire:target="acceptModal({{ $bidding->id }})">
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
                <button wire:click.prevent="rejectModal({{ $bidding->id }})" wire:loading.remove
                  wire:target="rejectModal({{ $bidding->id }})"
                  class="hover:scale-110 transition-transform duration-300 mr-4 bg-red-500 rounded-md px-2 py-1 text-white text-xs">Reject</button>
                <div class="w-20 rounded-lg tei-bg-light flex justify-center p-3 shadow-xl" wire:loading
                  wire:target="rejectModal({{ $bidding->id }})">
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
                <span class="text-xs text-green-500">Approved.</span>
              @endif
            @endif
          </td>
        </tr>
        @php
          $check = [];
        @endphp
      @empty
        <tr class="bg-white border-b">
          <th scope="row" colspan="100" class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap text-center">
            <span class="font-black text-lg tei-text-primary">There is no project bid to be approved.</span>
          </th>
        </tr>
      @endforelse
    </tbody>
  </table>
  {{-- <x-action-message class="me-3" on="alert-eligibility">
    {{ __($alertMessage) }}
    </x-action-message> --}}

  {{ $biddings->links('livewire.layout.pagination') }}

  {{-- Review Modal --}}
  <div id="review-bid-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-7xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">
          </h3>
          <button type="button" wire:click="closeReviewModal"
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
        @if ($selectedBidReview)
          <div class="flex">
            <div class="mx-5 my-4">
              <div>
                <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Project No</label>
                <span
                  class="text-xs font-extrabold tei-text-accent">{{ $selectedBidReview->project_id }}</span>
              </div>
              <div>
                <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Project Title</label>
                <span class="text-xs font-extrabold tei-text-accent">{{ $selectedBidReview->title }}</span>
              </div>
              <div>
                <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Reserved Price</label>
                <span
                  class="text-xs uppercase font-extrabold tei-text-accent">{{ $selectedBidReview->reserved_price_switch ? 'PHP ' . number_format($selectedBidReview->reserved_price, 2) : 'No Cieling' }}</span>
              </div>
            </div>
            <div class="mx-5 my-4">

              <div>
                <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Score Method</label>
                <span class="text-xs uppercase font-extrabold tei-text-accent">{{ $selectedBidReview->score_method }}
                  Based</span>
              </div>
              <div>
                <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Sales</label>
                <span
                  class="text-xs font-extrabold tei-text-accent">{{ $selectedBidReview->scrap ? 'Yes' : 'No' }}</span>
              </div>
            </div>
          </div>
          <div class="flex mx-5 my-4 text-xs tei-text-accent font-semibold gap-4">
            <div>
              <span><i class="fa-solid fa-circle-check text-green-500"></i></span>
              <span> - Passed</span>
            </div>
            <div>
              <span><i class="fa-solid fa-circle-xmark text-red-500"></i></span>
              <span> - Failed</span>
            </div>
            <div>
              <span><i class="fa-solid fa-trophy tei-text-secondary"></i></span>
              <span> - Selected winning bidder</span>
            </div>
            <div class="ml-auto">
              <button wire:click="printReport" wire:loading.remove wire:target="printReport"
                class="text-xs bg-green-500 hover:bg-green-600 uppercase font-semibold text-white py-1.5 px-4 rounded-md shadow-lg hover:scale-110 transition-transform duration-300"><i
                  class="fa-solid fa-file-lines"></i> Prints</button>
              <div class="w-20 rounded-lg tei-bg-light flex justify-center p-3 shadow-xl" wire:loading
                wire:target="printReport">
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
              <div class="w-20 rounded-lg bg-white flex justify-center p-3 shadow-xl" wire:loading
                wire:target="bidPackage">
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
          </div>
          <div>
            <table class="w-full text-xs tei-text-accent uppercase text-left shadow-lg rounded-lg">
              <thead class="tei-bg-light">
                <tr>
                  <th class="px-6 py-2">
                    Overall rank
                  </th>
                  <th class="px-6 py-2">
                    Vendor name
                  </th>
                  {{-- @foreach ($envelopes as $envelope)
                    <th class="px-6 py-2">
                      {{ $envelope }}
                    </th>
                  @endforeach --}}
                  @if ($selectedBidReview->eligibility)
                    <th class="px-6 py-2">
                      Eligibility
                    </th>
                  @endif
                  @if ($selectedBidReview->technical)
                    <th class="px-6 py-2">
                      Technical
                    </th>
                  @endif
                  @if ($selectedBidReview->financial)
                    <th class="px-6 py-2">
                      Financial
                    </th>
                  @endif
                  @if (!$selectedBidReview->scrap && $selectedBidReview->score_method == 'Rating')
                    <th class="px-6 py-2">
                      Total Amount(PHP)
                    </th>
                  @endif
                  @if ($selectedBidReview->financial)
                    <th class="px-6 py-2">
                      {{ $selectedBidReview->score_method == 'Rating' ? 'Total Score(100%)' : 'Total Amount(PHP)' }}
                    </th>
                  @endif
                  <th class="px-6 py-2">
                    Selected Winner
                  </th>
                  <th class="px-6 py-2">
                    Action
                  </th>
                </tr>
              </thead>
              <tbody>
                @foreach ($vendorResults as $vendor)
                  <tr class="hover:shadow-lg hover:bg-neutral-300 border-b">
                    <td class="px-6 py-2">
                      {{ $vendor->rank }}
                    </td>
                    <td class="px-6 py-2 normal-case">
                      {{ $vendor->name }}
                    </td>
                    @if ($selectedBidReview->eligibility)
                      <td class="px-6 py-2 normal-case">
                        @if ($vendor->eligibility_result)
                          <span><i class="fa-solid fa-circle-check text-green-500"></i></span>
                        @else
                          <span><i class="fa-solid fa-circle-xmark text-red-500"></i></span>
                        @endif
                      </td>
                    @endif
                    @if ($selectedBidReview->technical)
                      <td class="px-6 py-2 normal-case">
                        @if ($vendor->technical_result)
                          <span><i class="fa-solid fa-circle-check text-green-500"></i></span>
                        @else
                          <span><i class="fa-solid fa-circle-xmark text-red-500"></i></span>
                        @endif
                      </td>
                    @endif
                    @if ($selectedBidReview->financial)
                    <td class="px-6 py-2 normal-case">
                      @if ($vendor->financial_result)
                        <span><i class="fa-solid fa-circle-check text-green-500"></i></span>
                      @else
                        <span><i class="fa-solid fa-circle-xmark text-red-500"></i></span>
                      @endif
                    </td>
                  @endif
                    @if (!$selectedBidReview->scrap && $selectedBidReview->score_method == 'Rating')
                      <td class="px-6 py-2 normal-case">
                        {{ $vendor ? 'PHP ' . number_format($vendor->total_amount, 2) : 'NULL' }}
                      </td>
                    @endif
                    @if ($selectedBidReview->financial)
                      <td class="px-6 py-2 normal-case">
                        @if ($vendor->total_score)
                          {{ $selectedBidReview->score_method == 'Rating' ? number_format($vendor->total_score, 2) . '%' : 'PHP ' . number_format($vendor->total_score, 2) }}
                        @else
                          <span><i class="fa-solid fa-circle-xmark text-red-500"></i></span>
                        @endif
                      </td>
                    @endif
                    <td class="px-6 py-2 normal-case">
                      @if ($vendor->winner)
                        <div>
                          <i class="fa-solid fa-trophy tei-text-secondary"></i> <span class="uppercase font-semibold">
                            Winner</span>
                        </div>
                      @endif
                    </td>
                    <td class="px-6 py-2 normal-case">
                      @if (roleAccessRights('view'))
                        <button wire:click.prevent="reviewVendorModal({{ $vendor->id }})" wire:loading.remove
                          wire:target="reviewVendorModal({{ $vendor->id }})"
                          class="flex gap-2 hover:scale-110 transition-transform duration-300 mr-4 tei-bg-primary rounded-md px-2 py-1 text-white text-xs"><i
                            class="fa-solid fa-file-lines mt-0.5"></i> Summary</button>
                        <div class="w-20 rounded-lg tei-bg-light flex justify-center p-3 shadow-xl" wire:loading
                          wire:target="reviewVendorModal({{ $vendor->id }})">
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
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            <div class="mx-5 my-4">
              @if ($remarks)
                <label class="block text-sm tei-text-secondary uppercase font-semibold mb-2">Remarks:</label>
                <span class="tei-text-accent font-semibold text-xs">{{ $remarks }}</span>
              @endif
            </div>
          </div>

        @endif

        <div class="flex justify-center p-4 md:p-5 border-t border-gray-200 rounded-b">
          <button wire:click="closeReviewModal"
            class=" font-medium rounded-lg text-sm px-5 py-2.5 text-center tei-bg-light text-white">
            Close
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
  {{-- Review Modal --}}

  {{-- Accept Modal --}}
  <div id="accept-modal" tabindex="-1" data-modal-backdrop="static" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button" wire:click="closeAcceptModal"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        <div class="p-4 md:p-5 text-center" wire:loading.remove wire:target="awardWinner">
          <svg class="mx-auto mb-4 tei-text-primary w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          <h3 class="mb-5 text-lg font-normal tei-text-primary ">Are you sure you want to approve and award the bid to
            <span class="font-semibold underline uppercase">{{ $selectedWinner ? $selectedWinner->name : '' }}</span>?
          </h3>
          <button type="button"
            class="text-white bg-green-600 hover:bg-green-900 focus:outline-none  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
            wire:click="awardWinner">
            Confirm
          </button>
          <button wire:click="closeAcceptModal"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10 focus:ring-4 focus:ring-gray-100">
            Cancel</button>
        </div>

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
  {{-- END Accept Modal --}}

  {{-- Cancel Modal --}}
  <div id="reject-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button" wire:click="closeRejectModal"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        <div class="p-4 md:p-5 text-center" wire:loading.remove wire:target="rejectWinner">
          <svg class="mx-auto mb-4 tei-text-primary w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          <h3 class="mb-5 text-lg font-normal tei-text-primary ">Are you sure you want to reject bid to
            <span class="font-semibold underline uppercase">{{ $rejectedWinner ? $rejectedWinner->name : '' }}</span>?
          </h3>
          <div class="py-4 text-start">
            <label class="block text-sm tei-text-secondary uppercase font-semibold mb-2">Remarks:</label>
            <textarea id="message" rows="4"
              class="tei-text-accent {{ $errors->has('rejectRemarks') ? ' border border-red-500  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 focus:ring-gray-500 focus:border-gray-500 ' }} block p-2.5 w-full text-sm rounded-lg border"
              placeholder="Enter Remarks" wire:model="rejectRemarks"></textarea>
            @error('rejectRemarks')
              <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                {{ $message }}
              </p>
            @enderror
          </div>
          <button type="button"
            class="text-white bg-red-600 hover:bg-red-900 focus:outline-none  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
            wire:click= "rejectWinner">
            Reject
          </button>
          <button wire:click="closeRejectModal" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10 focus:ring-4 focus:ring-gray-100">
            Cancel</button>
        </div>

        <div class="bg-white w-full rounded-md px-32 py-14" wire:loading wire:target="rejectWinner">
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
  {{-- END Cancel Modal --}}

  @livewire('admin.print-reports-modal')
  @livewire('admin.modal.vendor-review')
  @livewire('admin.modal.bid-package')

</div>

@script
  <script>
    $wire.on('openReviewModal', () => {
      var modalElement = document.getElementById('review-bid-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });

    $wire.on('closeReviewModal', () => {
      var modalElement = document.getElementById('review-bid-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });

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

    $wire.on('openRejectModal', () => {
      var modalElement = document.getElementById('reject-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });

    $wire.on('closeRejectModal', () => {
      var modalElement = document.getElementById('reject-modal');
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
  </script>
@endscript
