  <div>
    {{-- review Modal --}}
    <div id="review-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
      class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
      <div class="relative p-4 w-full max-w-7xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
          <!-- Modal header -->
          <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
            <h3 class="text-xl font-extrabold tei-text-primary">
              Summary
            </h3>
            <button type="button" wire:click="closeReviewVendorModal"
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
          @if ($selectedVendor)
            <div>
              {{-- Vendor Details --}}
              <div class="p-5">
                <h3 class="text-lg uppercase font-extrabold tei-text-primary">Vendor Details</h3>
                <table>
                  <tbody class="text-xs uppercase tei-text-accent font-semibold px-5">
                    <tr>
                      <td class="pl-5 ">
                        <label class="tei-text-secondary">Vendor Id</label>
                      </td>
                      <td class="text-left ">
                        <span class="pl-4">{{ $selectedVendor->id }}</span>
                      </td>
                      <td class="pl-5 ">
                        <label class="tei-text-secondary">Vendor Name</label>
                      </td>
                      <td class="text-left ">
                        <span class="pl-4">{{ $selectedVendor->name }}</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="pl-5 ">
                        <label class="tei-text-secondary">Email</label>
                      </td>
                      <td class="text-left ">
                        <span class="pl-4 normal-case">{{ $selectedVendor->email }}</span>
                      </td>
                      <td class="pl-5 ">
                        <label class="tei-text-secondary">Contact Number</label>
                      </td>
                      <td class="text-left ">
                        <span class="pl-4">{{ $selectedVendor->number }}</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="pl-5 ">
                        <label class="tei-text-secondary">Address</label>
                      </td>
                      <td colspan="3" class="">
                        <span class="pl-4">{{ $selectedVendor->address }}</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <hr>
              {{-- End Vendor Details --}}

              {{-- Envelopes --}}
              <div class="p-5 flex flex-col gap-4 tei-bg-light">
                <h3 class="text-lg uppercase font-extrabold tei-text-primary">Envelopes</h3>
                @if ($project->eligibility)
                  @if ($finalEnvelopes['eligibility'])
                    <div class="shadow-lg bg-white rounded">
                      <div>
                        <h3 class="uppercase text-lg tei-text-secondary font-semibold p-4">Eligibility</h3>
                      </div>
                      <div class="px-5">
                        <table class="w-full text-xs tei-text-accent uppercase text-left  rounded-lg">
                          <thead class="tei-bg-light">
                            <tr>
                              <th class="px-6 py-2">
                                Name
                              </th>
                              <th class="px-6 py-2">
                                Attachment(s)
                              </th>
                              <th class="px-6 py-2">
                                Status
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($finalEnvelopes['eligibility']['data'] as $eligibility)
                              <tr class="hover:shadow-lg hover:bg-neutral-300 border-b">
                                <td class="px-6 py-2 normal-case">
                                  {{ $eligibility['name'] }}
                                </td>
                                <td class="px-6 py-2">
                                  @if (isset($eligibility['files']) && $eligibility['files'])
                                    @foreach ($eligibility['files'] as $file)
                                      <button wire:click.prevent="viewFile('{{ $file }}', 'eligibility')"
                                        wire:loading.remove wire:target="viewFile('{{ $file }}', 'eligibility')"
                                        class="block mt-2 hover:scale-110 transition-transform duration-300 mr-4 bg-green-600 rounded-md px-2 py-1 text-white text-xs"><i
                                          class="fa-solid fa-file-pdf text-xs"></i> {{ $file }}</button>
                                      <x-loading-spinner color="var(--secondary)"
                                        target="viewFile('{{ $file }}', 'eligibility')" />
                                    @endforeach
                                  @endif
                                </td>
                                <td class="px-6 py-2">
                                  @if ($eligibility['result'])
                                    <span><i class="fa-solid fa-circle-check text-green-500"></i></span>
                                  @else
                                    <span><i class="fa-solid fa-circle-xmark text-red-500"></i></span>
                                  @endif
                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                      <div class="grid grid-cols-2 gap-2">
                        <div class="p-5">
                          <label class="text-sm font-extrabold tei-text-primary">Status:</label>
                          <span
                            class="pl-4 text-sm uppercase font-semibold {{ $finalEnvelopes['eligibility']['result'] ? 'text-green-500' : 'text-red-500' }}">{{ $finalEnvelopes['eligibility']['status'] ? 'Passed' : 'Failed' }}</span>
                        </div>
                        <div class="p-5">
                          <label class="text-sm font-extrabold tei-text-primary">Remarks:</label>
                          <span
                            class="pl-4 text-sm tei-text-accent">{{ $finalEnvelopes['eligibility']['vendor_remarks'] }}</span>
                        </div>
                      </div>
                    </div>
                  @endif
                @endif
                @if ($project->technical)
                  @if ($finalEnvelopes['technical'])
                    <div class="shadow-lg bg-white rounded">
                      <div>
                        <h3 class="uppercase text-lg tei-text-secondary font-semibold p-4">Technical</h3>
                      </div>
                      <div class="px-5">
                        <table class="w-full text-xs tei-text-accent uppercase text-left rounded-lg">
                          <thead class="tei-bg-light">
                            <tr>
                              <th class="px-6 py-2">
                                Technical Question
                              </th>
                              <th class="px-6 py-2">
                                Passing
                              </th>
                              <th class="px-6 py-2">
                                Response
                              </th>
                              <th class="px-6 py-2">
                                Attachment(s)
                              </th>
                              <th class="px-6 py-2">
                                Status
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($finalEnvelopes['technical']['data'] as $technical)
                              <tr class="hover:shadow-lg hover:bg-neutral-300 border-b">
                                <td class="px-6 py-2 normal-case">
                                  {{ $technical['question'] }}
                                </td>
                                <td class="px-6 py-2">
                                  @if ($technical['from'] && $technical['to'])
                                    <span>{{ $technical['from'] }} - {{ $technical['to'] }}</span>
                                  @elseif ($technical['from'] || $technical['to'])
                                    <span>{{ $technical['from'] }} - * </span>
                                  @endif
                                </td>
                                <td class="px-6 py-2">
                                  @if ($technical['answer'])
                                    @if ($technical['admin_answer'])
                                      <span>{{ $technical['answer'] }}</span>
                                    @else
                                      <span>{{ $technical['answer'] }}</span>
                                    @endif
                                  @else
                                    <span class="text-red-500">NULL</span>
                                  @endif
                                </td>
                                <td class="px-6 py-2">
                                  @if (isset($technical['files']) && $technical['files'])
                                    @foreach ($technical['files'] as $file)
                                      <button wire:click.prevent="viewFile('{{ $file }}', 'technical')"
                                        wire:loading.remove wire:target="viewFile('{{ $file }}', 'technical')"
                                        class="block mt-2 hover:scale-110 transition-transform duration-300 mr-4 bg-green-600 rounded-md px-2 py-1 text-white text-xs"><i
                                          class="fa-solid fa-file-pdf text-xs"></i> {{ $file }}</button>
                                      <x-loading-spinner color="var(--secondary)"
                                        target="viewFile('{{ $file }}', 'technical')" />
                                    @endforeach
                                  @endif
                                </td>
                                <td class="px-6 py-2 normal-case">
                                  @if ($technical['score'] == 'Fully Compliant')
                                    <span class="text-green-600">{{ $technical['score'] }}</span>
                                  @elseif($technical['score'] == 'Partially Compliant')
                                    <span class="text-yellow-600">{{ $technical['score'] }}</span>
                                  @else
                                    <span class="text-red-600">{{ $technical['score'] }}</span>
                                  @endif

                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                      <div class="grid grid-cols-2 gap-2">
                        <div class="p-5">
                          <label class="text-sm font-extrabold tei-text-primary">Status:</label>
                          <span
                            class="pl-4 text-sm uppercase font-semibold {{ $finalEnvelopes['technical']['result'] ? 'text-green-500' : 'text-red-500' }}">{{ $finalEnvelopes['technical']['result'] ? 'Passed' : 'Failed' }}</span>
                        </div>
                        <div class="p-5">
                          <label class="text-sm font-extrabold tei-text-primary">Remarks:</label>
                          <span
                            class="pl-4 text-sm tei-text-accent">{{ $finalEnvelopes['technical']['vendor_remarks'] }}</span>
                        </div>
                      </div>
                    </div>
                  @else
                    <div class="shadow-lg bg-white rounded">
                      <div>
                        <h3 class="uppercase text-lg tei-text-secondary font-semibold p-4">Technical</h3>
                      </div>
                      <div class="px-5">
                        <label class="text-kg font-extrabold tei-text-primary">Status:</label>
                        <span class="pl-4 text-sm uppercase font-semibold text-red-500">The vendor failed to pass
                          previous
                          envelopes.</span>
                      </div>
                    </div>
                  @endif
                @endif
                @if ($project->financial)
                  @if ($finalEnvelopes['financial'])
                    <div class="shadow-lg bg-white rounded">
                      <div>
                        <h3 class="uppercase text-lg tei-text-secondary font-semibold p-4">Financial</h3>
                      </div>
                      <div class="px-5">
                        <table class="w-full text-xs tei-text-accent uppercase text-left rounded-lg">
                          <thead class="tei-bg-light">
                            <tr>
                              <th class="px-6 py-2">
                                Inventory Id
                              </th>
                              <th class="px-6 py-2">
                                Description
                              </th>
                              <th class="px-6 py-2">
                                Reserved Price
                              </th>
                              <th class="px-6 py-2">
                                Vendor Price
                              </th>
                              <th class="px-6 py-2">
                                Tax/Duties/Fees/levies
                              </th>
                              <th class="px-6 py-2">
                                Quantity
                              </th>
                              <th class="px-6 py-2">
                                Total Amount
                              </th>
                              <th class="px-6 py-2">
                                Status
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($finalEnvelopes['financial']['data'] as $financial)
                              <tr class="hover:shadow-lg hover:bg-neutral-300 border-b">
                                <td class="px-6 py-2">
                                  <button class="underline"
                                    wire:click="getHistoryModal({{$project->id}}, {{$selectedVendor->id}}, '{{ $financial['inventory_id'] }}')">
                                    {{ $financial['inventory_id'] }}
                                  </button>
                                </td>
                                <td class="px-6 py-2 normal-case">
                                  {{ $financial['description'] }}
                                </td>
                                <td class="px-6 py-2">
                                  PHP {{ number_format($financial['reserved_price'], 2) }}
                                </td>
                                <td class="px-6 py-2 ">
                                  @if ($financial['price'])
                                    PHP {{ number_format($financial['price'], 2) }}
                                  @else
                                    <span class="text-red-500">NULL</span>
                                  @endif
                                </td>
                                <td class="px-6 py-2">
                                  @if ($financial['other_fees'])
                                    PHP {{ number_format($financial['other_fees'], 2) }}
                                  @else
                                    <span class="text-red-500">NULL</span>
                                  @endif
                                </td>
                                <td class="px-6 py-2">
                                  {{ $financial['quantity'] }}
                                </td>
                                <td class="px-6 py-2">
                                  @if ($financial['amount'])
                                    PHP {{ number_format($financial['amount'], 2) }}
                                  @else
                                    <span class="text-red-500">NULL</span>
                                  @endif
                                </td>
                                <td class="px-6 py-2">

                                  @if ($financial['price'])
                                    @if ($project->score_method == 'Cost')
                                      @if ($financial['price'] > 0)
                                        <span><i class="fa-solid fa-circle-check text-green-500"></i></span>
                                      @else
                                        <span><i class="fa-solid fa-circle-xmark text-red-500"></i></span>
                                      @endif
                                    @else
                                      @if ($financial['price'] <= $financial['reserved_price'])
                                        <span><i class="fa-solid fa-circle-check text-green-500"></i></span>
                                      @else
                                        <span><i class="fa-solid fa-circle-xmark text-red-500"></i></span>
                                      @endif
                                    @endif
                                  @else
                                    <span class="text-red-500">NULL</span>
                                  @endif
                                </td>
                              </tr>
                            @endforeach
                            <tr>
                              <td colspan="6" class="px-6 py-2"></td>
                              <td class="px-6 py-2 whitespace-nowrap">
                                Total: PHP
                                {{ number_format($finalEnvelopes['financial']['grand_total'], 2) }}
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="p-5 flex flex-col gap-4">
                        <div class="">
                          <div>
                            <label for="myfile" class="mt-2 text-xs tei-text-accent">Uploaded Financial
                              Offer:</label>
                          </div>
                          @if (isset($finalEnvelopes['financial']['files']) && $finalEnvelopes['financial']['files'])
                            @foreach ($finalEnvelopes['financial']['files'] as $file)
                              <button wire:click.prevent="viewFile('{{ $file }}', 'financial')"
                                wire:loading.remove wire:target="viewFile('{{ $file }}', 'financial')"
                                class="block mt-2 hover:scale-110 transition-transform duration-300 mr-4 bg-green-600 rounded-md px-2 py-1 text-white text-xs"><i
                                  class="fa-solid fa-file-pdf text-xs"></i> {{ $file }}</button>
                              <x-loading-spinner color="var(--secondary)"
                                target="viewFile('{{ $file }}', 'financial')" />
                            @endforeach
                          @endif
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                          <div>
                            <div>
                              <label class="text-sm font-extrabold tei-text-primary">Total Reserved Price:</label>
                              <span class="pl-4 text-sm uppercase font-semibold tei-text-accent">
                                @if ($project->reserved_price_switch)
                                  PHP
                                  {{ number_format(collect($finalEnvelopes['financial']['data'])->sum('total_reserved_price'), 2) }}
                                @else
                                  No Ceiling
                                @endif
                              </span>
                            </div>


                            <div>
                              <label class="text-sm font-extrabold tei-text-primary">Total Amount:</label>
                              <span class="pl-4 text-sm uppercase font-semibold tei-text-accent">PHP
                                {{ number_format($finalEnvelopes['financial']['grand_total'], 2) }}</span>
                            </div>
                            <div>
                              <label class="text-sm font-extrabold tei-text-primary">Status:</label>
                              <span
                                class="pl-4 text-sm uppercase font-semibold {{ $finalEnvelopes['financial']['result'] ? 'text-green-500' : 'text-red-500' }}">{{ $finalEnvelopes['financial']['result'] ? 'Passed' : 'Failed' }}</span>
                            </div>
                          </div>
                          <div class="">
                            <label class="text-sm font-extrabold tei-text-primary">Remarks:</label>
                            <span
                              class="pl-4 text-sm tei-text-accent">{{ $finalEnvelopes['financial']['vendor_remarks'] }}</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  @else
                    <div class="shadow-lg bg-white rounded">
                      <div>
                        <h3 class="uppercase text-lg tei-text-secondary font-semibold p-4">Financial</h3>
                      </div>
                      <div class="px-5">
                        <label class="text-lg font-extrabold tei-text-primary">Status:</label>
                        <span class="pl-4 text-sm uppercase font-semibold text-red-500">The vendor failed to pass
                          previous
                          envelopes.</span>
                      </div>
                    </div>
                  @endif
                @endif
              </div>
              {{-- End Envelopes --}}
            </div>
          @endif

          <div class="flex justify-center p-4 md:p-5 border-t border-gray-200 rounded-b">
            <button wire:click="closeReviewVendorModal"
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
    {{-- END review Modal --}}

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
            <span class="uppercase tei-text-primary text-lg font-black mb-5">{{ $fileName }}</span>
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

    @livewire('admin.modal.inventory-history')
  </div>

  @script
    <script>
      $wire.on('openReviewVendorModal', () => {
        console.log(1);
        var modalElementOpen = document.getElementById('review-modal');
        var modalOpen = new Modal(modalElementOpen, {
          backdrop: 'static'
        });
        modalOpen.show();
      });

      $wire.on('closeReviewVendorModal', () => {
        var modalElement = document.getElementById('review-modal');
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
