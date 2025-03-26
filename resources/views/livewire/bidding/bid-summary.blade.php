<div>
  <div class="border-b-2 flex">
    <div class="flex">
      <div class="p-5 ">
        <div>
          <label for="title" class=" mr-2 text-lg font-extrabold tei-text-primary ">Project No:</label>
          <span class="text-xs uppercase font-extrabold tei-text-secondary">{{ $project->project_id }}</span>
        </div>
        <div>
          <label for="title" class=" mr-2 text-lg font-extrabold tei-text-primary ">Project Title:</label>
          <span class="text-xs uppercase font-extrabold tei-text-accent">{{ $project->title }}</span>
        </div>
        <div>
          <label for="title" class=" mr-2 text-lg font-extrabold tei-text-primary ">Deadline Date:</label>
          <span
            class="text-xs uppercase font-extrabold tei-text-accent">{{ $project->extend_date ? date('F j,Y @ h:i A', strtotime($project->extend_date)) : date('F j,Y @ h:i A', strtotime($project->deadline_date)) }}</span>
        </div>

      </div>
      <div class="p-5 ">
        <div>
          <label for="title" class=" mr-2 text-lg font-extrabold tei-text-primary ">Score Method:</label>
          <span class="text-xs uppercase font-extrabold tei-text-accent">
            {{ $project->score_method == 'Cost' ? 'Best Financial Offer' : 'Rating' }}
          </span>
        </div>
        <div>
          <label for="title" class=" mr-2 text-lg font-extrabold tei-text-primary ">Sales:</label>
          <span class="text-xs uppercase font-extrabold tei-text-accent">{{ $project->scrap ? 'Yes' : 'No' }}</span>
        </div>
        <div>
          <label for="title" class=" mr-2 text-lg font-extrabold tei-text-primary ">Vendor Status:</label>
          <span
            class="text-xs uppercase font-extrabold {{ $vendorStatus->complete ? 'text-green-600' : 'text-red-500' }}">
            {{ $vendorStatus->complete ? 'Submitted' : 'Not Submitted' }}
          </span>
        </div>
      </div>
    </div>
  </div>
  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <div class="w-full p-5">
      <div class="w-1/2 ">
        <span class="text-lg font-semibold tei-text-secondary">Summary</span>
        <p class="mt-1 text-sm font-normal text-gray-500 ">
          You can review all the details of your submitted bid to ensure everything is accurate and complete.
        </p>
      </div>
      <div class="mt-10 gap-10 flex flex-col">
        @if ($project->eligibility)
          <div>
            <div>
              <h3 class="text-lg uppercase font-bold tei-text-secondary">
                Eligibility
              </h3>
              <p class="text-sm tei-text-accent">
                Status and List of All Submitted Eligibilities
              </p>
            </div>
            <div class="mt-5">
              @php
                $bidEligibilityRemarks = $project->envelopeRemarks->where('envelope', 'eligibility')->first();
                $eligibilityRemarks = $bidEligibilityRemarks ? $bidEligibilityRemarks->remarks : null;
              @endphp
              <span class=" font-bold tei-text-primary">
                Remarks:
              </span>
              <span class="text-sm tei-text-accent"> {{ $eligibilityRemarks }}</span>
            </div>
            <div class="mt-5">
              <span class="font-bold tei-text-primary">
                Status:
              </span>
              <span
                class="text-xs font-extrabold {{ $results['Eligibilities']['status'] ? 'text-green-500' : 'text-red-500' }}">
                {{ $results['Eligibilities']['status'] ? 'All Eligbility Requirements Submitted Successfully' : 'Eligbility Requirements Not Submitted' }}
              </span>
            </div>
            <div class="mt-5">
              <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase tei-bg-light ">
                  <tr>
                    <th scope="col" class="px-6 py-3">
                      Eligibility ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Eligibility Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Eligibility Description
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Attachments
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Remarks
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($results['Eligibilities']['eligibilityData'] as $eligibility)
                    <tr
                      class="bg-white border-b text-extrabold hover:bg-neutral-200 hover:shadow-xl transition duration-300"
                      wire:key="eligibility-{{ $eligibility['id'] }}" wire:ignore.self>
                      <th scope="row" class="px-6 tei-text-secondary whitespace-nowrap ">
                        {{ $eligibility['id'] }}
                      </th>
                      <td class="px-6">
                        {{ $eligibility['name'] }}
                      </td>
                      <td class="px-6">
                        {{ $eligibility['description'] }}
                      </td>
                      <td class="px-6 whitespace-nowrap">
                        @if (isset($eligibility['files']) && $eligibility['files'])
                          @foreach ($eligibility['files'] as $file)
                            <button wire:click.prevent="viewFile('{{ $file }}', 'eligibility')"
                              class="block mt-2 hover:scale-110 transition-transform duration-300 mr-4 bg-green-600 rounded-md px-2 py-1 text-white text-xs"><i
                                class="fa-solid fa-file-pdf text-xs"></i> {{ $file }}</button>
                          @endforeach
                        @endif
                      </td>
                      <td class="px-6">
                        @if ($eligibility['status'])
                          <i class="fa-solid fa-circle-check text-green-600"></i>
                        @else
                          <i class="fa-solid fa-circle-xmark text-red-600"></i>
                        @endif
                      </td>
                      <td class="px-6">
                        {{-- <span id="icon.{{ $eligibility['id'] }}" class="cursor-pointer"
                      wire:click.prevent="eligibilityModal({{ $eligibility['id'] }})">
                      <i class="fa-solid fa-circle-plus text-green-800"></i> show
                      more
                    </span> --}}
                      </td>
                    </tr>
                  @empty
                    <tr class="bg-white border-b">
                      <th scope="row" colspan="100" class="px-6 py-4 text-center whitespace-nowrap ">
                        <span class="text-lg text-red-500">No Eligiblity Requirements Submitted.</span>
                      </th>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        @endif
        @if ($project->technical)
          <div>
            <div>
              <h3 class="text-lg uppercase font-bold tei-text-secondary">
                Technical
              </h3>
              <p class="text-sm tei-text-accent">
                Status and List of All Technicals
              </p>
            </div>
            <div class="mt-5">
              <span class="font-bold tei-text-primary">
                Status:
              </span>
              <span
                class="text-xs font-extrabold {{ $results['Technicals']['status'] ? 'text-green-500' : 'text-red-500' }}">
                {{ $results['Technicals']['status'] ? 'All Technical Requirements Submitted Successfully' : 'Technical Requirements Not Submitted' }}
              </span>
            </div>
            <div class="mt-5">
              @php
                $bidRemarks = $project->envelopeRemarks->where('envelope', 'technical')->first();
                $remarks = $bidRemarks ? $bidRemarks->remarks : null;
              @endphp
              <span class=" font-bold tei-text-primary">
                Remarks:
              </span>
              <span class="text-sm tei-text-accent"> {{ $remarks }}</span>
            </div>
            <div class="mt-5">
              <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase tei-bg-light ">
                  <tr>
                    <th scope="col" class="px-6 py-3">
                      Question
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Response
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Attachments
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Remarks
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($results['Technicals']['technicalData'] as $technical)
                    <tr
                      class="bg-white border-b text-extrabold hover:bg-neutral-200 hover:shadow-xl transition duration-300"
                      wire:key="technical-{{ $technical['id'] }}" wire:ignore.self>
                      <th scope="row" class="px-6 tei-text-secondary whitespace-nowrap ">
                        {{ $technical['question'] }}
                      </th>
                      <td class="px-6">
                        @if ($technical['type'] == 'checkbox')
                          {{ $technical['answer'] ? 'Yes' : 'No' }}
                        @else
                          {{ $technical['answer'] }}
                        @endif

                      </td>
                      <td class="px-6 whitespace-nowrap">
                        @if (isset($technical['files']) && $technical['files'])
                          @foreach ($technical['files'] as $file)
                            <button wire:click.prevent="viewFile('{{ $file }}', 'technical')"
                              class="block mt-2 hover:scale-110 transition-transform duration-300 mr-4 bg-green-600 rounded-md px-2 py-1 text-white text-xs"><i
                                class="fa-solid fa-file-pdf text-xs"></i> {{ $file }}</button>
                          @endforeach
                        @endif
                      </td>
                      <td class="px-6">
                        {{ $technical['remarks'] }}
                      </td>
                    </tr>
                  @empty
                    <tr class="bg-white border-b">
                      <th scope="row" colspan="100" class="px-6 py-4 text-center whitespace-nowrap ">
                        <span class="text-lg text-red-500">No Technical Requirements Submitted.</span>
                      </th>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        @endif
        @if ($project->financial)
          <div>
            <div>
              <h3 class="text-lg uppercase font-bold tei-text-secondary">
                Financial
              </h3>
              <p class="text-sm tei-text-accent">
                Status and List of all financial offers
              </p>
            </div>
            <div class="mt-5">
              <span class="font-bold tei-text-primary">
                Status:
              </span>
              <span
                class="text-xs font-extrabold {{ $results['Financials']['status'] ? 'text-green-500' : 'text-red-500' }}">
                {{ $results['Financials']['status'] ? 'Financial Offer Submitted Successfully' : 'Financial Offer Not Submitted' }}
              </span>
            </div>
            <div class="mt-5">
              @php
                $bidRemarks = $project->envelopeRemarks->where('envelope', 'technical')->first();
                $remarks = $bidRemarks ? $bidRemarks->remarks : null;
              @endphp
              <span class=" font-bold tei-text-primary">
                Remarks:
              </span>
              <span class="text-sm tei-text-accent"> {{ $remarks }}</span>
            </div>
            <div class="mt-5">
              <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase tei-bg-light ">
                  <tr>
                    <th scope="col" class="px-6 py-3">
                      Inventory Id
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Description
                    </th>
                    <th scope="col" class="px-6 py-3">
                      UOM
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Quantity
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Price/Item(PHP)
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Tax/Duties/Fees/Levies
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Total
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Remarks
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($results['Financials']['financialData'] as $financial)
                    <tr
                      class="bg-white border-b text-extrabold hover:bg-neutral-200 hover:shadow-xl transition duration-300"
                      wire:key="technical-{{ $financial['id'] }}" wire:ignore.self>
                      <th scope="row" class="px-6 tei-text-secondary whitespace-nowrap ">
                        {{ $financial['inventory_id'] }}
                      </th>
                      <td class="px-6">
                        {{ $financial['description'] }}
                      </td>
                      <td class="px-6">
                        {{ $financial['uom'] }}
                      </td>
                      <td class="px-6">
                        {{ $financial['quantity'] }}
                      </td>
                      <td class="px-6">
                        PHP {{ number_format($financial['price'], 2) }}
                      </td>
                      <td class="px-6">
                        PHP {{ number_format($financial['other_fees'], 2) }}
                      </td>
                      <td class="px-6">
                        PHP {{ number_format($financial['total'], 2) }}
                      </td>
                      <td class="px-6">
                      </td>
                    </tr>

                  @empty
                    <tr class="bg-white border-b">
                      <th scope="row" colspan="100" class="px-6 py-4 text-center whitespace-nowrap ">
                        <span class="text-lg text-red-500">No Financial Offer.</span>
                      </th>
                    </tr>
                  @endforelse
                  @if ($results['Financials']['status'])
                    <tr class="text-lg font-extrabold">
                      <td colspan="6" align="right">Grand Total: </td>
                      <td class="px-6">
                        PHP
                        {{ number_format(array_sum(array_column($results['Financials']['financialData'], 'total')), 2) }}
                      </td>
                    </tr>
                  @endif

                </tbody>
              </table>
              <div class="">
                <label for="myfile" class="mt-2 text-xs tei-text-accent">Uploaded Financial Offer:</label>
                @if (isset($results['Financials']['attachments']) && $results['Financials']['attachments'])
                  @foreach ($results['Financials']['attachments'] as $file)
                    <button wire:click.prevent="viewFile('{{ $file }}', 'financial')"
                      class="block mt-2 hover:scale-110 transition-transform duration-300 mr-4 bg-green-600 rounded-md px-2 py-1 text-white text-xs"><i
                        class="fa-solid fa-file-pdf text-xs"></i> {{ $file }}</button>
                  @endforeach
                @endif
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
    {{-- <x-action-message class="me-3" on="update-message">
    {{ __($alertMessage) }}
  </x-action-message> --}}

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
  </div>
</div>
@script
  <script>
    $wire.on('openSubmitModal', () => {
      var modalElement = document.getElementById('submit-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });
    $wire.on('closeSubmitModal', () => {
      var modalElement = document.getElementById('submit-modal');
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
{{-- @section('page-script')
  <script>
    document.addEventListener("click", (e) => {
      const elementId = e.target.id;
      const id = elementId.split('.')[1]
      const detailId = document.getElementById('financialDetails.' + id)
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
@endsection --}}
