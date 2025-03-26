<div>
  <div class="grid grid-cols-1 sm:grid-cols-2 p-5 gap-4">
    <div>
      <div>
        <label for="title" class=" mr-2 text-lg font-extrabold tei-text-primary ">Vendor Name:</label>
        <span class="text-xs uppercase font-extrabold tei-text-accent">{{ $vendor->name }}</span>
      </div>
      <div>
        <label for="title" class=" mr-2 text-lg font-extrabold tei-text-primary ">Email:</label>
        <span class="text-xs font-extrabold tei-text-accent">{{ $vendor->email }}</span>
      </div>
      <div>
        <label for="title" class=" mr-2 text-lg font-extrabold tei-text-primary ">Address:</label>
        <span class="text-xs uppercase font-extrabold tei-text-accent">{{ $vendor->address }}</span>
      </div>
    </div>
    <div>
      <div>
        <label for="title" class=" mr-2 text-lg font-extrabold tei-text-primary ">Contact Number:</label>
        <span class="text-xs uppercase font-extrabold tei-text-accent">{{ $vendor->number }}</span>
      </div>
      <div>
        <label for="title" class="block mr-2 text-lg font-extrabold tei-text-primary ">Additional Contacts:</label>
        @foreach ($vendor->vendorContacts as $contact)
          <div class="grid grid-cols-2">
            <label for="title" class="block mr-2 text-sm font-extrabold tei-text-secondary ">Name: <span
                class="tei-text-accent">{{ $contact->name }}</span></label>
            <label for="title" class="block mr-2 text-sm font-extrabold tei-text-secondary ">Number: <span
                class="tei-text-accent">{{ $contact->number }}</span></label>
          </div>
        @endforeach
        {{-- <span class="text-xs uppercase font-extrabold tei-text-accent">{{ $vendor->number }}</span> --}}
      </div>
    </div>
  </div>
  <hr>
  <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
      <div>
        <label for="title" class=" mr-2 text-lg font-extrabold tei-text-primary ">Tin No:</label>
        <span class="text-xs uppercase font-extrabold tei-text-accent">{{ $vendor->tin_no }}</span>
      </div>
      <div>
        <label for="title" class=" mr-2 text-lg font-extrabold tei-text-primary ">DTI Business Name No./SEC Company
          Registration No.:</label>
        <span class="text-xs font-extrabold tei-text-accent">{{ $vendor->dti_sec_no }}</span>
      </div>
    </div>
    <div>
      <div>
        <label for="title" class=" mr-2 text-lg font-extrabold tei-text-primary ">Company Profile:</label>
        <span
          class="text-xs uppercase font-extrabold tei-text-accent">{{ $vendor->company_profile ? $vendor->company_profile : 'NULL' }}</span>
        @if ($vendor->company_profile)
          <button  wire:click.prevent="vieCompanyProfile"
            class="hover:scale-110 transition-transform duration-300 mr-4 tei-bg-secondary rounded-md px-2 py-1 text-white text-xs">View</button>
        @endif
      </div>
    </div>
  </div>

  <hr>

  <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="w-full sm:w-1/2">
      <div>
        <label for="title" class=" mr-2 text-lg font-extrabold tei-text-primary ">Nature of Business:</label>
        <div class="pt-2 leading-loose">
          @foreach ($vendorNatures as $nature)
            @if ($nature)
              <small
                class="text-xs text-white font-black text-nowrap px-2 py-1 rounded-md cursor-pointer tei-bg-secondary hover:bg-gray-600">{{ strtoupper($nature) }}</small>
            @endif
          @endforeach
          {{-- @if (!$vendorNatures)
            <small>You have to select nature of business.</small>
          @endif --}}
          <small data-modal-target="nature-modal" data-modal-toggle="nature-modal" wire:click="vendorNatureModal" wire:loading.remove wire:target="vendorNatureModal"
            class="font-black pl-3 underline italic cursor-pointer hover:text-slate-700">show more</small>
          <x-loading-spinner color="var(--secondary)" target="vendorNatureModal" />
        </div>
      </div>

      <div>
        <label for="title" class=" mr-2 text-lg font-extrabold tei-text-primary ">Product/Services:</label>
        <div class="pt-2 leading-loose">
          @forelse ($vendorClasses as $class)
            <small
              class="text-xs text-white font-black text-nowrap px-2 py-1 rounded-md cursor-pointer tei-bg-secondary hover:bg-gray-600">{{ $class }}</small>
          @empty
            <small>Please click show more to select products/services.</small>
          @endforelse
        </div>
        <div>
          <small data-modal-target="class-modal" data-modal-toggle="class-modal"
            class="font-black pl-3 underline italic cursor-pointer hover:text-slate-700">show more</small>
        </div>
      </div>
    </div>
    <div>
      <div>
        <label for="title" class="block mr-2 text-lg font-extrabold tei-text-primary ">Top Customers:</label>
        @forelse ($vendor->vendorTopCustomers as $customer)
          <div class="grid grid-cols-3">
            <label for="title" class="block mr-2 text-sm font-extrabold tei-text-secondary ">Company Name: <span
                class="tei-text-accent">{{ $customer->company_name }}</span></label>
            <label for="title" class="block mr-2 text-sm font-extrabold tei-text-secondary ">Contact Person: <span
                class="tei-text-accent">{{ $customer->contact_person }}</span></label>
            <label for="title" class="block mr-2 text-sm font-extrabold tei-text-secondary ">Phone NUmber: <span
                class="tei-text-accent">{{ $customer->phone_number }}</span></label>
          </div>
        @empty
          <span class="text-xs uppercase font-extrabold tei-text-accent">NULL</span>
        @endforelse
        {{-- <span class="text-xs uppercase font-extrabold tei-text-accent">{{ $vendor->number }}</span> --}}
      </div>
    </div>
  </div>

  <hr>

  <div class="p-5 overflow-x-auto">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 boder-collapse">
      <caption class="p-5 text-lg font-semibold text-left rtl:text-right tei-text-secondary bg-white ">
        List of vendors's bids
        <p class="mt-1 text-sm font-normal text-gray-500 ">Overview of all the bids submitted by vendors for a
          particular project or set of projects.
        </p>
      </caption>
      <thead class="text-xs tei-text-primary uppercase tei-bg-light font-extrabold">
        <tr>
          <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
            <div class="flex justify-between">
              <span>Id</span>
            </div>
          </td>
          <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
            <div class="flex justify-between">
              <span>Title</span>
            </div>
          </td>
          <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
            <div class="flex justify-between">
              <span>Type</span>
            </div>
          </td>
          <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
            <div class="flex justify-between">
              <span>Deadline Date</span>
            </div>
          </td>
          <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
            <div class="flex justify-between">
              <span>Bid Status</span>
            </div>
          </td>
          <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
            <div class="flex justify-between">
              <span>Vendor Status</span>
            </div>
          </td>
          {{-- <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900 w-96">
            DEADLINE DATE
          </td> --}}
        </tr>
      </thead>
      <tbody>
        @forelse ($biddings as $bidding)
          <tr class="bg-white border-b font-extrabold hover:bg-neutral-200 hover:shadow-xl transition duration-300">
            <th scope="row" class="px-6 py-2 font-medium tei-text-secondary whitespace-nowrap ">
              {{ $bidding->id }}
            </th>
            <td class="px-6 py-2">
              {{ $bidding->title }}
            </td>
            <td class="px-6 py-2">
              {{ $bidding->type }}
            </td>
            <td class="px-6 py-2">
              {{ $bidding->extend_date ? date('F j,Y', strtotime($bidding->extend_date)) : date('F j,Y', strtotime($bidding->deadline_date)) }}
            </td>
            <td class="px-6 py-2">
              {{ $bidding->status }}
            </td>
            <td class="px-6 py-2">
              {{ $bidding->pivot->status }}
            </td>

          </tr>
        @empty
          <tr class="bg-white border-b">
            <th scope="row" colspan="100"
              class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap text-center">
              <span class="font-black text-lg tei-text-primary">No bid.</span>
            </th>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- File Modal --}}
  <div id="view-file" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-7xl max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
          data-modal-hide="view-file">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        <div class="p-4 md:p-5 text-center ">
          <span class="uppercase tei-text-primary text-lg font-black mb-5">Company Profile</span>
          <hr>
          <div class=" flex justify-center mt-5">
            @if ($company_profile)
              <iframe src="{{ $fileAttachment }}" frameborder="1"
                width="1000" height="850"></iframe>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
  {{-- END File Modal --}}

  {{-- nature Modal --}}
  <div id="nature-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-2xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-semibold text-gray-900">
            Nature of Business
          </h3>
          <button type="button"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
            data-modal-hide="nature-modal">
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
            @foreach ($initNatureBusiness as $index => $nature)
              <small
                class="text-white font-black text-nowrap px-2 py-1 rounded-md cursor-pointer {{ in_array($nature, $vendorNatures) ? 'tei-bg-secondary hover:bg-orange-700' : 'bg-gray-500 hover:bg-gray-600' }}"
                wire:click="selectedBusiness('{{ $nature }}')">{{ strtoupper($nature) }}</small>
            @endforeach
            <div class="w-64">
              <small class="" wire:click="selectedOther">OTHERS</small>
              <div>
                @foreach ($others as $key => $other)
                  <small
                    class="text-white font-black text-nowrap px-2 py-1 rounded-md cursor-pointer tei-bg-secondary hover:bg-orange-700 mr-2">{{ strtoupper($other) }}
                    <i class="fa-solid fa-xmark pl-1" wire:click="removeOthers({{ $key }})"></i>
                  </small>
                @endforeach
              </div>
              <div class="flex justify-end w-full">
                <x-text-input wire:model="othersInput" class="block mt-1 w-full mr-2" type="text" />
                <small
                  class="text-white font-black text-nowrap px-2 py-1 rounded-md cursor-pointer tei-bg-primary hover:bg-gray-600"
                  wire:click="addOthers">ADD</small>
              </div>
            </div>
            @error('vendorNatures')
              <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                {{ $message }}
              </p>
            @enderror
          </div>
        </div>
        <!-- Modal footer -->
        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
          <button wire:click="saveNature" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-white focus:outline-none bg-green-500 rounded-lg border border-gray-200 hover:bg-green-600  focus:z-10 focus:ring-4 focus:ring-gray-100">Save</button>
          <button data-modal-hide="nature-modal" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">Close</button>
        </div>
      </div>
    </div>
  </div>
  {{-- END Nature Modal --}}

  {{-- Class Modal --}}
  <div id="class-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-2xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-semibold text-gray-900">
            Products/Services
          </h3>
          <button type="button"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
            wire:click="closeFileModal">
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
            @foreach ($allClass as $class)
              <small
                class="text-white font-black text-nowrap px-2 py-1 rounded-md cursor-pointer {{ isset($vendorClasses[$class->code]) ? 'tei-bg-secondary hover:bg-orange-700' : 'bg-gray-500 hover:bg-gray-600' }}"
                wire:click="selectClass('{{ $class->code }}')">{{ $class->description }}</small>
            @endforeach
          </div>
          @error('vendorClasses')
            <p class="mt-2 text-xs text-red-600 dark:text-red-500">
              {{ $message }}
            </p>
          @enderror
        </div>
        <!-- Modal footer -->
        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
          <button type="button" wire:click="saveClasses"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Save</button>
          <button wire:click="closeFileModal" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">Close</button>
        </div>
      </div>
    </div>
  </div>
  {{-- END Class Modal --}}
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
