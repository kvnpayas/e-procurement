<div class="overflow-x-auto shadow-md sm:rounded-lg">
  <div class="p-5">
    <h3 class="text-lg font-semibold tei-text-secondary">Project Bid Details</h3>
    <p class="mt-1 text-sm font-normal text-gray-500 ">All the information about this bid is shown here.
    </p>
  </div>

  <div class="p-5 tei-text-accent">
    <div class="grid grid-1 sm:grid-cols-2 gap-4">
      <div>
        <label for="title" class="block mb-2 text-sm font-extrabold tei-text-primary ">Project Title</label>
        <span class=" font-semibold">{{ $projectBid->title }}</span>
      </div>
      <div class="grid grid-cols-3 gap-5">
        <div>
          <label for="type" class="block mb-2 text-sm font-extrabold tei-text-primary ">Bidding Type</label>
          <span class=" font-semibold uppercase">{{ strtoupper($projectBid->type) }}</span>
        </div>
        <div>
          <label for="sales" class="block mb-2 text-sm font-extrabold tei-text-primary ">Sales</label>
          <span class=" font-semibold uppercase">{{ $projectBid->scrap ? 'Yes' : 'No' }}</span>
        </div>
        <div>
          <label for="user" class="block mb-2 text-sm font-extrabold tei-text-primary ">Created By</label>
          <span class="font-extrabold ">{{ $projectBid->created_user->name }}</span>
        </div>
      </div>
    </div>

    <div class="grid grid-1 sm:grid-cols-2 gap-4 mt-2">
      <div class="grid grid-cols-2 gap-4 py-4">
        <div>
          <label for="budget" class="block mb-2 text-sm font-extrabold tei-text-primary ">Budget</label>
          <span class="font-extrabold  uppercase">{{ $projectBid->budget_id ? $projectBid->budget_id : 'N/A' }}</span>
        </div>
        <div>
          <label for="projectType" class="block mb-2 text-sm font-extrabold tei-text-primary ">Project Type</label>
          <span
            class="font-extrabold  uppercase">{{ $projectBid->icss_project_id ? $projectBid->icss_project_id : 'N/A' }}</span>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-4 tei-border-accent rounded-lg py-4 px-2">
        <div>
          <label for="reservedPrice" class="block mb-2 text-sm font-extrabold tei-text-primary ">Reserved Price
            (PHP)</label>
          @if ($projectBid->reserved_price_switch)
            <span class=" font-semibold">PHP {{ number_format($projectBid->reserved_price, 2) }}</span>
          @else
            <span class=" font-semibold">The reserved price has no ceiling.</span>
          @endif
        </div>
        <div>
          <label for="settings" class="block mb-2 text-sm font-extrabold tei-text-primary ">Reflect Price to
            vendor</label>
          <span class=" font-semibold uppercase">{{ $projectBid->reflect_price ? 'Yes' : 'No' }}</span>
        </div>
      </div>
    </div>

    <div class="flex space-x-10 mt-5">
      <div>
        <label for="deadline" class="block mb-2 text-sm font-extrabold tei-text-primary ">Deadline date</label>
        <span class="font-semibold uppercase">{{ date('F j,Y @ h:i A', strtotime($projectBid->deadline_date)) }}</span>
      </div>
      @if ($projectBid->extend_date)
        <div>
          <label for="deadline" class="block mb-2 text-sm font-extrabold tei-text-primary ">Extend Deadline date</label>
          <span class="font-semibold uppercase">{{ date('F j,Y @ h:i A', strtotime($projectBid->extend_date)) }}</span>
        </div>
      @endif
    </div>

    <hr class="mt-5 border-2 tei-border-accent">
    <div class="grid grid-1 sm:grid-cols-2">
      <div class="mt-5">
        <div class="mb-5">
          <label for="deadline" class="block mb-2 text-sm font-extrabold tei-text-primary ">Score Method</label>
          <span class="font-semibold">{{ $projectBid->score_method }} Based</span>
        </div>
        <label for="envelopes" class="block mb-2 text-sm font-extrabold tei-text-primary ">Envelopes</label>
        <div class="flex flex-col font-semibold space-y-4 px-2 text-sm">
          <div>
            <span class="block uppercase {{ $projectBid->eligibility ? 'text-green-500' : 'text-red-500' }}">
              Eligibility
              @if ($projectBid->eligibility)
                <i class="fa-solid fa-circle-check text-green-600"></i>
              @else
                <i class="fa-solid fa-circle-xmark text-red-600"></i>
              @endif
            </span>
          </div>
          <div>
            <span class="block uppercase {{ $projectBid->technical ? 'text-green-500' : 'text-red-500' }}">
              Technical
              @if ($projectBid->technical)
                <i class="fa-solid fa-circle-check text-green-600"></i>
              @else
                <i class="fa-solid fa-circle-xmark text-red-600"></i>
              @endif
            </span>
            <span class="text-xs">Weight:
              {{ $projectBid->technical ? $projectBid->weights->where('envelope', 'technical')->first()->weight . ' %' : 'N/A' }}</span>
          </div>
          <div>
            <span class="block uppercase {{ $projectBid->financial ? 'text-green-500' : 'text-red-500' }}">
              Financial
              @if ($projectBid->financial)
                <i class="fa-solid fa-circle-check text-green-600"></i>
              @else
                <i class="fa-solid fa-circle-xmark text-red-600"></i>
              @endif
            </span>
            <span class="text-xs">Weight:
              {{ $projectBid->financial ? $projectBid->weights->where('envelope', 'financial')->first()->weight . ' %' : 'N/A' }}</span>
          </div>
        </div>
      </div>

      <div class="mt-5 w-full">
        <div class="mb-5">
          <label for="instruction" class="block mb-2 text-sm font-extrabold tei-text-primary ">Instruction
            Details</label>
          <span class="text-xs">
            {!! nl2br(e($projectBid->instruction_details ? $projectBid->instruction_details : 'N/A')) !!}
          </span>
        </div>
        <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">Instruction
          Attachment</label>
        @if (!$projectBid->projectBidFiles->isEmpty())
          @foreach ($projectBid->projectBidFiles as $file)
            <button wire:click.prevent="openFileModal('{{ $file->file_name }}')"
              class="mb-5 block hover:scale-110 transition-transform duration-300 mr-4 tei-bg-primary rounded-md px-2 py-1 text-white text-xs"><i
                class="fa-solid fa-file-pdf"></i> {{ $file->file_name }}</button>
          @endforeach
        @else
          <span class="font-semibold uppercase">N/A</span>
        @endif

      </div>

    </div>

    <hr class="mt-5 border-2 tei-border-accent">

    <div class="text-center mt-4">
      <button wire:click="back"
        class="text-white tei-bg-primary hover:bg-sky-900 font-semibold rounded-lg text-lg px-5 py-2 me-2 mb-2 hover:scale-110 transition-transform duration-300"
        type="submit">Back</button>
    </div>
  </div>
  {{-- {{ $classes->links('livewire.layout.pagination') }} --}}
  {{-- 
  <x-action-message class="me-3" on="alert-message">
    {{ __($alertMessage) }}
  </x-action-message>  --}}

  {{-- File Modal --}}
  <div id="view-file" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-7xl max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">
            File
          </h3>
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
        </div>

        <div class="p-4 md:p-5 text-center">
          <div class=" flex justify-center">
            <iframe src="{{ $attachment }}" frameborder="1" width="1000" height="850"></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
  {{-- END File Modal --}}
</div>

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
