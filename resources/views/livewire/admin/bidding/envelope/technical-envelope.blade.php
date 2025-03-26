<div class="overflow-x-auto shadow-md sm:rounded-lg">
  <table class="w-full text-sm text-left rtl:text-right text-gray-500">
    <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white ">
      <div class="flex justify-between">
        <div>
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
        <div>
          @if ($projectbid->status == 'Active' || $projectbid->status == 'On Hold')
            @if (roleAccessRights(['create', 'update']))
              <button type="button" wire:loading.remove wire:target="openTechnicalModalLists({{ $projectbid->id }})"
                class="text-white tei-bg-primary hover:bg-sky-900 font-medium rounded-lg text-xs px-5 py-2 me-2 mb-2 hover:scale-110 transition-transform duration-300"
                wire:click="openTechnicalModalLists({{ $projectbid->id }})">
                Add Technicals
              </button>
              <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
                wire:target="openTechnicalModalLists({{ $projectbid->id }})">
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
              <a href="{{ route('technical-envelope') }}"
                class="text-white tei-bg-primary hover:bg-sky-900 font-medium rounded-lg text-xs px-5 py-2 me-2 mb-2 hover:scale-110 transition-transform duration-300">
                Create Technicals
              </a>
            @endif
          @endif
        </div>
      </div>
    </caption>
    <thead class="text-xs text-gray-700 uppercase tei-bg-light ">
      <tr>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>ID</span>
            {{-- <button wire:click.prevent="selectedFilters('id')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'id' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'id' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button> --}}
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Name</span>
            {{-- <button wire:click.prevent="selectedFilters('name')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'name' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'name' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button> --}}
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Description</span>
            {{-- <button wire:click.prevent="selectedFilters('description')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'description' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'description' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button> --}}
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Question</span>
            {{-- <button wire:click.prevent="selectedFilters('question')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'question' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'question' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button> --}}
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Question Type</span>
            {{-- <button wire:click.prevent="selectedFilters('question_type')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'question_type' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'question_type' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button> --}}
          </div>
        </th>
        <th scope="col" class="px-6 py-3 w-48">
          <div class="flex justify-between">
            Weight ({{ $projectbid->weights()->where('envelope', 'technical')->first()->weight }}%)
            @if ($projectbid->status == 'Active' || $projectbid->status == 'On Hold')
              @if (roleAccessRights('update'))
                @if ($projectbid->technicals()->count() != 0)
                  @if ($editButton)
                    <button wire:click.prevent="editWeight" wire:loading.remove wire:target="editWeight"
                      class="tei-bg-secondary text-white px-3 py-0.5 rounded hover:scale-125 transition-transform duration-300">Edit</button>
                    <x-loading-spinner color="var(--secondary)" target="editWeight" />
                  @else
                    <button wire:click.prevent="saveWeight" wire:loading.remove wire:target="saveWeight"
                      class="bg-green-500 text-white px-3 py-0.5 rounded hover:scale-125 transition-transform duration-300">Save</button>
                    <x-loading-spinner color="var(--secondary)" target="saveWeight" />
                  @endif
                @endif
              @endif
            @endif
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          Remarks
        </th>
      </tr>
    </thead>
    <tbody>
      @forelse ($technicals as $technical)
        <tr class="bg-white border-b " wire:key="technical-{{ $technical->id }}">
          <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
            {{ $technical->id }}
          </th>
          <td class="px-6 py-4">
            {{ $technical->name }}
          </td>
          <td class="px-6 py-4">
            {{ $technical->description }}
          </td>
          <td class="px-6 py-4">
            {{ $technical->question }}
          </td>
          <td class="px-6 py-4">
            {{ strtoupper($technical->question_type) }}
          </td>
          <td class="px-6 py-4">
            @if ($editButton)
              {{ number_format($technical->biddings()->where('bidding_id', $projectbid->id)->first()->pivot->weight, 2) }}%
            @else
              <input type="number"
                class="w-20 text-xs py-1.5 rounded tei-focus-secondary {{ $errors->has('customWeight.' . $technical->id) ? 'border-red-500 border-2' : '' }}"
                wire:model.live="customWeight.{{ $technical->id }}" />

              @error('customWeight.' . $technical->id)
                <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                  {{ $message }}
                </p>
              @enderror
            @endif
          </td>
          <td class="px-6 py-4">
            <button wire:click.prevent="remarksModal({{ $technical->id }})" wire:loading.remove
              wire:target="remarksModal({{ $technical->id }})"
              class="flex text-white text-xs uppercase bg-green-500 p-1.5 rounded hover:scale-110 transition-transform duration-300">
              <i class="fa-solid fa-comment-dots mr-1"></i> Remarks
            </button>
            <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4" wire:loading
              wire:target="remarksModal({{ $technical->id }})">
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
        </tr>
      @empty
        <tr class="bg-white border-b">
          <th scope="row" colspan="100" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center">
            <span class="font-black text-lg tei-text-primary">No technicals on records.</span>
          </th>
        </tr>
      @endforelse
      @if (!$editButton)
        <tr>
          <td colspan="5"></td>
          <td class="px-6 py-4 font-extrabold">
            <span class="{{ $errors->has('totalWeight') ? 'text-red-500' : '' }}">Total:
              {{ number_format($totalWeight) }}%</span>
            @error('totalWeight')
              <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                {{ $message }}
              </p>
            @enderror
          </td>
        </tr>
      @endif
      <tr class="text-lg">
        <td colspan="4" class="px-6 py-4">
          <label for="myfile" class="text-xs tei-text-secondary">Technical envelope remarks:</label>
          <button wire:click.prevent="remarksModalBid" wire:loading.remove wire:target="remarksModalBid"
            class="text-white text-xs uppercase bg-green-500 p-1.5 rounded hover:scale-110 transition-transform duration-300">
            <i class="fa-solid fa-comment-dots"></i> Remarks
          </button>
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4" wire:loading wire:target="remarksModalBid">
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
      </tr>
    </tbody>
  </table>

  {{ $technicals->links('livewire.layout.pagination') }}

  @livewire('admin.bidding.envelope.technical-lists', ['id' => $projectbid->id])

  {{-- <x-action-message class="me-3" on="alert-technical">
    {{ __($alertMessage) }}
  </x-action-message> --}}

  {{-- Remarks Modal --}}
  <div id="remarks-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">
            Add Remarks
          </h3>
          <button type="button" wire:click="closeRemarksModal" wire:loading.attr="disabled"
            wire:target="saveRemarks"
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
        <form wire:submit="saveRemarks">
          <div class="p-4 md:p-5 space-y-4">
            <div class="mb-2">
              <div>
                <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Name:</label>
                <span class="text-xs uppercase font-extrabold tei-text-accent">{{ $technicalName }}</span>
              </div>
              <div>
                <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Description:</label>
                <span class="text-xs uppercase font-extrabold tei-text-accent">{{ $technicalDesc }}</span>
              </div>
            </div>
            <div class="mb-6">
              <div class="mt-5">
                <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">Remarks</label>
                @if ($projectbid->status == 'Active' || $projectbid->status == 'On Hold')
                  <textarea id="message" rows="4"
                    class="{{ $errors->has('remarks') ? ' border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} block p-2.5 w-full text-sm rounded-lg border"
                    placeholder="Remarks" wire:model="remarks" {{ roleAccessRights(['create', 'update']) ? '' : 'disabled' }}></textarea>
                @else
                  <span
                    class="font-extrabold tei-text-accent text-sm">{{ $technicalRemarksVal ? $technicalRemarksVal : 'No Remarks' }}</span>
                @endif
              </div>
            </div>
          </div>
          <!-- Modal footer -->
          <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
            @if (roleAccessRights(['create', 'update']))
              <button type="submit" wire:loading.remove wire:target="saveRemarks"
                class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
                Add
              </button>
            @endif
            <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
              wire:target="saveRemarks">
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
            <button type="button" wire:click="closeRemarksModal" wire:loading.attr="disabled"
              wire:target="saveRemarks"
              class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10  hover:scale-110 transition-transform duration-300">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  {{-- End Remarks Modal --}}

  {{-- Technical Remarks Modal --}}
  <div id="envelope-remarks-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">
            Add Remarks
          </h3>
          <button type="button" wire:click="closeTechnicalRemarksModal" wire:loading.attr="disabled"
            wire:target="saveEnvelopeRemarks"
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
        <form wire:submit="saveEnvelopeRemarks">
          <div class="p-4 md:p-5 space-y-4">
            <div class="mb-6">
              <div class="mt-5">
                @if ($projectbid->status == 'Active' || $projectbid->status == 'On Hold')
                  <textarea id="message" rows="4"
                    class="{{ $errors->has('envelopeRemarksInput') ? ' border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} block p-2.5 w-full text-sm rounded-lg border"
                    placeholder="Remarks" wire:model="envelopeRemarksInput"
                    {{ roleAccessRights(['create', 'update']) ? '' : 'disabled' }}></textarea>
                @else
                  <span
                    class="font-extrabold tei-text-accent text-sm">{{ $envelopeRemarksInput ? $envelopeRemarksInput : 'No Remarks' }}</span>
                @endif
              </div>
            </div>
          </div>
          <!-- Modal footer -->
          <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
            @if ($projectbid->status == 'Active' || $projectbid->status == 'On Hold')
              @if (roleAccessRights(['create', 'update']))
                <button type="submit" wire:loading.remove wire:target="saveEnvelopeRemarks"
                  class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
                  Add
                </button>
              @endif
            @endif
            <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
              wire:target="saveEnvelopeRemarks">
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
            <button type="button" wire:click="closeTechnicalRemarksModal" wire:loading.attr="disabled"
              wire:target="saveEnvelopeRemarks"
              class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 hover:scale-110 transition-transform duration-300">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  {{-- End Technical Remarks Modal --}}
</div>
@script
  <script>
    $wire.on('openRemarksModal', () => {
      var modalElement = document.getElementById('remarks-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });
    $wire.on('closeRemarksModal', () => {
      var modalElement = document.getElementById('remarks-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });
    $wire.on('openTechnicalRemarksModal', () => {
      var modalElement = document.getElementById('envelope-remarks-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });
    $wire.on('closeTechnicalRemarksModal', () => {
      var modalElement = document.getElementById('envelope-remarks-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });
  </script>
@endscript
