<div class="max-w-full mx-auto sm:px-6 lg:px-8 py-10">
  <div class="overflow-x-auto rounded-md shadow-lg">
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
    @foreach ($vendors as $index => $vendor)
      <div id="financial-vendor-{{ $vendor->id }}" class="">
        <div class="p-2 tei-bg-gradient shadow-none hover:shadow-lg">
          <h3 id="header-{{ $vendor->id }}" onclick="toggleArrow({{ $vendor->id }})"
            class=" px-2 cursor-pointer tei-text-light text-lg font-semibold rounded-t-md hover:text-orange-500 transition ease-in-out">
            <i id="arrow-icon-{{ $vendor->id }}"
              class="fa-solid fa-chevron-up text-sm transition-transform duration-500 ease-in-out {{ $index == 0 ? 'down-arrow' : 'up-arrow' }}"></i>
            <span class="ml-4">{{ $vendor->name }}</span>
          </h3>
        </div>
        <div class="vendor-content tei-bg-light pt-4 {{ $index == 0 ? 'content-show' : 'content-hide' }}"
          id="vendor-content-{{ $vendor->id }}" wire:ignore.self>
          <div class="p-4 ">
            <div>
              <table class="w-full text-sm text-left rtl:text-right text-gray-500 shadow-lg">
                <thead class="text-xs tei-text-secondary uppercase bg-white">
                  <tr>
                    <th scope="col" class="px-6 py-3 rounded-tl-lg">
                      Inventory Id
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Description
                    </th>
                    <th scope="col" class="px-6 py-3">
                      UOM
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Reserved Price
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Quantity
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Vendor Price
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Tax/Duties/Fees/Levies
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Amount
                    </th>
                    @if (Auth::user()->role->id == 4 || Auth::user()->role->id == 1)
                      <th scope="col" class="px-6 py-3 rounded-tr-lg">
                        Admin
                      </th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                  @foreach ($financials as $financial)
                    <tr class="bg-white font-semibold text-xs">
                      <td class="px-6 py-4">
                        {{ $financial->inventory_id }}
                      </td>
                      <td class="px-6 py-4 w-96">
                        {{ $financial->description }}
                      </td>
                      <td class="px-6 py-4">
                        {{ $financial->uom }}
                      </td>
                      <td class="px-6 py-4">
                        PHP
                        {{ $financialsVendor[$vendor->id][$financial->id] ? number_format($financialsVendor[$vendor->id][$financial->id]['bid_price'], 2) : null }}
                      </td>
                      <td class="px-6 py-4">
                        {{ $financialsVendor[$vendor->id][$financial->id] ? $financialsVendor[$vendor->id][$financial->id]['quantity'] : null }}
                      </td>
                      <td class="px-6 py-4">
                        @if ($financialsVendor[$vendor->id][$financial->id])
                          @if ($financialsVendor[$vendor->id][$financial->id]['admin_price'])
                            <span class="tei-text-secondary"> PHP
                              {{ number_format($financialsVendor[$vendor->id][$financial->id]['price'], 2) }}</span>
                            <i class="fa-solid fa-user-tie ml-5 tei-text-secondary"></i>
                          @else
                            <span> PHP
                              {{ number_format($financialsVendor[$vendor->id][$financial->id]['price'], 2) }}</span>
                          @endif
                        @else
                          <span class="text-red-500">Null</span>
                        @endif
                      </td>
                      <td class="px-6 py-4">
                        @if ($financialsVendor[$vendor->id][$financial->id])
                          @if ($financialsVendor[$vendor->id][$financial->id]['admin_fees'])
                            <span class="tei-text-secondary"> PHP
                              {{ number_format($financialsVendor[$vendor->id][$financial->id]['fees'], 2) }}</span>
                            <i class="fa-solid fa-user-tie ml-5 tei-text-secondary"></i>
                          @else
                            <span> PHP
                              {{ number_format($financialsVendor[$vendor->id][$financial->id]['fees'], 2) }}</span>
                          @endif
                        @else
                          <span class="text-red-500">Null</span>
                        @endif
                      </td>
                      <td class="px-6 py-4">
                        @if ($financialsVendor[$vendor->id][$financial->id])
                          PHP {{ number_format($financialsVendor[$vendor->id][$financial->id]['amount'], 2) }}
                        @else
                          <span class="text-red-500">Null</span>
                        @endif
                      </td>
                      @if (Auth::user()->role->id == 4 || Auth::user()->role->id == 1)
                        <td class="px-6 py-4">
                          <button wire:click.prevent="adminModal({{ $financial->id }}, {{ $vendor->id }})"
                            wire:loading.remove wire:target="adminModal({{ $financial->id }}, {{ $vendor->id }})"
                            class="hover:scale-110 transition-transform duration-300 mr-4 bg-green-500 rounded-md px-2 py-1 text-white text-xs">Admin</button>
                          <div class="w-14 rounded tei-bg-light flex justify-center py-2.5 mr-4" wire:loading
                            wire:target="adminModal({{ $financial->id }}, {{ $vendor->id }})">
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
                      @endif
                    </tr>
                  @endforeach
                  <tr>
                    <td colspan="6" class="px-6 py-4 rounded-bl-lg">

                    </td>
                    <td colspan="6" class="px-6 py-4">
                      <span class="text-xs uppercase font-extrabold">
                        Total: PHP {{ number_format(collect($financialsVendor[$vendor->id])->sum('amount'), 2) }}
                      </span>
                    </td>
                    <td>

                    </td>
                  </tr>
                </tbody>
              </table>

              <div>
                <div class="py-5 px-5">
                  <h3 class="text-xl tei-text-secondary font-semibold mb-4">Financial Offer File</h3>
                  <div>
                    @if (isset($financialOfferFiles[$vendor->id]) && $financialOfferFiles[$vendor->id])
                      @foreach ($financialOfferFiles[$vendor->id] as $dataFile)
                        <button wire:click.prevent="viewFile('{{ $dataFile->file }}', {{ $dataFile->id }})"
                          wire:loading.remove wire:target="viewFile('{{ $dataFile->file }}', {{ $dataFile->id }})"
                          class="{{ $fileStatus[$dataFile->id]['status'] ? 'bg-green-600' : 'bg-gray-400' }} flex mb-1 hover:scale-110 transition-transform duration-300 rounded-md px-2 py-1 text-white text-xs">
                          <i class="fa-solid fa-file-pdf text-xs"></i> {{ $dataFile->file }}
                        </button>
                        <div class="mb-1">
                          <x-loading-spinner color="var(--secondary)"
                            target="viewFile('{{ $dataFile->file }}', {{ $dataFile->id }})" />
                        </div>
                      @endforeach
                    @else
                      No Files
                    @endif
                  </div>
                </div>
                <div class="grid grid-cols-2 py-5 px-5">
                  <div class="">
                    <h3 class="text-xl tei-text-secondary font-semibold mb-4">Financial Results</h3>
                    {{-- Check if Score method is Cost/Rating --}}
                    @if ($bidding->score_method == 'Rating')
                      <div class="grid grid-cols-1 sm:grid-cols-2">
                        <div class="text-sm flex flex-col space-y-2">
                          @if ($financialResult[$vendor->id])
                            <div>
                              <span class="tei-text-accent font-semibold">Reserved Price:</span>
                              <span class="mt-1 uppercase font-extrabold ml-2 tei-text-accent text-xs">
                                {{ $financialResult[$vendor->id]['total_reserved_price'] ? 'PHP ' . number_format($financialResult[$vendor->id]['total_reserved_price']) : 'No Reserved Price' }}
                              </span>
                            </div>
                          @endif
                          <div>
                            <span class="tei-text-accent font-semibold">Total Amount:</span>
                            @if ($financialResult[$vendor->id])
                              <span class="mt-1 uppercase font-extrabold ml-2 tei-text-accent text-xs">
                                PHP {{ number_format($financialResult[$vendor->id]['total_amount']) }}</span>
                            @else
                              <span class="mt-1 uppercase font-extrabold ml-2 text-red-500 text-xs">Null</span>
                            @endif
                          </div>
                          <div>
                            <span class="tei-text-accent font-semibold">Difference:</span>
                            @if ($financialResult[$vendor->id])
                              <span class="mt-1 uppercase font-extrabold ml-2 tei-text-accent text-xs">
                                {{ $financialResult[$vendor->id]['difference'] === null || $financialResult[$vendor->id]['difference_percent'] === '' ? '' : 'PHP ' . number_format($financialResult[$vendor->id]['difference']) }}</span>
                            @else
                              <span class="mt-1 uppercase font-extrabold ml-2 text-red-500 text-xs">Null</span>
                            @endif
                          </div>
                          <div>
                            <span class="tei-text-accent font-semibold">Difference (%):</span>
                            @if ($financialResult[$vendor->id])
                              <span class="mt-1 uppercase font-extrabold ml-2 tei-text-accent text-xs">
                                {{ $financialResult[$vendor->id]['difference_percent'] === null || $financialResult[$vendor->id]['difference_percent'] === '' ? '' : number_format($financialResult[$vendor->id]['difference_percent']) . '%' }}</span>
                            @else
                              <span class="mt-1 uppercase font-extrabold ml-2 text-red-500 text-xs">Null</span>
                            @endif
                          </div>
                        </div>

                        <div>
                          <div>
                            <span class="tei-text-accent font-semibold">Scoring Guide</span>
                          </div>
                          <div>
                            <span class="tei-text-accent font-semibold">Rating:</span>
                            @if ($financialResult[$vendor->id])
                              <span class="mt-1 uppercase font-extrabold ml-2 tei-text-accent text-xs">
                                {{ number_format($financialResult[$vendor->id]['score'], 2) }}%</span>
                            @else
                              <span class="mt-1 uppercase font-extrabold ml-2 text-red-500 text-xs">Null</span>
                            @endif

                          </div>
                          <div>
                            @if ($bidding->score_method == 'Rating' && $bidding->reserved_price_switch)
                              <span class="tei-text-accent font-semibold">Status:</span>
                              @if ($financialResult[$vendor->id])
                                @if ($financialResult[$vendor->id]['result'])
                                  <span class="mt-1 uppercase font-extrabold ml-2 text-green-500 text-xs">
                                    Offer is within the reserved price.</span>
                                @else
                                  <span class="mt-1 uppercase font-extrabold ml-2 text-red-500 text-xs">
                                    Disqualified - Offer exceeds the reserved price.</span>
                                @endif
                              @else
                                <span class="mt-1 uppercase font-extrabold ml-2 text-red-500 text-xs">
                                  No Offer.</span>
                              @endif
                            @endif
                          </div>
                        </div>
                      </div>
                    @else
                      <div class="grid grid-cols-1 sm:grid-cols-2">
                        <div class="text-sm flex flex-col space-y-2">
                          <div>
                            <span class="tei-text-accent font-semibold">Total Reserved:</span>
                            @if ($financialResult[$vendor->id])
                              <span class="mt-1 uppercase font-extrabold ml-2 tei-text-accent text-xs">
                                @if ($bidding->reserved_price_switch)
                                  {{ $financialResult[$vendor->id]['total_reserved_price'] ? 'PHP ' . number_format($financialResult[$vendor->id]['total_reserved_price'], 2) : 'NULL' }}
                                @else
                                  No ceiling
                                @endif
                              </span>
                            @else
                              <span class="mt-1 uppercase font-extrabold ml-2 text-red-500 text-xs">Null</span>
                            @endif
                          </div>
                          <div>
                            <span class="tei-text-accent font-semibold">Total Amount:</span>
                            @if ($financialResult[$vendor->id])
                              <span class="mt-1 uppercase font-extrabold ml-2 tei-text-accent text-xs">
                                PHP {{ number_format($financialResult[$vendor->id]['total_amount'], 2) }}</span>
                            @else
                              <span class="mt-1 uppercase font-extrabold ml-2 text-red-500 text-xs">Null</span>
                            @endif
                          </div>
                          <div>
                            <span class="tei-text-accent font-semibold">Difference:</span>
                            @if ($bidding->scrap)
                              @php
                                $difference = $financialResult[$vendor->id]['difference'];
                                // $financialResult[$vendor->id]['total_reserved_price'];
                              @endphp
                              @if ($bidding->reserved_price_switch)
                                <span
                                  class="mt-1 uppercase font-extrabold ml-2 text-xs {{ $difference >= 0 ? 'text-green-500' : 'text-red-500' }}">
                                  PHP {{ number_format($difference, 2) }}</span>
                              @else
                                <span
                                  class="mt-1 uppercase font-extrabold ml-2 text-xs tei-text-accent">{{ $difference }}</span>
                              @endif
                            @else
                              @php
                                if ($financialResult[$vendor->id]) {
                                    $difference =
                                        $financialResult[$vendor->id]['total_reserved_price'] -
                                        $financialResult[$vendor->id]['total_amount'];
                                } else {
                                    $difference = null;
                                }
                              @endphp
                              <span
                                class="mt-1 uppercase font-extrabold ml-2 text-xs {{ $difference >= 0 ? 'text-green-500' : 'text-red-500' }}">
                                {{ $difference >= 0 ? ' PHP ' . number_format($difference, 2) : 'Null' }}</span>
                            @endif
                          </div>

                          <div>
                            <span class="tei-text-accent font-semibold">Status:</span>
                            {{-- @php
                              $vendResult = $results ? collect($results)->where('vendor_id', $vendor->id)->first() : 0;
                              $check = $vendResult ? $vendResult->result : 0
                            @endphp --}}
                            @if ($financialResult[$vendor->id] && (isset($results[$vendor->id]) && $results[$vendor->id]['result']))
                              <span class="mt-1 uppercase font-extrabold ml-2 text-green-500 text-xs">
                                Passed</span>
                            @else
                              <span class="mt-1 uppercase font-extrabold ml-2 text-red-500 text-xs">Failed</span>
                            @endif
                          </div>

                        </div>
                      </div>
                    @endif
                    {{-- Check if Score method is Cost/Rating --}}
                  </div>
                  <div>
                    <h3 class="text-xl tei-text-secondary font-semibold mb-4">Remarks</h3>
                    <textarea id="message" rows="4"
                      class="{{ $errors->has('vendorRemarks') ? ' border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} block p-2.5 w-full text-sm rounded-lg border"
                      placeholder="Enter Remarks" wire:model.live.debounce.250ms="vendorRemarks.{{ $vendor->id }}"></textarea>
                    <div class="flex">
                      <div class="mt-2" wire:loading wire:target="vendorRemarks.{{ $vendor->id }}">
                        <x-loading-spinner color="var(--secondary)" target="vendorRemarks.{{ $vendor->id }}" />
                      </div>
                      <div class="flex items-center justify-center mt-3 text-xs tei-text-accent" wire:loading
                        wire:target="vendorRemarks.{{ $vendor->id }}">
                        <span class="ml-2">Saving...</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach

  </div>


  {{-- Admin Modal --}}
  <div id="admin-modal" tabindex="-1" data-modal-backdrop="static"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-4xl max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <h3 class="tei-text-secondary text-lg font-semibold p-4">
          Admin Financial
        </h3>
        <button type="button"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
          wire:click="closeAdminModal">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        @if ($adminFinancial)
          <div class="p-4 md:p-5 border-t-2 flex flex-col space-y-4">
            <div class="flex flex-row space-x-4">
              <div>
                <label class="font-extrabold tei-text-primary mb-2">Inventory Id: </label>
                <span
                  class="tei-text-accent text-xs uppercase font-semibold">{{ $adminFinancial->inventory_id }}</span>
              </div>
              <div>
                <label class="font-extrabold tei-text-primary mb-2">Description: </label>
                <span
                  class="tei-text-accent text-xs uppercase font-semibold">{{ $adminFinancial->description }}</span>
              </div>
            </div>
            <div class="flex flex-row space-x-4">
              <div>
                <label class="font-extrabold tei-text-primary mb-2">Reserved Price: </label>
                <span class="tei-text-accent text-xs uppercase font-semibold">PHP
                  {{ $adminFinancial ? number_format($adminFinancial->bid_price, 2) : null }}</span>
              </div>
              <div>
                <label class="font-extrabold tei-text-primary mb-2">Quantity: </label>
                <span
                  class="tei-text-accent text-xs uppercase font-semibold">{{ $adminFinancial ? $adminFinancial->quantity : null }}</span>
              </div>
            </div>
            <hr>
            <div class="flex flex-row space-x-4">
              <div>
                <label class="font-extrabold tei-text-primary mb-2">Vendor Price: </label>
                <span
                  class="tei-text-accent text-xs uppercase font-semibold {{ $adminVendorResponse ? '' : 'text-red-500' }}">{{ $adminVendorResponse ? 'PHP ' . number_format($adminVendorResponse->price, 2) : 'No Offer' }}</span>
              </div>
              <div>
                <label class="font-extrabold tei-text-primary mb-2">Tax/Duties/Fees/Levies: </label>
                <span
                  class="tei-text-accent text-xs uppercase font-semibold {{ $adminVendorResponse ? '' : 'text-red-500' }}">{{ $adminVendorResponse ? 'PHP ' . number_format($adminVendorResponse->other_fees, 2) : 'No Offer' }}</span>
              </div>
            </div>

            <div class="flex flex-row space-x-4">
              <div>
                <div class="flex">
                  <span
                    class="inline-flex items-center px-3 text-sm tei-text-light tei-bg-primary border rounded-e-0 border-gray-300 border-e-0 rounded-s-md ">
                    Admin Price
                  </span>
                  <div class="relative">
                    <input type="text" wire:model="adminResponse.admin_price"
                      class="block w-full text-xs tei-text-accent bg-transparent rounded-e-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 tei-focus-secondary peer"
                      placeholder=" " />
                  </div>
                </div>
                <div>
                  @error('adminResponse.admin_price')
                    <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                        class="font-medium">{{ $message }}</span></p>
                  @enderror
                </div>
              </div>
              <div>
                <div>
                  <div class="flex">
                    <span
                      class="inline-flex items-center px-3 text-sm tei-text-light tei-bg-primary border rounded-e-0 border-gray-300 border-e-0 rounded-s-md ">
                      Admin Fees
                    </span>
                    <div class="relative">
                      <input type="text" wire:model="adminResponse.admin_fees"
                        class="block w-full text-xs tei-text-accent bg-transparent rounded-e-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 tei-focus-secondary peer"
                        placeholder=" " />
                    </div>
                  </div>
                </div>
                <div>
                  @error('adminResponse.admin_fees')
                    <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                        class="font-medium">{{ $message }}</span></p>
                  @enderror
                </div>
              </div>
            </div>
          </div>
        @endif
        <div class="flex justify-end p-4 md:p-5 border-t-2 border-gray-200 rounded-b space-x-4">
          <button type="button" wire:click="closeAdminModal"
            class="text-white bg-gray-400 hover:bg-gray-500 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
            Close
          </button>
          <button type="submit" wire:click="submitAdminResponse" wire:loading.remove
            wire:target="submitAdminResponse"
            class="text-white bg-green-500 hover:bg-green-700 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
            Submit
          </button>
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
            wire:target="submitAdminResponse">
            <div class="flex justify-center">
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
  {{-- END Admin Modal --}}
  {{-- File Modal --}}
  <div id="view-file" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-7xl max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
          wire:click="closeFileModal">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        <div class="p-4 md:p-5 text-center ">
          <span class="uppercase tei-text-primary text-lg font-black mb-5">{{ $financialFileName }}</span>
          <hr>
          <div class=" flex justify-center mt-5">
            @if ($fileAttachment)
              <iframe src="{{ $fileAttachment }}" frameborder="1" width="1000" height="850"></iframe>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
  {{-- END File Modal --}}

  @livewire('admin.print-reports-modal')

</div>
@section('content-script')
  <script>
    function toggleArrow(vendorId) {
      var arrowIcon = document.getElementById("arrow-icon-" + vendorId);
      var content = document.getElementById("vendor-content-" + vendorId);

      if (arrowIcon.classList.contains('up-arrow')) {
        arrowIcon.classList.remove('up-arrow');
        arrowIcon.classList.add('down-arrow');

        content.classList.add('content-show');
        content.classList.remove('content-hide');
      } else {
        arrowIcon.classList.remove('down-arrow');
        arrowIcon.classList.add('up-arrow');

        content.classList.remove('content-show');
        content.classList.add('content-hide');
      }
    }
  </script>
@endsection
@script
  <script>
    $wire.on('openAdminModal', () => {
      var modalElement = document.getElementById('admin-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });

    $wire.on('closeAdminModal', () => {
      var modalElement = document.getElementById('admin-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });

    $wire.on('openFileModal', () => {
      var modalElementOpen = document.getElementById('view-file');
      var modalOpen = new Modal(modalElementOpen, {
        backdrop: 'static'
      });
      modalOpen.show();
    });

    $wire.on('closeFileModal', () => {
      var modalElement = document.getElementById('view-file');
      var modal = new Modal(modalElement);
      modal.hide();
    });
  </script>
@endscript
