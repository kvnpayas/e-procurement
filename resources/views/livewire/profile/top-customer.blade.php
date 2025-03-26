<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use App\Models\VendorBank;

new class extends Component {
    public $user, $customers = [];

    public function mount()
    {
        $this->user = Auth::user();
        $vendorCustomers = $this->user->vendorTopCustomers;

        foreach ($vendorCustomers as $key => $customer) {
            $this->customers[$key] = [
                'company_name' => $customer->company_name,
                'address' => $customer->address,
                'contact_person' => $customer->contact_person,
                'phone_number' => $customer->phone_number,
                'mobile_number' => $customer->mobile_number,
                'email' => $customer->email,
            ];
        }
    }

    public function add()
    {
        $this->resetValidation();
        $this->customers[] = [
            'company_name' => null,
            'address' => null,
            'contact_person' => null,
            'phone_number' => null,
            'mobile_number' => null,
            'email' => null,
        ];
    }
    public function remove($index)
    {
        $this->resetValidation();
        unset($this->customers[$index]);
        $this->customers = array_values($this->customers);
    }
    public function updateForm(): void
    {
        $validated = $this->validate(
            [
                'customers.*.company_name' => ['required'],
                'customers.*.address' => ['required'],
                'customers.*.contact_person' => ['required'],
                'customers.*.phone_number' => ['required'],
                'customers.*.mobile_number' => ['required'],
                'customers.*.email' => ['required', 'lowercase', 'email'],
            ],
            [
                'customers.*.company_name.required' => 'The company name field is required.',
                'customers.*.address.required' => 'The address field is required.',
                'customers.*.contact_person.required' => 'The contact person field is required.',
                'customers.*.phone_number.required' => 'The phone number field is required.',
                'customers.*.mobile_number.required' => 'The mobile number field is required.',
                'customers.*.email.required' => 'The email field is required.',
                'customers.*.email.email' => 'Email must be valid email address.',
            ],
        );
        $this->user->vendorTopCustomers()->delete();
        foreach ($this->customers as $customer) {
            $this->user->vendorTopCustomers()->create($customer);
        }
        $this->dispatch('customer-updated');
    }
}; ?>

<section>
  <header>
    <h2 class="text-4xl font-black tei-text-primary" style="text-shadow:0px 4px 12px rgb(107, 107, 107);">
      {{ __('TOP CUSTOMERS') }}
    </h2>

    <p class="mt-3 text-sm text-gray-600">
      {{ __('Include a list of your best customers.') }}
    </p>
  </header>

  @if (count($customers) != 3)
    <div class="my-3">
      <button
        class="tei-bg-primary px-5 py-2 rounded-md text-white hover:bg-slate-600 transition ease-in-out duration-150 font-semibold text-sm"
        wire:click.prevent="add">Add</button>
    </div>
  @endif

  <form wire:submit="updateForm">
    @forelse ($customers as $i => $customer)
      <h1 class="text-lg font-black mt-3">Customer {{ $i + 1 }}</h1>
      <div class="mt-3 grid gap-4 grid-cols-2 mb-2">
        <div>
          <div>
            <x-input-label-dark for="name" :value="__('Company Name ')" />
            <x-text-input wire:model="customers.{{ $i }}.company_name" class="mt-1 block w-full" />
            <x-input-error :messages="$errors->get('customers.' . $i . '.company_name')" class="mt-2" />
          </div>
          <div>
            <x-input-label-dark for="name" :value="__('Contact Person ')" />
            <x-text-input wire:model="customers.{{ $i }}.contact_person" class="mt-1 block w-full" />
            <x-input-error :messages="$errors->get('customers.' . $i . '.contact_person')" class="mt-2" />
          </div>
        </div>

        <div>
          <x-input-label-dark for="email" :value="__('Address')" />
          <textarea wire:model="customers.{{ $i }}.address" cols="10" rows="3"
            class="border-gray-300 rounded-md shadow-sm focus:ring-orange-700 focus:border-orange-700 mt-1 block w-full font-medium text-sm tei-text"></textarea>
          <x-input-error :messages="$errors->get('customers.' . $i . '.address')" class="mt-2" />
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <x-input-label-dark for="number" :value="__('Phone Number')" />
            <x-text-input wire:model="customers.{{ $i }}.phone_number" class="mt-1 block w-full" />
            <x-input-error :messages="$errors->get('customers.' . $i . '.phone_number')" class="mt-2" />
          </div>
          <div>
            <x-input-label-dark for="number" :value="__('Mobile Number')" />
            <x-text-input wire:model="customers.{{ $i }}.mobile_number" class="mt-1 block w-full" />
            <x-input-error :messages="$errors->get('customers.' . $i . '.mobile_number')" class="mt-2" />
          </div>
        </div>

        <div>
          <x-input-label-dark :value="__('Email')" />
          <x-text-input wire:model="customers.{{ $i }}.email" class="mt-1 block w-64" />
          <x-input-error :messages="$errors->get('customers.' . $i . '.email')" class="mt-2" />
        </div>

      </div>
      <div class="flex justify-end place-items-end mb-4">
        <button
          class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2"
          wire:click.prevent="remove({{ $i }})">Remove</button>
      </div>
      <hr>
    @empty
      <div class="w-full text-center my-5">
        <span class="tei-text">No Customers.</span>
      </div>
    @endforelse
    <div class="flex items-center gap-4 mt-5">
      <x-primary-button>{{ __('Save') }}</x-primary-button>

      <x-action-message class="me-3" on="customer-updated">
        {{ __('Top customers saved.') }}
      </x-action-message>
    </div>
  </form>
</section>
