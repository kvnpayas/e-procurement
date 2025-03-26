<?php

use Livewire\Volt\Component;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

new class extends Component {
    public $userId, $user, $name, $tin_no, $dti_sec_no, $email, $address, $number, $password, $password_confirmation;
    public $natureBusiness = [],
        $inputHidden = true,
        $othersInput,
        $classes = [],
        $nature = [],
        $selectedClasses = [];

    public function mount($user): void
    {
        $this->user = $user;
        $this->userId = $user->id;
        $this->email = $this->user->email;
    }


    public function vendorForm()
    {
        $vendor = User::where('email', $this->email)->first();
        $validated = $this->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
                'address' => ['required'],
                'number' => ['required'],
                'password' => ['required', 'string', 'confirmed', Password::defaults()],
            ]
        );

        $validated['token'] = null;
        $validated['active'] = true;

        // vendor update
        $vendor->update($validated);

        Auth::login($vendor);

        return redirect()->route('dashboard');
    }
}; ?>

<div>
  <form wire:submit="vendorForm">
    @csrf

     <!-- Roles -->
     <div class="mt-4">
      <span class="text-white text-2xl font-extrabold">Role:</span>
      <span class="text-green-500  text-2xl font-extrabold ml-2">Administrator</span>
    </div>

     <!-- Name -->
     <div class="mt-4">
      <x-input-label for="name" :value="__('Name')" />
      <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" autofocus
        autocomplete="name" />
      <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <!-- Email Address -->
    <div class="mt-4">
      <x-input-label for="email" :value="__('Email')" />
      <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email"
        autocomplete="username" readonly />
      <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- Address -->
    <div class="mt-4">
      <x-input-label for="email" :value="__('Address')" />
      <textarea wire:model="address" name="address" id="address" cols="10" rows="5"
        class="border-gray-300 rounded-md shadow-sm focus:ring-orange-700 focus:border-orange-700 mt-1 block w-full font-medium text-sm tei-text"></textarea>
      <x-input-error :messages="$errors->get('address')" class="mt-2" />
    </div>

    <!-- Number -->
    <div class="mt-4">
      <x-input-label for="email" :value="__('Contact Number')" />
      <x-text-input wire:model="number" class="block mt-1 w-full" type="text" name="number"
        autocomplete="username" />
      <x-input-error :messages="$errors->get('number')" class="mt-2" />
    </div>

    <!-- Password -->
    <div class="mt-4">
      <x-input-label for="password" :value="__('Password')" />

      <x-text-input wire:model="password" id="password" class="block mt-1 w-full" type="password" name="password"
        required autocomplete="new-password" />

      <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <!-- Confirm Password -->
    <div class="mt-4">
      <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

      <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
        type="password" name="password_confirmation" required autocomplete="new-password" />

      <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>

    <div class="text-center mt-4">
      {{-- <x-primary-button class="ms-4">
          {{ __('Register') }}
        </x-primary-button> --}}
      <button type="submit"
        class="w-full tei-btn-secondary focus:ring-4 focus:ring-orange-700 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2">{{ __('Update') }}</button>
      <a class="underline text-sm text-white hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        href="{{ route('login') }}" wire:navigate>
        {{ __('Already registered?') }}
      </a>
    </div>
  </form>

  {{-- Class Modal --}}
  <div id="class-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-4xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-semibold text-gray-900">
            Products/Services
          </h3>
          <button type="button"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
            data-modal-hide="class-modal">
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
            @foreach ($classes as $index => $class)
              <small
                class="text-white text-nowrap font-black px-2 py-1 rounded-md cursor-pointer {{ $class['select'] ? 'tei-bg-secondary hover:bg-orange-700' : 'bg-gray-500 hover:bg-gray-600' }}"
                wire:click="selectClass('{{ $index }}')">{{ $class['description'] }}</small>
            @endforeach
          </div>
        </div>
        <!-- Modal footer -->
        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
          <button type="button" wire:click="resetClass"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Reset</button>
          <button data-modal-hide="class-modal" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">Close</button>
        </div>
      </div>
    </div>
  </div>
  {{-- END Class Modal --}}
</div>
