<div>
  @if (session('success'))
    <div id="toast-success"
      class="fixed top-[7%] left-[43%] flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-green-500 rounded-lg shadow"
      role="alert">
      <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-white rounded-lg">
        <i class="fa-solid fa-circle-check text-success"></i>
        <span class="sr-only">Check icon</span>
      </div>
      <div class="ms-3 text-sm font-normal tei-text">{{ session('success') }}</div>
    </div>
  @endif
  <div id="toast-success-hidden"
    class="hidden fixed top-[7%] left-[43%] items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-green-500 rounded-lg shadow"
    role="alert">
    <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-white rounded-lg">
      <i class="fa-solid fa-circle-check text-success"></i>
      <span class="sr-only">Check icon</span>
    </div>
    <div class="ms-3 text-sm font-normal tei-text">{{ session('success') }}</div>
  </div>
</div>
@section('toast-script')
  <script>
    document.addEventListener('DOMContentLoaded', function() {

      function showToast() {
        var toast = document.getElementById('toast-success');
        if (toast) {
          toast.style.opacity = 1;


          setTimeout(function() {
            toast.style.transition = 'opacity 1.5s';
            toast.style.opacity = 0;
          }, 2000);
          toast.addEventListener('transitionend', function() {
            toast.remove();
          });
        }
      }

      showToast();
    });

    window.addEventListener('success-message', event => {
      const message = event.detail[0].message;
      const toast = document.getElementById('toast-success-hidden');
      toast.querySelector('.tei-text').textContent = message;
      toast.classList.remove('hidden');
      toast.classList.add('flex');
      setTimeout(() => {
        toast.classList.add('hidden');
      }, 3000); // Hide after 3 seconds
    });
  </script>
@endsection
