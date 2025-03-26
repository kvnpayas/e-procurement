<div
  class="tei-sidebar tei-sidebar-show flex flex-col pt-20 bg-white overflow-y-auto fixed max-h-screen min-h-screen z-10 shadow-[0_35px_60px_-15px_rgba(0,0,0,0.7)] "
  wire:ignore.self>
  <div>
    <div class="px-4 py-2 flex">
      <span class="text-2xl ml-auto cursor-pointer tei-text-secondary" id="collapse-icon">
        <i class="fa-solid fa-bars"></i>
      </span>
    </div>
    {{-- Search --}}
    <div class="px-4 relative">
      <div class="flex opacity-100" id="searchBar" wire:ignore.self>
        <input type="text" id="website-admin" wire:model.live.debounce.250ms="search"
          class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-orange-400 focus:border-orange-400 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5 "
          placeholder="search">
        <span
          class="inline-flex items-center px-3 text-sm text-gray-900 border rounded-s-0 border-gray-300 border-e-0 rounded-e-md tei-bg-light">
          <i class="fa-solid fa-magnifying-glass tei-text-secondary"></i>
        </span>
      </div>
      <div class="bg-neutral-100 mt-1 shadow-xl rounded-sm absolute inset-left-5 w-[87%]" {{ $search ? '' : 'hidden' }}>
        <ul class="text-sm font-medium text-gray-900 border rounded-md">
          @forelse ($menuLists as $menu)
            <li
              class="text-gray-900 w-full px-4 py-2 border-b border-gray-200 text-xs uppercase font-semibold menu-hover hover:shadow-xl transition duration-300">
              <a href="{{ route($menu['route_name']) }}">{{ $menu['name'] }}</a>
            </li>
          @empty
            <li class="w-full px-4 py-2 border-b border-gray-200 rounded-t-lg text-center">No Result</li>
          @endforelse
        </ul>
      </div>
    </div>
    {{-- End Search --}}
    {{-- Sidebar Menu --}}
    <div class="mt-10 text-sm">
      <ul class="whitespace-nowrap">
        <li
          class="w-full h-12 flex place-items-center px-4 menu-hover {{ session()->get('originalRouteName') == 'dashboard' ? 'tei-bg-active shadow-lg ' : '' }}">
          <a href="{{ route('dashboard') }}" class="flex place-items-center w-full">
            <img src="{{ asset('img/home-e-proc.png') }}" alt="" class="h-8 w-8 ">
            <span class="ml-4 font-black tei-text-primary">Dashboard</span>
          </a>
        </li>
        <li class="w-full" id="biddingMenu">
          <div class="flex place-items-center w-full cursor-pointer px-4 h-12 menu-hover" id="main-bidding">
            <img src="{{ asset('img/bid.png') }}" alt="" class="h-8 w-8 ">
            <span class="ml-4 font-black tei-text-primary">Project Bid</span>
          </div>
          <ul
            class="whitespace-nowrap tei-bg-light sub-menu shadow-inner {{ Str::startsWith(Route::currentRouteName(), 'bid-') ? 'sub-menu-show' : 'sub-menu-hide' }}"
            wire:ignore.self>
            <li
              class="h-12 flex place-items-center font-black tei-text-primary menu-hover transition ease-in-out delay-150 pl-5 text-sm {{ Route::currentRouteName() == 'bid-invitation' ? 'tei-bg-active shadow-lg ' : '' }}"
              wire:ignore.self>
              <a href="{{ route('bid-invitation') }}" class="menu-link transition-opacity duration-300">Bid
                Invitation</a>
              <i class="fa-solid fa-minus menu-icon hidden"></i>
            </li>
            <li
              class="h-12 flex place-items-center font-black tei-text-primary menu-hover pl-5 {{ Str::startsWith(Route::currentRouteName(), 'bid-lists') ? 'tei-bg-active shadow-lg ' : '' }}"
              wire:ignore.self>
              <a href="{{ route('bid-lists') }}" class="menu-link transition-opacity duration-300">Bid Lists</a>
              <i class="fa-solid fa-minus menu-icon hidden"></i>
            </li>
            <li
              class="h-12 flex place-items-center font-black tei-text-primary menu-hover pl-5 {{ Str::startsWith(Route::currentRouteName(), 'bid-results') ? 'tei-bg-active shadow-lg ' : '' }}"
              wire:ignore.self>
              <a href="{{ route('bid-results') }}" class="menu-link transition-opacity duration-300">Bid Results</a>
              <i class="fa-solid fa-minus menu-icon hidden"></i>
            </li>
          </ul>
        </li>
      </ul>
    </div>
    {{-- End Sidebar Menu --}}
  </div>
  <div class="mt-auto border-t-2 p-2" id="sidebarFooter">
    <p class="text-xs tei-text-accent whitespace-nowrap">&copy; 2025 <span class="tei-text-secondary">TEI
        e-Procurement</span>. Version
      1.0.0</p>
  </div>
</div>

@section('page-sidebar-script')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const mainBidding = document.getElementById("main-bidding");
      const biddingMenu = document.querySelector("#biddingMenu ul");
      const collapseIcon = document.getElementById("collapse-icon");
      const sidebar = document.querySelector(".tei-sidebar");
      const searchBar = document.getElementById("searchBar");
      const main = document.querySelector(".tei-main");
      const menuLinks = document.querySelectorAll('.menu-link');
      const menuIcons = document.querySelectorAll('.menu-icon');
      const footer = document.querySelector('#sidebarFooter');

      const toggleSubMenu = () => {
        biddingMenu.classList.toggle('sub-menu-show');
        biddingMenu.classList.toggle('sub-menu-hide');
      };

      const toggleSidebar = (show) => {
        sidebar.classList.toggle('tei-sidebar-show', show);
        sidebar.classList.toggle('tei-sidebar-collapse', !show);
        main.classList.toggle('tei-main-open', !show);
        main.classList.toggle('tei-main-close', show);
        footer.classList.toggle('hidden', !show);

        if (show) {
          menuLinks.forEach(link => link.classList.remove('hidden'));
          menuIcons.forEach(icon => icon.classList.add('hidden'));
          searchBar.classList.add('opacity-100');
          searchBar.classList.remove('opacity-0');
        } else {
          menuLinks.forEach(link => link.classList.add('hidden'));
          menuIcons.forEach(icon => icon.classList.remove('hidden'));
          searchBar.classList.remove('opacity-100');
          searchBar.classList.add('opacity-0');
        }

        // $wire.dispatch('testEvent', {
        //   menuName: show
        // });
      };

      function updateSidebar() {
        if (window.matchMedia('(max-width: 765px)').matches) {
          sidebar.classList.remove('tei-sidebar-show');
          sidebar.classList.add('tei-sidebar-collapse');
          main.classList.add('tei-main-open');
          main.classList.remove('tei-main-close');
          searchBar.classList.remove('opacity-100');
          searchBar.classList.add('opacity-0');
          menuLinks.forEach(link => link.classList.add('hidden'));
          menuIcons.forEach(icon => icon.classList.remove('hidden'));
          footer.classList.add('hidden');
        } else {
          sidebar.classList.add('tei-sidebar-show');
          sidebar.classList.remove('tei-sidebar-collapse');
          main.classList.remove('tei-main-open');
          main.classList.add('tei-main-close');
          searchBar.classList.add('opacity-100');
          searchBar.classList.remove('opacity-0');
          menuLinks.forEach(link => link.classList.remove('hidden'));
          menuIcons.forEach(icon => icon.classList.add('hidden'));
          footer.classList.remove('hidden');
        }
      }

      // const updateSidebar = () => {
      //   const isMobile = window.matchMedia("(max-width: 765px)").matches;
      //   toggleSidebar(!isMobile);
      // };

      mainBidding.addEventListener("click", toggleSubMenu);
      collapseIcon.addEventListener("click", () => {
        toggleSidebar(!sidebar.classList.contains('tei-sidebar-show'));
      });

      updateSidebar();
      window.addEventListener('resize', updateSidebar);
    });
  </script>
@endsection
