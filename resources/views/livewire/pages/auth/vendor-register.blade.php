<?php

use Livewire\Volt\Component;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\ClassProduct;
use App\Models\VendorClass;
use App\Models\VendorNature;
use App\Models\UserOtp;
use App\Helpers\SmsNotification;
use App\Mail\OtpMail;
use App\Mail\SuccessVendorRegistration;

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
        $this->name = $this->user->name;
        $this->address = $this->user->address;
        $this->number = $this->user->number;
        // $this->natureBusiness = [
        //     'manufacturer' => false,
        //     'trading' => false,
        //     'service provider' => false,
        // ];
        // $getAllClass = $this->getClass();

        // foreach ($getAllClass as $class) {
        //     $this->classes[$class['code']] = [
        //         'description' => $class['description'],
        //         'select' => false,
        //     ];
        // }
        // $this->classes();
    }
    // public function getClass()
    // {
    //     return ClassProduct::all();
    // }
    // public function classes()
    // {
    //     $this->selectedClasses = collect($this->classes)
    //         ->where('select', true)
    //         ->toArray();
    // }
    // public function selectClass($code)
    // {
    //     $this->classes[$code]['select'] = !$this->classes[$code]['select'];
    //     $this->classes();
    // }
    public function resetClass()
    {
        foreach ($this->classes as $index => $class) {
            $this->classes[$index] = [
                'description' => $class['description'],
                'select' => false,
            ];
        }
        $this->classes();
    }
    public function vendorForm()
    {
        // dd($this->selectedClasses);
        // $nob = [];
        // $nob = $this->natureBusiness;
        // if (!$this->inputHidden && $this->othersInput != null) {
        //     $nob[$this->othersInput] = true;
        // }
        // $this->nature = array_keys(array_filter($nob));

        $vendor = User::where('email', $this->email)->first();
        $validated = $this->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
                'address' => ['required'],
                'number' => ['required'],
                'tin_no' => ['required'],
                'dti_sec_no' => ['required'],
                'password' => ['required', 'string', 'confirmed', Password::defaults()],
                // 'nature' => ['required'],
                // 'selectedClasses' => ['required'],
            ],
            [
                // 'nature.required' => 'The nature of business is required.',
                // 'selectedClasses.required' => 'The product/services is required.',
                'dti_sec_no.required' => 'The dti no or sec no is required.',
            ],
        );

        $validated['token'] = null;
        $validated['active'] = true;

        // vendor update
        $vendor->update($validated);

        // // Vendor Nature of Business
        // foreach ($this->nature as $data) {
        //     if ($data == 'manufacturer' || $data == 'trading' || $data == 'service provider') {
        //         VendorNature::create([
        //             'vendor_id' => $vendor->id,
        //             'name' => $data,
        //         ]);
        //     } else {
        //         VendorNature::create([
        //             'vendor_id' => $vendor->id,
        //             'others' => $data,
        //         ]);
        //     }
        // }

        // // Vendor Products/Services
        // foreach ($this->selectedClasses as $index => $classData) {
        //     VendorClass::create([
        //         'vendor_id' => $vendor->id,
        //         'code' => $index,
        //         'description' => $classData['description'],
        //     ]);
        // }

        Auth::login($vendor);

        $otpCode = mt_rand(100000, 999999);

        UserOtp::create([
            'user_id' => $vendor->id,
            'otp' => $otpCode,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Mail Notification
        Mail::to(Auth::user()->email)->send(new OtpMail($vendor, $otpCode));

        // SMS Notification
        $content = 'Your One-Time Password (OTP) for Secure Access to e-Procurement: ' . $otpCode;
        SmsNotification::globeSms($vendor->number, $content);
        // $this->redirect(route('otp-page', absolute: false), navigate: true);

        Mail::to($vendor->email)->send(new SuccessVendorRegistration($vendor));
        return redirect()->route('otp-page');
    }

    public function selectedBusiness($value)
    {
        $this->natureBusiness[$value] = !$this->natureBusiness[$value];
    }
    public function selectedOther()
    {
        $this->inputHidden = !$this->inputHidden;
    }
}; ?>

<div>
  <form wire:submit="vendorForm">
    @csrf
    <!-- Name -->
    <div>
      <x-input-label for="name" :value="__('Company Name')" />
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

    <!-- Tin Number -->
    <div class="mt-4">
      <x-input-label for="email" :value="__('Tin Number')" />
      <x-text-input wire:model="tin_no" class="block mt-1 w-full" type="text" name="number"
        autocomplete="username" />
      <x-input-error :messages="$errors->get('tin_no')" class="mt-2" />
    </div>

    <!-- DTI or SEC Number -->
    <div class="mt-4">
      <x-input-label for="email" :value="__('DTI Business Name No./SEC Company Registration No.')" />
      <x-text-input wire:model="dti_sec_no" class="block mt-1 w-full" type="text" name="number"
        autocomplete="username" />
      <x-input-error :messages="$errors->get('dti_sec_no')" class="mt-2" />
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

    {{-- <!-- Nature of Business -->
    <div class="mt-4">
      <x-input-label for="nature_business" :value="__('Nature of Business')" />
      <div class="pt-2 leading-loose">
        @foreach ($natureBusiness as $index => $business)
          <small
            class="text-white font-black text-nowrap px-2 py-1 rounded-md cursor-pointer {{ $business ? 'tei-bg-secondary hover:bg-orange-700' : 'bg-gray-500 hover:bg-gray-600' }}"
            wire:click="selectedBusiness('{{ $index }}')">{{ strtoupper($index) }}</small>
        @endforeach
      </div>
      <div class="flex gap-4 pt-3">
        <small
          class="text-white font-black text-nowrap px-2 py-1 rounded-md cursor-pointer {{ $inputHidden ? 'bg-gray-500 hover:bg-gray-600' : 'tei-bg-secondary hover:bg-orange-700' }}"
          wire:click="selectedOther">OTHERS</small>
        <div {{ $inputHidden ? 'hidden' : '' }}>
          <x-text-input wire:model="othersInput" class="block mt-1 w-50" type="text" />
        </div>
      </div>
      @if ($errors->has('nature'))
        <x-input-error :messages="$errors->first('nature')" class="mt-2" />
      @endif
    </div>

    <!-- Product/Service -->
    <div class="mt-4">
      <x-input-label for="nature_business" :value="__('Product/Services')" />
      <div class="pt-2 leading-loose">
        @forelse ($selectedClasses as $index => $class)
          <small
            class="text-white font-black text-nowrap px-2 py-1 rounded-md cursor-pointer 
            {{ $class ? 'tei-bg-secondary hover:bg-orange-700' : 'bg-gray-500 hover:bg-gray-600' }}"
            wire:click="selectClass('{{ $index }}')">{{ $class['description'] }}</small>
        @empty
          <small class="text-white">Please click view more to select products/services.</small>
        @endforelse
      </div>
      <div class="flex gap-4 pt-3">
        <small
          class="text-white font-black text-nowrap px-2 py-1 rounded-md cursor-pointer bg-green-600 hover:bg-green-800"
          data-modal-target="class-modal" data-modal-toggle="class-modal">view
          more</small>
      </div>
      @if ($errors->has('selectedClasses'))
        <x-input-error :messages="$errors->first('selectedClasses')" class="mt-2" />
      @endif
    </div> --}}
    <div class="text-center mt-4">
      {{-- <x-primary-button class="ms-4">
          {{ __('Register') }}
        </x-primary-button> --}}
      <button type="submit" wire:loading.remove wire:target="vendorForm"
        class="w-full tei-btn-secondary focus:ring-4 focus:ring-orange-700 font-medium rounded-full text-sm px-5 py-2.5 me-2 mb-2">{{ __('Update') }}</button>
      <div class="w-full rounded-full tei-bg-light flex justify-center p-4 md:p-5" wire:loading wire:target="vendorForm" >
        <div class="flex justify-center ">
          <div class="loading-small loading-main">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>
      </div>
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
