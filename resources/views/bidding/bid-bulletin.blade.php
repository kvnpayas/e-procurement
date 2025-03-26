<x-app-layout>
  <div class="max-w-full mx-auto sm:px-6 lg:px-8 py-12">
    <div class="bg-white shadow-lg rounded-md">


      <livewire:bidding.bid-bulletin :id='$biddingId'/>

    </div>
  </div>
  @include('components.toast-message')
</x-app-layout>