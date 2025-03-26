<x-app-layout>

  <div class="py-12">
    <div class="max-w-full mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10">
        @if (Auth::user()->role_id == 2)
          @livewire('bidding.vendor-dashboard')
        @else
          {{-- {{dd(session()->all())}} --}}
          @livewire('admin.dashboard')
        @endif
      </div>
    </div>
  </div>
</x-app-layout>
