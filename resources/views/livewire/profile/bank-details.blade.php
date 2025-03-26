<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use App\Models\VendorBank;

new class extends Component {
    public $user, $bankName, $bankAddress, $accountName, $accountNumber;

    public function mount()
    {
      $this->user = Auth::user();
      $vendorBank = $this->user->vendorBank;
      if($vendorBank){
        $this->bankName = $vendorBank->bank_name;
        $this->bankAddress = $vendorBank->bank_address;
        $this->accountName = $vendorBank->account_name;
        $this->accountNumber = $vendorBank->account_number;
      }
    }
    public function updateForm(): void
    {
        $validated = $this->validate([
            'bankName' => ['required'],
            'bankAddress' => ['required'],
            'accountName' => ['required'],
            'accountNumber' => ['required'],
        ]);
        $data = [
            'vendor_id' => Auth::user()->id,
            'bank_name' => $validated['bankName'],
            'bank_address' => $validated['bankAddress'],
            'account_name' => $validated['accountName'],
            'account_number' => $validated['accountNumber'],
        ];

        $bank = VendorBank::where('vendor_id', $data['vendor_id'])->first();
        if ($bank) {
          $bank->update($data);
        } else {
          VendorBank::create($data);
        }
        $this->dispatch('bank-details');
    }
}; ?>

<section>
  <header>
    <h2 class="text-4xl font-black tei-text-primary" style="text-shadow:0px 4px 12px rgb(107, 107, 107);">
      {{ __('BANK DETAILS') }}
    </h2>

    <p class="mt-3 text-sm text-gray-600">
      {{ __('Please check that the information you entered is accurate.') }}
    </p>
  </header>

  <form wire:submit="updateForm" class="mt-6 space-y-6">
    <div>
      <x-input-label-dark :value="__('Bank Name')" />
      <x-text-input wire:model="bankName" type="text" class="mt-1 block w-full" />
      <x-input-error :messages="$errors->get('bankName')" class="mt-2" />
    </div>

    <div>
      <x-input-label-dark :value="__('Bank Address')" />
      <textarea wire:model="bankAddress" cols="10" rows="3"
        class="border-gray-300 rounded-md shadow-sm focus:ring-orange-700 focus:border-orange-700 mt-1 block w-full font-medium text-sm tei-text"></textarea>
      <x-input-error :messages="$errors->get('bankAddress')" class="mt-2" />
    </div>

    <div>
      <x-input-label-dark :value="__('Account Name')" />
      <x-text-input wire:model="accountName" type="text" class="mt-1 block w-full" />
      <x-input-error :messages="$errors->get('accountName')" class="mt-2" />
    </div>

    <div>
      <x-input-label-dark :value="__('Account Number')" />
      <x-text-input wire:model="accountNumber" type="text" class="mt-1 block w-full" />
      <x-input-error :messages="$errors->get('accountNumber')" class="mt-2" />
    </div>

    <div class="flex items-center gap-4">
      <x-primary-button>{{ __('Save') }}</x-primary-button>

      <x-action-message class="me-3" on="bank-details">
        {{ __('Bank details updated.') }}
      </x-action-message>
    </div>
  </form>
</section>
