<div class="shadow-lg">
  <div class="h-20 tei-bg-gradient flex place-items-center pl-5">
    <span class="text-2xl fon-black text-white text-shadow-lg" style="text-shadow: 0px 6px 4px rgb(0, 0, 0);">
      {{ str_replace('-', ' ', strtoupper($currentRoutes)) }}
    </span>
  </div>
  <div class="bg-white flex place-items-center pl-5">
    <span class="italic">

      <!-- Breadcrumb -->
      <nav class="flex px-5 py-3 text-gray-700" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
          @foreach ($routes as $route)
            @php
            array_push($routestTest, $route);
              $finalRoute = implode('.', $routestTest);
            @endphp
            <li class="inline-flex items-center">
              @if ($routes[0] != $route)
                <svg class="rtl:rotate-180 block w-3 h-3 mx-1 text-gray-400 " aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 9 4-4-4-4" />
                </svg>
              @endif
              @if ($currentRoutes == $route)
                <span  class="inline-flex items-center text-sm font-medium text-gray-700 ">{{  ucwords(str_replace('-', ' ', $route)) }}</span>
              @else
              <a href="{{ route($finalRoute) }}"
                class="inline-flex items-center text-sm font-medium tei-text-secondary hover:text-blue-600 ">
                {{ ucwords(str_replace('-', ' ', $route)) }}
              </a>
              @endif
            </li>
          @endforeach
          {{-- {{dd($routestTest)}} --}}
        </ol>
      </nav>

      {{-- {{str_replace("-", " ", ucfirst(Route::currentRouteName()))}} --}}
    </span>
  </div>
</div>
