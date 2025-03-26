<div class="relative overflow-x-auto shadow-md sm:rounded-lg overflow-x-auto">
  <div wire:loading wire:target="syncClassess">
    @include('partial.page-loader')
  </div>
  <table class="w-full text-sm text-left rtl:text-right text-gray-500">
    <caption class="p-5 text-lg font-semibold text-left rtl:text-right tei-text-secondary bg-white ">
      Products/Services
      <p class="mt-1 text-sm font-normal text-gray-500 ">The lists of vendors ready to participate in bidding.
      </p>
      <div class="flex justify-between">
        <div>

        </div>
        <div>
          {{-- <button type="button"
            class="text-white tei-bg-primary hover:bg-sky-900 drop-shadow-md hover:drop-shadow-lg font-medium rounded-lg text-xs px-5 py-2 me-2 mb-2"
            data-modal-target="accept-modal" data-modal-toggle="accept-modal">
            Update Class
          </button> --}}
        </div>
      </div>
    </caption>
    <thead class="text-xs text-gray-700 uppercase tei-bg-light ">
      <tr>
        <th scope="col" class="px-6 py-3">
          Class Code
        </th>
        <th scope="col" class="px-6 py-3">
          Class Description
        </th>
        <th scope="col" class="px-6 py-3">
          Action
        </th>
      </tr>
    </thead>
    <tbody>
      @forelse ($classes  as $class)
        <tr class="bg-white border-b ">
          <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
            {{ $class->code }}
          </th>
          <td class="px-6 py-4">
            {{ $class->description }}
          </td>
          <td class="px-6 py-4">

          </td>
        </tr>
      @empty
        <tr class="bg-white border-b">
          <th scope="row" colspan="100" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center">
            <span class="font-black text-lg tei-text-primary">No class records exist.</span>
          </th>
        </tr>
      @endforelse
    </tbody>
  </table>

  {{ $classes->links('livewire.layout.pagination') }}
  {{-- 
  <x-action-message class="me-3" on="alert-message">
    {{ __($alertMessage) }}
  </x-action-message>  --}}
  @if (session('success'))
    <div id="toast-success"
      class="fixed top-[7%] left-[43%]  flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-green-600 rounded-lg shadow dark:text-gray-400 dark:bg-gray-800"
      role="alert">
      <div
        class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
          viewBox="0 0 20 20">
          <path
            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
        </svg>
        <span class="sr-only">Check icon</span>
      </div>
      <div class="ms-3 text-sm font-normal text-white">{{ session('success') }}.</div>
      <button type="button"
        class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
        data-dismiss-target="#toast-success" aria-label="Close">
        <span class="sr-only">Close</span>
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
        </svg>
      </button>
    </div>
  @endif


  {{-- Accept Modal --}}
  <div id="accept-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
          data-modal-hide="accept-modal">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
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
          <h3 class="mb-5 text-lg font-normal tei-text-primary ">Update Products/Services?</h3>
          <button data-modal-hide="accept-modal" type="button"
            class="text-white bg-green-600 hover:bg-green-900 focus:ring-4 focus:outline-none  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
            wire:click= "syncClassess">
            Confirm
          </button>
          <button data-modal-hide="accept-modal" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10 focus:ring-4 focus:ring-gray-100">
            Cancel</button>
        </div>
      </div>
    </div>
  </div>
  {{-- END Accept Modal --}}
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {

    function showToast() {
      var toast = document.getElementById('toast-success');
      console.log(toast);
      if (toast) {
        toast.style.opacity = 1;


        setTimeout(function() {
          toast.style.transition = 'opacity 1.5s';
          toast.style.opacity = 0;
        }, 2000);
        toast.addEventListener('transitionend', function() {
          toast.remove();
        });
      }
    }

    showToast();
  });
</script>
