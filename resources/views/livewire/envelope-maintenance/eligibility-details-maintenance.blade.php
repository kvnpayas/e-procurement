<?php

use Livewire\Volt\Component;
use App\Models\Eligibility;
use App\Models\EligibilityDetails;

new class extends Component {
    public $eligibility, $details, $detail;
    public $fieldName,
        $fieldType,
        $dateValidation = false;
    public $editFieldName, $editFieldType, $switchStatus, $editStatus, $editDateValidation;

    public function mount($eligibilityId)
    {
        $this->eligibility = Eligibility::where('id', $eligibilityId)->first();
        $this->details = $this->getDetails();
    }
    public function getDetails()
    {
        return $this->eligibility->details;
    }
    public function addFieldsModal()
    {
        $this->resetvalidation();
        $this->dispatch('openAddFieldsModal');
    }
    public function editModal($fieldId)
    {
        $this->resetvalidation();
        $this->dispatch('openEditModal');
        $this->detail = $this->details->where('id', $fieldId)->first();
        $this->editFieldName = $this->detail->field;
        $this->editFieldType = $this->detail->field_type;
        $this->switchStatus = $this->detail->status;
        $this->editDateValidation = $this->detail->validate_date ? true : false;
        $this->editStatus = $this->switchStatus == 'Active' ? true : false;
    }
    public function updatedEditStatus()
    {
        $this->switchStatus = $this->editStatus ? 'Active' : 'Inactive';
    }
    public function updatedFieldType($value)
    {
        $this->fieldType = $value;
    }
    public function addFieldsModalClose()
    {
        $this->dispatch('closeAddFieldsModal');
    }
    public function editModalClose()
    {
        $this->dispatch('closeEditModal');
    }

    public function addFields()
    {
        $this->validate(
            [
                'fieldName' => 'required',
                'fieldType' => 'required',
            ],
            [
                'fieldName.required' => 'The field name is required.',
                'fieldType.required' => 'The field type is required.',
            ],
        );

        $data = [
            'eligibility_id' => $this->eligibility->id,
            'field' => $this->fieldName,
            'field_type' => $this->fieldType,
            'validate_date' => $this->dateValidation,
            'status' => 'Active',
            'crtd_user' => Auth::user()->id,
        ];

        EligibilityDetails::create($data);

        if ($this->eligibility->status == 'Inactive') {
            $this->eligibility->update(['status' => 'Active']);
        }

        // return redirect()->route('eligibility-envelope.eligibility-details', $this->eligibility->id)->with('success', 'Successfully added fields.');
        $this->details = $this->getDetails();
        $this->dispatch('closeAddFieldsModal');
        $this->dispatch('success-message', ['message' => 'Successfully added fields.']);
    }
    public function editFields()
    {
        $this->validate(
            [
                'editFieldName' => 'required',
            ],
            [
                'editFieldName.required' => 'The field name is required.',
            ],
        );

        $data = [
            'field' => $this->editFieldName,
            'status' => $this->switchStatus,
            'validate_date' => $this->editDateValidation,
            'upd_user' => Auth::user()->id,
        ];

        $this->detail->update($data);
        if ($this->eligibility->details->where('status', 'Active')->count() == 0) {
            $this->eligibility->update(['status' => 'Inactive']);
        }
        // return redirect()->route('eligibility-envelope.eligibility-details', $this->eligibility->id)->with('success', 'Successfully updated fields.');
        $this->details = $this->getDetails();
        $this->dispatch('closeEditModal');
        $this->dispatch('success-message', ['message' => 'Successfully updated fields.']);
    }
}; ?>

<div class="relative shadow-md sm:rounded-lg">
  <div class="p-5 grid grid-cols-1 sm:grid-cols-3 tei-bg-light gap-4">
    <div>
      <h3 class="tei-text-secondary font-extrabold text-lg">Eligibility Name</h3>
      <span class="text-sm pl-2 tei-text-accent">{{ $eligibility->name }}</span>
    </div>
    <div>
      <h3 class="tei-text-secondary font-extrabold text-lg">Eligibility Description</h3>
      <span class="text-sm pl-2 tei-text-accent">{{ $eligibility->description }}</span>
    </div>
    <div>
      <h3 class="tei-text-secondary font-extrabold text-lg">Status</h3>
      <span
        class="text-sm pl-2 {{ $eligibility->status == 'Active' ? 'text-green-500' : 'text-red-500' }}">{{ $eligibility->status }}</span>
    </div>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
      <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white ">
        Eligibility Details
        <p class="mt-1 text-sm font-normal text-gray-500 ">Add fields for eligibility requirements.
        </p>
        <div class="flex justify-start mt-4">
          <div>

          </div>
          <div>
            @if (roleAccessRights('create'))
              <button type="button" wire:loading.remove
                wire:target="addFieldsModal"
                class="text-white tei-bg-primary hover:bg-sky-900 font-medium rounded-lg text-xs px-5 py-2 me-2 mb-2 hover:scale-110 transition-transform duration-300"
                wire:click.prevent="addFieldsModal">
                Add Fields
              </button>
              <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
                wire:target="addFieldsModal">
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
          </div>
        </div>
      </caption>
      <thead class="text-xs text-gray-700 uppercase tei-bg-light ">
        <tr>
          <th scope="col" class="px-6 py-3">
            ID
          </th>
          <th scope="col" class="px-6 py-3">
            Field Name
          </th>
          <th scope="col" class="px-6 py-3">
            Field Type
          </th>
          <th scope="col" class="px-6 py-3">
            Status
          </th>
          <th scope="col" class="px-6 py-3">
            Action
          </th>
        </tr>
      </thead>
      <tbody>
        @forelse ($details as $detail)
          <tr class="bg-white border-b ">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
              {{ $detail->id }}
            </th>
            <td class="px-6 py-4">
              {{ $detail->field }}
            </td>
            <td class="px-6 py-4">
              {{ $detail->field_type }}
            </td>
            <td class="px-6 py-4">
              <span class="{{ $detail->status == 'Active' ? 'text-green-500' : 'text-red-600' }}">
                {{ $detail->status }}
              </span>
            </td>
            <td class="px-6 py-4">
              <div class="flex gap-4">
                <div>
                  @if (roleAccessRights('update'))
                    <button data-tooltip-target="tooltip-edit-{{ $detail->id }}" type="button"
                      class="hover:scale-125 transition-transform duration-300"
                      wire:click.prevent="editModal({{ $detail->id }})" wire:loading.remove
                    wire:target="editModal({{ $detail->id }})">
                      <i class="fa-solid fa-pen-to-square text-green-600 text-lg"></i>
                    </button>
                    <x-loading-spinner color="var(--secondary)" target="editModal({{ $detail->id }})" />
                  @endif
                  {{-- <div id="tooltip-edit-{{$detail->id}}" role="tooltip"
                  class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-accent rounded-lg shadow-sm opacity-0 tooltip">
                  Edit Field
                  <div class="tooltip-arrow" data-popper-arrow></div>
                </div> --}}
                </div>
              </div>
            </td>
          </tr>
        @empty
          <tr class="bg-white border-b">
            <th scope="row" colspan="100" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center">
              <span class="font-black text-lg tei-text-primary">There are no fields on this eligibility.</span>
            </th>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  {{-- <x-action-message class="me-3" on="alert-eligibility">
    {{ __($alertMessage) }}
  </x-action-message> --}}

  {{-- @include('components.toast-message') --}}

  {{-- Create Eligibility --}}
  <div id="add-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">
            Add Fields
          </h3>
          <button type="button" wire:click.prevent="addFieldsModalClose"
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
        <form wire:submit="addFields">
          <div class="p-4 md:p-5 space-y-4">
            <div class="mb-6">
              <div>
                <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">Field
                  Name</label>
                <input type="text" id="name" wire:model="fieldName" placeholder="Field Name"
                  class="{{ $errors->has('fieldName') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} text-sm rounded-lg block w-full p-1" />
                @error('fieldName')
                  <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                    {{ $message }}
                  </p>
                @enderror
              </div>
              <div class="mt-5">
                <div>
                  <label for="underline_select" class="sr-only">Underline select</label>
                  <select id="underline_select" wire:model.live="fieldType"
                    class="block py-2.5 px-0 w-full sm:w-1/2 text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-900 appearance-none focus:outline-none focus:ring-0 focus:border-gray-900 peer">
                    <option value="">--Select field type--</option>
                    <option value="text">Text</option>
                    <option value="date">Date</option>
                  </select>
                  @error('fieldType')
                    <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                      {{ $message }}
                    </p>
                  @enderror
                </div>
              </div>
              @if ($fieldType == 'date')
                <div class="mt-5">
                  <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">Validate
                    Date</label>
                  <input type="checkbox" value=""
                    class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600"
                    wire:model="dateValidation">
                  <span class="ms-3 text-xs font-medium tei-text-accent">Check this box if you want to add validation
                    (Current date and time)</span>
                </div>
              @endif
            </div>
          </div>
          <!-- Modal footer -->
          <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
            <button type="submit" wire:loading.remove wire:target="addFields"
              class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
              Create Field
            </button>
            <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
              wire:target="addFields">
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
            <button type="button" wire:click.prevent="addFieldsModalClose"
              class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 hover:scale-110 transition-transform duration-300">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  {{-- End Create Eligibility --}}

  {{-- Edit Eligibility --}}
  <div id="edit-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">
            Edit Field
          </h3>
          <button type="button"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
            wire:click.prevent="editModalClose">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <!-- Modal body -->
        <form wire:submit="editFields">
          <div class="p-4 md:p-5 space-y-4">
            <div class="mb-6">
              <div>
                <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">Field
                  Name</label>
                <input type="text" id="name" wire:model="editFieldName" placeholder="Field Name"
                  class="{{ $errors->has('editFieldName') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} text-sm rounded-lg block w-full p-1" />
                @error('editFieldName')
                  <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                    {{ $message }}
                  </p>
                @enderror
              </div>
              <div class="mt-5">
                <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">Field
                  Type</label>
                <span class="font-extrabold tei-text-accent">{{ strtoupper($editFieldType) }}</span>
                @error('editFieldType')
                  <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                    {{ $message }}
                  </p>
                @enderror
              </div>
              @if ($editFieldType == 'date')
                <div class="mt-5">
                  <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">Validate
                    Date</label>
                  <input type="checkbox"
                    class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600"
                    wire:model="editDateValidation">
                  <span class="ms-3 text-xs font-medium tei-text-accent">Check this box if you want to add validation
                    (Current date and time)</span>
                </div>
              @endif
              <div class="mt-5">
                <div>
                  <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">Status</label>
                  <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" value="" class="sr-only peer" wire:model.live="editStatus">
                    <div
                      class="relative w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 focus:outline-none peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-600">
                    </div>
                    <span
                      class="ms-3 text-sm font-medium {{ $editStatus ? 'text-green-500' : 'text-red-500' }}">{{ $switchStatus }}</span>
                  </label>
                </div>
                @error('editStatus')
                  <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                    {{ $message }}
                  </p>
                @enderror
              </div>
            </div>
          </div>
          <!-- Modal footer -->
          <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
            <button type="submit" wire:loading.remove wire:target="editFields"
              class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
              Update Field
            </button>
            <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
              wire:target="editFields">
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
            <button type="button" wire:click.prevent="editModalClose"
              class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 hover:scale-110 transition-transform duration-300">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  {{-- End Edit Eligibility --}}

</div>
@script
  <script>
    $wire.on('openAddFieldsModal', () => {
      var modalElement = document.getElementById('add-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });
    $wire.on('closeAddFieldsModal', () => {
      var modalElement = document.getElementById('add-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });
    $wire.on('openEditModal', () => {
      var modalElement = document.getElementById('edit-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });
    $wire.on('closeEditModal', () => {
      var modalElement = document.getElementById('edit-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });
  </script>
@endscript
