<div>
  <style>
    /* .container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
} */
/* 
    .input {
      width: 40px;
      border: none;
      border-bottom: 3px solid rgba(0, 0, 0, 0.5);
      margin: 0 10px;
      text-align: center;
      font-size: 36px;
      cursor: pointer;
      pointer-events: all;
    }

    .input:focus {
      border-bottom: 3px solid #E76727;
      outline: none;
    } */
  </style>

  <div class="p-10 bg-white border shadow-xl rounded-md flex flex-col gap-10">
    <div class="text-center">
      <i class="fa-solid fa-shield-halved text-7xl text-blue-500"></i>
    </div>
    <div class="text-center">
      @error('otpCode')
        <span class="mb-4 text-xs text-red-600 dark:text-red-500">
          {{ $message }}
        </span>
      @enderror
      <h3 class="text-xl uppercase font-extrabold ">Enter OTP CODE</h3>
      <p class="text-neutral-400 text-sm">We have sent a verification code to your email and mobile number.</p>
    </div>
    <div>
      <div class="container">
        <div id="inputs" class="grid grid-cols-6 gap-3">
          {{-- @for ($count = 0; $count < 6; $count++)
          <input id="otp-input-{{ $count }}" type="text"
            class="rounded-md border-2 focus:border-orange-500 focus:ring-orange-500" maxlength="1"
            wire:model.live="otpInputs.{{ $count }}" {{ $autoFocus == $count ? 'autofocus' : '' }}>
        @endfor --}}
          @for ($count = 0; $count < 6; $count++)
            <input class="input p-0 focus:ring-0 focus:border-orange-500 text-4xl font-extrabold" type="text" inputmode="numeric" maxlength="1"
              wire:model.live="otpInputs.{{ $count }}" {{ $autoFocus == $count ? 'autofocus' : '' }}/>
          @endfor
        </div>
      </div>
      <div>
        <span
          class="text-xs font-semibold {{ $this->expiresAt == 'Time has passed.' ? 'text-red-500' : 'text-green-500' }}">{{ $this->expiresAt }}</span>
        <div class="text-center">
          <button wire:click="resendModal"
            class="underline text-sm text-neutral-400  hover:text-neutral-500 rounded-md focus:outline-none focus:ring-0">
            Resend code?
          </button>
        </div>
      </div>
    </div>
    <div class="text-center">
      <button id="submitButton" wire:click="submitOtp"
        class="px-10 py-2 bg-blue-600 hover:bg-blue-700 rounded-md shadow-lg text-white font-extrabold text-sm"
        {{ $autoFocus == 6 }} {{ $disableButton ? 'disabled' : '' }}>Verify
        OTP</button>
      <div class="text-center">
        <button wire:click="loginPage"
          class="underline text-sm text-neutral-400  hover:text-neutral-500 rounded-md focus:outline-none focus:ring-0">
          Go back to login page
        </button>
      </div>
    </div>

    {{-- Resend Modal --}}
    <div id="resend-modal" tabindex="-1" data-modal-backdrop="static" tabindex="-1" aria-hidden="true"
      class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
      wire:ignore.self>
      <div class="relative p-4 w-full max-w-md max-h-full" wire:loading.remove wire:target="resendCode">
        <div class="relative bg-white rounded-lg shadow">
          <button type="button" wire:click.prevent="closeResendModal"
            class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
          <div class="p-4 md:p-5 text-center">
            <svg class="mx-auto mb-4 tei-text-primary w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
              fill="none" viewBox="0 0 20 20">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <h3 class="mb-5 text-lg font-normal tei-text-primary ">Are you sure you want to request another code?</h3>
            <p class="text-sm text-green-500 font-extrabold mb-4">{{ $resendTime }}</p>

            <button type="button"
              class="{{ !$resendTime ? 'bg-green-600 hover:bg-green-900' : 'bg-neutral-400' }} text-white focus:ring-4 focus:outline-none  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
              {{ $resendTime ? 'disabled' : '' }} wire:click= "resendCode">
              Confirm
            </button>
            <button wire:click.prevent="closeResendModal" type="button"
              class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10 focus:ring-4 focus:ring-gray-100">
              Cancel</button>
          </div>
        </div>
      </div>
      <div class="bg-white w-full  max-w-md max-h-full rounded-md px-32 py-14" wire:loading wire:target="resendCode">
        <div class="text-center">
          <span class="tei-text-primary font-extrabold">Please wait</span>
        </div>
        <div class="flex justify-center">
          <div class="loading loading-main">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>
      </div>
    </div>
    {{-- END Resend Modal --}}
  </div>
  @script
    <script>
      $wire.on('openResendModal', () => {
        var modalElement = document.getElementById('resend-modal');
        var modal = new Modal(modalElement, {
          backdrop: 'static'
        });
        modal.show();
      });

      $wire.on('closeResendModal', () => {
        var modalElement = document.getElementById('resend-modal');
        var modal = new Modal(modalElement);
        modal.hide();
      });
      const inputs = document.getElementById("inputs");

      inputs.addEventListener("input", function(e) {
        const target = e.target;
        const val = target.value;

        if (isNaN(val)) {
          target.value = "";
          return;
        }

        if (val != "") {
          const next = target.nextElementSibling;
          if (next) {
            next.focus();
          }
        }
      });

      inputs.addEventListener("keyup", function(e) {
        const target = e.target;
        const key = e.key.toLowerCase();

        if (key == "backspace" || key == "delete") {
          target.value = "";
          const prev = target.previousElementSibling;
          if (prev) {
            prev.focus();
          }
          return;
        }
      });
    </script>
  @endscript
