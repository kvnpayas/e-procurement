<x-app-layout>
  @php

    $biddingId = request()->route('bid');
  @endphp
  <div class="max-w-full mx-auto sm:px-6 lg:px-8 py-12">
    <div class="bg-white shadow-lg rounded-md">
      <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        @livewire('envelopes.envelope', ['projectId' => $biddingId])
        @isset($slot)
          {{ $slot }}
        @endisset

      </div>
    </div>
  </div>
  @include('components.toast-message')
</x-app-layout>
