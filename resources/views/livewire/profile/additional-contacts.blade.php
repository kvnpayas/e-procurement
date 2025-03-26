<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

new class extends Component {
    public $contacts = [];

    /**
     * Delete the currently authenticated user.
     */
    public function mount()
    {
        $vendorContacts = Auth::user()->vendorContacts;

        foreach ($vendorContacts as $key => $contact) {
            $this->contacts[$key] = [
                'name' => $contact->name,
                'email' => $contact->email,
                'number' => $contact->number,
            ];
        }
    }

    public function add()
    {
        $this->contacts[] = [
            'name' => null,
            'email' => null,
            'number' => null,
        ];
    }

    public function save(): void
    {
        $validated = $this->validate(
            [
                'contacts.*.name' => ['required', 'max:255'],
                'contacts.*.email' => ['required', 'lowercase', 'email', Rule::unique('vendor_contacts')->ignore(Auth::user()->id, 'vendor_id')],
                'contacts.*.number' => ['required'],
            ],
            [
                'contacts.*.name.required' => 'Contact name is required.',
                'contacts.*.name.max' => 'Contact name must not be greater than 255 characters.',
                'contacts.*.email.required' => 'Email is required.',
                'contacts.*.email.email' => 'Email must be valid email address.',
                'contacts.*.email.unique' => 'Email already in database.',
                'contacts.*.number.required' => 'Number is required.',
            ],
        );
        $vendor = Auth::user();
        $vendor->vendorContacts()->delete();
        foreach ($this->contacts as $contact) {
            $vendor->vendorContacts()->create($contact);
        }
        $this->dispatch('contact-updated', name: $vendor->name);
    }

    public function remove($index)
    {
        unset($this->contacts[$index]);
        $this->contacts = array_values($this->contacts);
    }
}; ?>

<section>
  <header class="mb-3">
    <h2 class="text-4xl font-black tei-text-primary" style="text-shadow:0px 4px 12px rgb(107, 107, 107);">
      {{ __('ADDITIONAL CONTACTS') }}
    </h2>

    <p class="mt-3 text-sm text-gray-600">
      {{ __('Add additional contacts for your vendor information.') }}
    </p>
  </header>
  @if (count($contacts) != 3)
    <button
      class="tei-bg-primary px-5 py-2 rounded-md text-white hover:bg-slate-600 transition ease-in-out duration-150 font-semibold text-sm"
      wire:click.prevent="add">Add</button>
  @endif

  <form wire:submit="save">
    @forelse ($contacts as $i => $contact)
      <div class="mt-3 gap-5 grid sm:grid-flow-col grid-flow-row justify-stretch">
        <div>
          <x-input-label-dark for="name" :value="__('Contact Name ' . $i + 1)" />
          <x-text-input wire:model="contacts.{{ $i }}.name" id="name.{{ $i }}" name="name"
            class="mt-1 block w-full" />
          <x-input-error :messages="$errors->get('contacts.' . $i . '.name')" class="mt-2" />
        </div>

        <div>
          <x-input-label-dark for="email" :value="__('Email')" />
          <x-text-input wire:model="contacts.{{ $i }}.email" id="email.{{ $i }}" name="email"
            class="mt-1 block w-full" />
          <x-input-error :messages="$errors->get('contacts.' . $i . '.email')" class="mt-2" />
        </div>

        <div>
          <x-input-label-dark for="number" :value="__('Number')" />
          <x-text-input wire:model="contacts.{{ $i }}.number" id="number.{{ $i }}" name="number"
            class="mt-1 block w-full" />
          <x-input-error :messages="$errors->get('contacts.' . $i . '.number')" class="mt-2" />
        </div>
        <div class="flex place-items-end">
          <button
            class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2"
            wire:click.prevent="remove({{ $i }})">Remove</button>
        </div>
      </div>
    @empty
      <div class="w-full text-center my-5">
        <span class="tei-text">No Additional Contacts Press Add Button</span>
      </div>
    @endforelse
    <div class="flex items-center gap-4 mt-5">
      <x-primary-button>{{ __('Save') }}</x-primary-button>

      <x-action-message class="me-3" on="contact-updated">
        {{ __('Contacts Saved.') }}
      </x-action-message>
    </div>
  </form>
</section>
