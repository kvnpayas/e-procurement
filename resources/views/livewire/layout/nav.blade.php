<div class="absolute w-full">
  <div class="navbar px-10 bg-white justify-between flex shadow-lg py-2">
    <div class="">
      <div class="flex-none">
        <a href="{{ route('dashboard') }}" class="flex">
          <img src="{{ asset('img/tei-logo-no-name.png') }}" alt="My Image" style="height: 38px;" />
          <span
            class="pl-2 pt-3 text-primary font-black drop-shadow-lg tei-text-primary hidden md:block">e-Procurement</span>
        </a>
      </div>
    </div>
    <div class="">
      <nav class="">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto">
          {{-- <button data-collapse-toggle="navbar-dropdown" type="button"
            class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
            aria-controls="navbar-dropdown" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 17 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M1 1h15M1 7h15M1 13h15" />
            </svg>
          </button> --}}
          <div class=" w-full " id="navbar-dropdown">
            <ul class="flex flex-row font-medium p-0 mt-0 rounded-lg">
              <li>
                <div class="py-4">
                  <span
                    class="hidden md:block font-extrabold tei-text-primary text-xs">{{ strtoupper(Auth::user()->name) }}</span>
                </div>
              </li>
              <li>
                <button class="flex items-center justify-between w-full py-2 px-3 tei-text"
                  id="avatarMenu">
                  <div class="avatar placeholder">
                    <div class=" tei-bg-light rounded-full ">
                      <span class="text-xs text-tei-primary">
                        <img src="{{ asset('img/tei-logo-no-name.png') }}" alt="" class="w-8 h-8 rounded-full ">
                      </span>
                    </div>
                  </div>
                  <svg class="w-2.5 h-2.5 ms-2.5 tei-text-secondary" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="m1 1 4 4 4-4" />
                  </svg>
                </button>
                <!-- Dropdown menu -->
                <div id="dropdownMenu"
                  class="z-10 top-16 right-0 font-normal bg-white divide-y divide-gray-100 rounded-lg shadow-xl w-44 tei-text border {{ $toggleDropDown ? 'absolute' : 'hidden' }}">
                  {{-- <ul class="py-2 text-sm " aria-labelledby="dropdownLargeButton">
                    <li>
                      <a href="{{ route('profile') }}" class="block px-4 py-2 ">Profile</a>
                    </li>
                  </ul> --}}
                  <div class="py-2 block md:hidden tei-bg-light rounded-t-md">
                    <span
                      class="font-extrabold tei-text-secondary text-sm px-4 py-2 ">{{ strtoupper(Auth::user()->name) }}</span>
                  </div>
                  <div class="py-1 hover:bg-gray-200 hover:text-black tei-text-accent">
                    <a href="{{ route('profile') }}" class="block px-4 py-2 text-xs uppercase font-extrabold "><i
                        class="fa-solid fa-user mr-2"></i>Profile</a>
                  </div>
                  <div class="py-1 hover:bg-gray-200 hover:text-black tei-text-accent">
                    <button wire:click.prevent="logout" class="block px-4 py-2 text-xs uppercase font-extrabold"><i
                        class="fa-solid fa-right-from-bracket mr-2"></i>Sign
                      out</button>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </div>
  </div>
</div>
@script
  <script>
    document.addEventListener('click', function(event) {
      const dropdownMenu = document.getElementById('dropdownMenu');
      const avatarMenu = document.getElementById('avatarMenu');
      // console.log(avatarMenu.contains(event.target));

      if (avatarMenu && avatarMenu.contains(event.target)) {
        if (@this.get('toggleDropDown')) {
          @this.set('toggleDropDown', false);
        } else {
          @this.set('toggleDropDown', true);
        }
      } else {
        if (dropdownMenu && !dropdownMenu.contains(event.target)) {
          @this.set('toggleDropDown', false);
        }
      }
    });
  </script>
@endscript
