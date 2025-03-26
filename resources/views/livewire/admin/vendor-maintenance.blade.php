<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
  <table class="w-full text-sm text-left rtl:text-right text-gray-500">
    <caption class="p-5 text-lg font-semibold text-left rtl:text-right tei-text-secondary bg-white ">
      Vendors
      <p class="mt-1 text-sm font-normal text-gray-500 ">The lists of vendors ready to participate in bidding.
      </p>
      <div class="flex gap-4 mt-4">
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
              class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-0 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-orange-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Search
              here</label>
          </div>
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
            <button type="button" wire:loading.remove wire:target="registrationModal"
              class="text-white tei-bg-primary hover:bg-sky-900 font-medium rounded-lg text-xs px-5 py-2 hover:scale-110 transition-transform duration-300"
              wire:click="registrationModal">
              Add Vendor
            </button>
            <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
              wire:target="registrationModal">
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
        @endif
      </div>
    </caption>
    <thead class="text-xs text-gray-700 uppercase tei-bg-light ">
      <tr>
        <th scope="col" class="px-6 py-3">
          Vendor Id
        </th>
        <th scope="col" class="px-6 py-3">
          vendor's name
        </th>
        <th scope="col" class="px-6 py-3">
          Email
        </th>
        <th scope="col" class="px-6 py-3">
          Address
        </th>
        <th scope="col" class="px-6 py-3">
          Contact Number
        </th>
        <th scope="col" class="px-6 py-3">
          Registered
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
      @forelse ($vendors as $vendor)
        <tr class="bg-white border-b ">
          <th scope="row" class="px-6 py-2 tei-text-secondary text-gray-900 whitespace-nowrap font-semibold">
            {{ $vendor->id }}
          </th>
          <th scope="row" class="px-6 py-2 tei-text-primary whitespace-nowrap font-semibold">
            {{ $vendor->name }}
          </th>
          <td class="px-6 py-2">
            {{ $vendor->email }}
          </td>
          <td class="px-6 py-2">
            {{ $vendor->address }}
          </td>
          <td class="px-6 py-2">
            {{ $vendor->number }}
          </td>
          <td class="px-6 py-2">
            @if ($vendor->token)
              <i class="fa-solid fa-circle-xmark text-red-600"></i>
            @else
              <i class="fa-solid fa-circle-check text-green-500"></i>
            @endif
          </td>
          <td class="px-6 py-2">
            <span
              class="{{ $vendor->active ? 'text-green-500' : 'text-red-500' }}">{{ $vendor->active ? 'Active' : 'Inactive' }}</span>
          </td>
          <td class="px-6 py-2">
            @if (!$vendor->token)
              @if (roleAccessRights('view'))
                <button wire:click.prevent="viewVendor({{ $vendor->id }})"
                  class="hover:scale-110 transition-transform duration-300 mr-4 tei-bg-primary rounded-md px-2 py-1 text-white text-xs">View</button>
              @endif
              @if (roleAccessRights('update'))
                <button wire:click.prevent="editUser({{ $vendor->id }})" wire:loading.remove
                  wire:target="editUser({{ $vendor->id }})"
                  class="hover:scale-110 transition-transform duration-300 mr-4 bg-green-500 rounded-md px-2 py-1 text-white text-xs"><i
                    class="fa-solid fa-gear"></i></button>
                <x-loading-spinner color="var(--secondary)" target="editUser({{ $vendor->id }})" />
              @endif
            @endif
          </td>
        </tr>
      @empty
        <tr class="bg-white border-b">
          <th scope="row" colspan="100" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center">
            <span class="font-black text-lg tei-text-primary">No vendor records exist.</span>
          </th>
        </tr>
      @endforelse
    </tbody>
  </table>
  {{ $vendors->links('livewire.layout.pagination') }}
  {{-- <x-action-message class="me-3" on="add-vendor-message">
    {{ __($addVendorMessage) }}
  </x-action-message> --}}

  {{-- Create Modal --}}
  <div id="add-vendor" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-lg max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow" wire:loading.remove wire:target="userRegistration">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-semibold tei-text-secondary">
            Add Vendor
          </h3>
          <button type="button"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
            wire:click="closeAddModal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <!-- Modal body -->
        <form wire:submit="vendorRegistration" class="w-full">
          <div class="p-4 md:p-5 space-y-4">
            <p class="mt-1 text-sm font-normal text-gray-500 ">Create users by filling out all the necessary fields.
            </p>
            <div class="mb-6 space-y-4 flex-col">
              <div class="col-span-2">
                <label for="name" class="block mb-2 text-xs font-semibold text-gray-900 uppercase ">Company
                  Name</label>
                <input type="text" wire:model="name"
                  class="text-xs tei-text-accent {{ $errors->has('name') ? ' border border-red-500  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 focus:ring-gray-500 focus:border-gray-500 ' }} text-sm rounded-lg block w-full p-2" />
                @error('name')
                  <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                    {{ $message }}
                  </p>
                @enderror
              </div>
              <div>
                <label for="email" class="block mb-2  text-xs font-semibold text-gray-900 uppercase ">Email</label>
                <input type="email" id="email" wire:model="vendorsEmail"
                  class="text-xs tei-text-accent {{ $errors->has('vendorsEmail') ? ' border border-red-500  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 focus:ring-gray-500 focus:border-gray-500 ' }} text-sm rounded-lg block w-full p-2" />
                @error('vendorsEmail')
                  <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                    {{ $message }}
                  </p>
                @enderror
              </div>
              <div>
                <label for="address"
                  class="block mb-2  text-xs font-semibold text-gray-900 uppercase ">Address</label>
                <textarea id="address" rows="4"
                  class="text-xs tei-text-accent {{ $errors->has('address') ? ' border border-red-500  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 focus:ring-gray-500 focus:border-gray-500 ' }} block p-2.5 w-full text-sm rounded-lg border"
                  placeholder="Address" wire:model="address"></textarea>
                @error('address')
                  <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                    {{ $message }}
                  </p>
                @enderror
              </div>
              <div>
                <label for="number" class="block mb-2  text-xs font-semibold text-gray-900 uppercase ">Contact
                  Number</label>
                <input type="number" id="number" wire:model="number"
                  class="text-xs tei-text-accent {{ $errors->has('number') ? ' border border-red-500  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 focus:ring-gray-500 focus:border-gray-500 ' }} text-sm rounded-lg block w-full p-2" />
                @error('number')
                  <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                    {{ $message }}
                  </p>
                @enderror
              </div>

              {{-- Nature Business --}}
              <div class="mt-4">
                <label for="nature_business" class="block mb-2  text-xs font-semibold text-gray-900 uppercase ">Nature
                  of Business</label>
                <div class=" leading-loose">
                  @foreach ($natureBusiness as $index => $business)
                    <small
                      class="text-white font-black text-nowrap px-2 py-1 rounded-md cursor-pointer {{ $business ? 'tei-bg-secondary hover:bg-orange-700' : 'bg-gray-500 hover:bg-gray-600' }}"
                      wire:click="selectedBusiness('{{ $index }}')">{{ strtoupper($index) }}</small>
                  @endforeach
                </div>
                <div class="flex gap-4 pt-3">
                  <small
                    class="text-white font-black text-nowrap px-2 py-1 rounded-md cursor-pointer {{ $inputHidden ? 'bg-gray-500 hover:bg-gray-600' : 'tei-bg-secondary hover:bg-orange-700' }}"
                    wire:click="selectedOther">OTHERS</small>
                  <div {{ $inputHidden ? 'hidden' : '' }}>
                    <x-text-input wire:model="othersInput" class="block mt-1 w-50" type="text" />
                  </div>
                </div>
                @if ($errors->has('nature'))
                  <x-input-error :messages="$errors->first('nature')" class="mt-2" />
                @endif
              </div>

              {{-- products/Service --}}
              <div class="mt-4">
                <label for="Product/Services"
                  class="block mb-2  text-xs font-semibold text-gray-900 uppercase ">Product/Services</label>
                <div class="pt-2 leading-loose">
                  @forelse ($selectedClasses as $index => $class)
                    <small
                      class="text-white font-black text-nowrap px-2 py-1 rounded-md cursor-pointer 
                      {{ $class ? 'tei-bg-secondary hover:bg-orange-700' : 'bg-gray-500 hover:bg-gray-600' }}"
                      wire:click="selectClass('{{ $index }}')">{{ $class['description'] }}</small>
                  @empty
                    <small class="tei-text-accent">Please click view more to select products/services.</small>
                  @endforelse
                </div>
                <div class="flex gap-4 pt-3" wire:loading.remove
                wire:target="openClassModal">
                  <small wire:click.prevent="openClassModal"
                    class="text-white font-black text-nowrap px-2 py-1 rounded-md cursor-pointer bg-green-600 hover:bg-green-800">view
                    more</small>
                </div>
                <x-loading-spinner color="var(--secondary)" target="openClassModal" />
                @if ($errors->has('selectedClasses'))
                  <x-input-error :messages="$errors->first('selectedClasses')" class="mt-2" />
                @endif
              </div>
            </div>
          </div>
          <!-- Modal footer -->
          <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
            <button type="submit" wire:loading.remove wire:target="vendorRegistration"
              class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">Create</button>
            <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
              wire:target="vendorRegistration">
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
            <button wire:click="closeAddModal" type="button" wire:loading.attr="disabled"
              wire:target="vendorRegistration"
              class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 focus:z-10 hover:scale-110 transition-transform duration-300">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  {{-- End Create Modal --}}

  {{-- Edit Modal --}}
  <div id="edit-vendor" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-lg max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-semibold tei-text-secondary">
            Vendor Setting
          </h3>
          <button type="button"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
            wire:click="closeEditModal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <!-- Modal body -->
        <form wire:submit="eidtUserInfo">
          <div class="p-4 md:p-5 space-y-4">
            <p class="mt-1 text-sm font-normal text-gray-500 ">Manage vendor settings, including password reset and
              active/inactive status.
            </p>
            <div class="mt-4">
              <label class="text-sm uppercase tei-text-primary font-semibold">Vendor Name: </label>
              <span class="text-sm font-semibold tei-text-accent">{{ $editVendor ? $editVendor->name : null }}</span>
            </div>
            <hr>
            <div class="mb-6 space-y-4 flex-col">
              <div class="my-5">
                <div>
                  <label class="block mb-2  text-xs font-semibold text-gray-900 uppercase ">Status</label>
                  <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" value="" class="sr-only peer" wire:model.live="editStatus">
                    <div
                      class="relative w-9 h-5 bg-gray-500 rounded-full peer focus:outline-none peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600">
                    </div>
                    <span
                      class="ms-3 text-sm font-medium {{ $editStatus ? 'text-green-500' : 'text-red-500' }}">{{ $switchStatus }}</span>
                  </label>
                  <p class="text-xs font-semibold text-yellow-500">{{ $warningInactive }}</p>
                </div>
              </div>
              <div class="my-5">
                <div>
                  <label class="block mb-2  text-xs font-semibold text-gray-900 uppercase ">Reset Password</label>
                  <p class="text-xs tei-text-accent italic">Click button to reset password</p>
                  <button wire:click.prevent="resetPassword" wire:loading.remove wire:target="resetPassword"
                    class="hover:scale-110 transition-transform duration-300 mr-4 tei-bg-primary rounded-md px-2 py-1 text-white text-xs"><i
                      class="fa-solid fa-gear"></i> Reset</button>
                  <x-loading-spinner color="var(--secondary)" target="resetPassword" />
                </div>
              </div>
            </div>
          </div>
          <!-- Modal footer -->
          <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
            <button type="submit" wire:loading.remove wire:target="eidtUserInfo"
              class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">Update</button>
            <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
              wire:target="eidtUserInfo">
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
            <button wire:click="closeEditModal" type="button"
              class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 focus:z-10 hover:scale-110 transition-transform duration-300">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  {{-- End Edit Modal --}}

  {{-- Reset Modal --}}
  <div id="confirmation-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button" wire:click="closeConfirmationModal"
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
          <h3 class="mb-5 text-lg font-normal tei-text-primary ">Are you sure you want to reset the password for <span
              class="font-extrabold underline">{{ $editVendor ? $editVendor->email : null }}</span></h3>
          {{-- @if ($this->bidHold)
            <div class="mb-4">
              <span class="tei-text-secondary font-extrabold">Project Bid Title: </span>
              <span class="tei-text-accent">{{ $this->bidHold->title }}</span>
            </div>
          @endif --}}
          <button data-modal-hide="hold-modal" type="button" wire:loading.remove wire:target="resetUserPassword"
            class="text-white bg-green-600 hover:bg-green-900 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
            wire:click= "resetUserPassword">
            Confirm
          </button>
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
            wire:target="resetUserPassword">
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
          <button wire:click="closeConfirmationModal" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10">
            Cancel</button>
        </div>
      </div>
    </div>
  </div>
  {{-- END Reset Modal --}}

  {{-- Class Modal --}}
  <div id="class-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-4xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-semibold text-gray-900">
            Products/Services
          </h3>
          <button type="button"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
            wire:click="closeClassModal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <!-- Modal body -->
        <div class="p-4 md:p-5 space-y-4">
          <div class="pt-2 leading-loose">
            @foreach ($classes as $index => $class)
              <small
                class="text-white text-nowrap font-black px-2 py-1 rounded-md cursor-pointer {{ $class['select'] ? 'tei-bg-secondary hover:bg-orange-700' : 'bg-gray-500 hover:bg-gray-600' }}"
                wire:click="selectClass('{{ $index }}')">{{ $class['description'] }}</small>
            @endforeach
          </div>
        </div>
        <!-- Modal footer -->
        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
          <button type="button" wire:click="resetClass"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Reset</button>
          <button wire:click="closeClassModal" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10">Close</button>
        </div>
      </div>
    </div>
  </div>
  {{-- END Class Modal --}}

  @script
    <script>
      $wire.on('addModal', () => {
        var modalElement = document.getElementById('add-vendor');
        var modal = new Modal(modalElement, {
          backdrop: 'static'
        });
        modal.show();
      });

      $wire.on('closeModal', () => {
        var modalElement = document.getElementById('add-vendor');
        var modal = new Modal(modalElement);
        modal.hide();
      });

      $wire.on('openEditModal', () => {
        var modalElement = document.getElementById('edit-vendor');
        var modal = new Modal(modalElement, {
          backdrop: 'static'
        });
        modal.show();
      });
      $wire.on('closeEditModal', () => {
        var modalElement = document.getElementById('edit-vendor');
        var modal = new Modal(modalElement);
        modal.hide();
      });

      $wire.on('openConfirmationModal', () => {
        var modalElement = document.getElementById('confirmation-modal');
        var modal = new Modal(modalElement, {
          backdrop: 'static'
        });
        modal.show();
      });
      $wire.on('closeConfirmationModal', () => {
        var modalElement = document.getElementById('confirmation-modal');
        var modal = new Modal(modalElement);
        modal.hide();
      });

      $wire.on('openClassModal', () => {
        var modalElement = document.getElementById('class-modal');
        var modal = new Modal(modalElement, {
          backdrop: 'static'
        });
        modal.show();
      });
      $wire.on('closeClassModal', () => {
        var modalElement = document.getElementById('class-modal');
        var modal = new Modal(modalElement);
        modal.hide();
      });
    </script>
  @endscript
  {{-- END Accept Modal --}}


  {{-- <script>
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
</script> --}}
