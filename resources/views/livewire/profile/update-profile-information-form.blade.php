<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use App\Models\VendorNature;
use App\Models\VendorClass;
use Livewire\WithFileUploads;
use App\Models\ClassProduct;

new class extends Component {
    use WithFileUploads;

    public $vendorNatures, $initNatureBusiness, $othersInput, $others;
    public $classes, $allClass;
    public string $name = '';
    public string $email = '';
    public string $number = '';
    public $tin_no;
    public $dti_sec_no;
    public $companyFile,
        $company_profile,
        $hasProfile,
        $disabledInput = false,
        $hasFile = false;
    public string $address = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->number = Auth::user()->number;
        $this->tin_no = Auth::user()->tin_no;
        $this->dti_sec_no = Auth::user()->dti_sec_no;
        $this->address = Auth::user()->address;
        $this->company_profile = Auth::user()->company_profile;
        $this->hasProfile = $this->company_profile ? true : false;

        // Nature of Busisness
        $this->vendorNatures = Auth::user()->vendorNatures->pluck('name')->toArray();
        $this->others = Auth::user()->vendorNatures()->where('others', true)->get()->pluck('name')->toArray();
        foreach ($this->others as $other) {
            $this->vendorNatures[] = $other;
        }
        $this->initNatureBusiness = ['manufacturer', 'trading', 'service provider'];

        // Products/Services
        $getAllClass = $this->getClass();
        foreach ($getAllClass as $class) {
            $existingClass = Auth::user()
                ->vendorClasses()
                ->where('code', $class['code'])
                ->first();
            $this->allClass[$class['code']] = [
                'description' => $class['description'],
                'select' => $existingClass ? true : false,
            ];
        }
        $this->classesTrue();
    }
    public function classesTrue()
    {
        $this->classes = collect($this->allClass)
            ->where('select', true)
            ->toArray();
    }

    public function getClass()
    {
        return ClassProduct::all();
    }

    public function selectClass($code)
    {
        $this->allClass[$code]['select'] = !$this->allClass[$code]['select'];
        $this->classesTrue();
    }
    public function selectedBusiness($value)
    {
        if (in_array($value, $this->vendorNatures)) {
            foreach ($this->vendorNatures as $key => $nature) {
                if ($value == $nature) {
                    unset($this->vendorNatures[$key]);
                }
            }
        } else {
            $this->vendorNatures[] = $value;
        }
    }
    public function addOthers()
    {
        if ($this->othersInput != null) {
            $this->vendorNatures[] = $this->othersInput;
            $this->others[] = $this->othersInput;
            $this->othersInput = '';
        }
    }
    public function removeOthers($index)
    {
        // unset($this->vendorNatures[$index]);
        // $this->vendorNatures = array_values($this->vendorNatures);
        unset($this->others[$index]);
        $this->others = array_values($this->others);
        foreach ($this->vendorNatures as $key => $value) {
            if (!in_array($value, ['manufacturer', 'trading', 'service provider']) && !in_array($value, $this->others)) {
                unset($this->vendorNatures[$key]);
            }
        }
    }

    public function updatedHasFile()
    {
        $this->disabledInput = !$this->hasFile;
    }
    public function removeFile()
    {
        $this->hasProfile = false;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
                'number' => ['required'],
                'tin_no' => ['required'],
                'dti_sec_no' => ['required'],
                'address' => ['required'],
                'vendorNatures' => ['required'],
                'classes' => ['required'],
                'companyFile' => $this->hasFile ? 'required|file|mimes:pdf' : '',
            ],
            [
                'vendorNatures.required' => 'The nature of business is required.',
                'dti_sec_no.required' => 'The dti no or sec no is required.',
                'classes.required' => 'The products/services is required.',
            ],
        );
        $attachName = null;
        if ($this->hasFile) {
            $attachName = strtolower(str_replace(' ', '_', $this->name) . '_company_profile_' . time() . '.' . $this->companyFile->extension());
            $files = $attachName;

            $this->companyFile->storeAs('company-profile', $attachName, 'public');
        }

        $validated['company_profile'] = $attachName;
        $this->hasProfile = $validated['company_profile'] ? true : false;

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Vendor Nature of Business
        $user->vendorNatures()->delete();
        foreach ($this->vendorNatures as $nature) {
            if ($nature == 'manufacturer' || $nature == 'trading' || $nature == 'service provider') {
                VendorNature::create([
                    'vendor_id' => $user->id,
                    'name' => $nature,
                ]);
            } else {
                VendorNature::create([
                    'vendor_id' => $user->id,
                    'name' => $nature,
                    'others' => true,
                ]);
            }
        }

        // Vendor Products/Services
        $user->vendorClasses()->delete();
        foreach ($this->classes as $index => $classData) {
            VendorClass::create([
                'vendor_id' => $user->id,
                'code' => $index,
                'description' => $classData['description'],
            ]);
        }

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
  <header>
    <h2 class="text-4xl font-black tei-text-primary" style="text-shadow:0px 4px 12px rgb(107, 107, 107);">
      {{ __('VENDOR INFORMATION') }}
    </h2>

    <p class="mt-3 text-sm text-gray-600">
      {{ __("Update your account's vendor information and email address.") }}
    </p>
  </header>

  <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
    <div class="grid sm:grid-cols-2 grid-cols-1 gap-4">
      <div class="space-y-6">
        <div>
          <x-input-label-dark for="name" :value="__('Vendor Name')" />
          <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required
            autocomplete="name" />
          <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
          <x-input-label-dark for="email" :value="__('Email')" />
          <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full"
            required autocomplete="username" />
          <x-input-error class="mt-2" :messages="$errors->get('email')" />

          @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
            <div>
              <p class="text-sm mt-2 text-gray-800">
                {{ __('Your email address is unverified.') }}

                <button wire:click.prevent="sendVerification"
                  class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                  {{ __('Click here to re-send the verification email.') }}
                </button>
              </p>

              @if (session('status') === 'verification-link-sent')
                <p class="mt-2 font-medium text-sm text-green-600">
                  {{ __('A new verification link has been sent to your email address.') }}
                </p>
              @endif
            </div>
          @endif
        </div>

        <div>
          <x-input-label-dark for="number" :value="__('Contact Number')" />
          <x-text-input wire:model="number" id="number" name="number" type="text" class="mt-1 block w-full"
            required autocomplete="name" />
          <x-input-error class="mt-2" :messages="$errors->get('number')" />
        </div>

        <div>
          <x-input-label-dark for="address" :value="__('Address')" />
          <textarea wire:model="address" name="address" id="address" cols="10" rows="3"
            class="border-gray-300 rounded-md shadow-sm focus:ring-orange-700 focus:border-orange-700 mt-1 block w-full font-medium text-sm tei-text"></textarea>
          <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>
      </div>
      <div class="space-y-6">
        <div>
          <x-input-label-dark :value="__('Tin Number')" />
          <x-text-input wire:model="tin_no" type="text" class="mt-1 block w-80" />
          <x-input-error class="mt-2" :messages="$errors->get('tin_no')" />
        </div>
        <div>
          <x-input-label-dark for="name" :value="__('DTI Business Name No./SEC Company Registration No.')" />
          <x-text-input wire:model="dti_sec_no" type="text" class="mt-1 block w-80" />
          <x-input-error class="mt-2" :messages="$errors->get('dti_sec_no')" />
        </div>
        <div>
          <x-input-label-dark for="address" :value="__('Company Profile')" />
          @if ($this->hasProfile)
            <div class="mt-2">
              <span class="tei-text-primary hover:text-orange-700 cursor-pointer"
              data-modal-target="file-modal" data-modal-toggle="file-modal">
                View Company Profile <i class="fa-regular fa-eye"></i>
              </span>
              <span class="text-red-700 ml-5 cursor-pointer hover:text-red-900" wire:click.prevent="removeFile"><i
                  class="fa-solid fa-trash-can"></i> remove</span>
            </div>
          @else
            <div class="flex gap-2">
              <input
                class="block w-80 mb-5 mt-4 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 "
                type="file" wire:model="companyFile" {{ $hasFile ? '' : 'disabled' }}>
              <label class="inline-flex items-center mb-2 cursor-pointer">
                <input type="checkbox" value="" class="sr-only peer" wire:model.live="hasFile">
                <div
                  class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all peer-checked:bg-blue-600">
                </div>
                <span class="ms-3 text-sm font-medium text-gray-900">Disabled/Enabled Uploads</span>
              </label>

            </div>
          @endif
          <x-input-error class="mt-2" :messages="$errors->get('companyFile')" />
        </div>
      </div>
    </div>

    <div class="w-1/2">
      <div>
        <x-input-label-dark for="address" :value="__('Nature of Business')" />
        <div class="pt-2 leading-loose">
          @foreach ($vendorNatures as $nature)
            @if ($nature)
              <small
                class="text-white font-black text-nowrap px-2 py-1 rounded-md cursor-pointer tei-bg-primary hover:bg-gray-600">{{ strtoupper($nature) }}</small>
            @endif
          @endforeach
          @if (!$vendorNatures)
            <small>You have to select nature of business.</small>
          @endif
          <small data-modal-target="nature-modal" data-modal-toggle="nature-modal"
            class="font-black pl-3 underline italic cursor-pointer hover:text-slate-700">show more</small>
        </div>

        <x-input-error class="mt-2" :messages="$errors->get('vendorNatures')" />
      </div>

      <div>
        <x-input-label-dark for="address" :value="__('Product/Services')" />
        <div class="pt-2 leading-loose">
          @forelse ($classes as $class)
            <small
              class="text-white font-black text-nowrap px-2 py-1 rounded-md cursor-pointer tei-bg-primary hover:bg-gray-600">{{ $class['description'] }}</small>
          @empty
            <small>Please click show more to select products/services.</small>
          @endforelse
        </div>
        <div>
          <small data-modal-target="class-modal" data-modal-toggle="class-modal"
            class="font-black pl-3 underline italic cursor-pointer hover:text-slate-700">show more</small>
        </div>

        <x-input-error class="mt-2" :messages="$errors->get('classes')" />
      </div>
    </div>


    <div class="flex items-center gap-4">
      <x-primary-button>{{ __('Save') }}</x-primary-button>

      <x-action-message class="me-3" on="profile-updated">
        {{ __('Vendor Information Saved.') }}
      </x-action-message>
    </div>
  </form>


  {{-- nature Modal --}}
  <div id="nature-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-2xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-semibold text-gray-900">
            Nature of Business
          </h3>
          <button type="button"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
            data-modal-hide="nature-modal">
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
            @foreach ($initNatureBusiness as $index => $nature)
              <small
                class="text-white font-black text-nowrap px-2 py-1 rounded-md cursor-pointer {{ in_array($nature, $vendorNatures) ? 'tei-bg-secondary hover:bg-orange-700' : 'bg-gray-500 hover:bg-gray-600' }}"
                wire:click="selectedBusiness('{{ $nature }}')">{{ strtoupper($nature) }}</small>
            @endforeach
            <div class="w-64">
              <small class="" wire:click="selectedOther">OTHERS</small>
              <div>
                @foreach ($others as $key => $other)
                  <small
                    class="text-white font-black text-nowrap px-2 py-1 rounded-md cursor-pointer tei-bg-secondary hover:bg-orange-700 mr-2">{{ strtoupper($other) }}
                    <i class="fa-solid fa-xmark pl-1" wire:click="removeOthers({{ $key }})"></i>
                  </small>
                @endforeach
              </div>
              <div class="flex justify-end w-full">
                <x-text-input wire:model="othersInput" class="block mt-1 w-full mr-2" type="text" />
                <small
                  class="text-white font-black text-nowrap px-2 py-1 rounded-md cursor-pointer tei-bg-primary hover:bg-gray-600"
                  wire:click="addOthers">ADD</small>
              </div>
            </div>
          </div>
        </div>
        <!-- Modal footer -->
        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
          <button data-modal-hide="nature-modal" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">Close</button>
        </div>
      </div>
    </div>
  </div>
  {{-- END Nature Modal --}}

  {{-- Class Modal --}}
  <div id="class-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-2xl max-h-full">
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
            @foreach ($allClass as $index => $data)
              <small
                class="text-white font-black text-nowrap px-2 py-1 rounded-md cursor-pointer {{ $data['select'] ? 'tei-bg-secondary hover:bg-orange-700' : 'bg-gray-500 hover:bg-gray-600' }}"
                wire:click="selectClass('{{ $index }}')">{{ $data['description'] }}</small>
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

  {{-- View File --}}
  <div id="file-modal" tabindex="-1"
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-4xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow ">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t ">
          <h3 class="text-xl font-medium text-gray-900 ">
            Company Profile
          </h3>
          <button type="button"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
            data-modal-hide="file-modal">
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
          <div class=" flex justify-center">
            <iframe src="{{ asset('storage/company-profile/'.$company_profile)}}" frameborder="1" width="1000"
              height="850"></iframe>
          </div>
        </div>
        <!-- Modal footer -->
        <div
          class="flex items-center p-4 md:p-5 space-x-3 rtl:space-x-reverse border-t border-gray-200 rounded-b">
          <button data-modal-hide="file-modal" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">Close</button>
        </div>
      </div>
    </div>
  </div>
  {{-- End View File --}}
</section>
