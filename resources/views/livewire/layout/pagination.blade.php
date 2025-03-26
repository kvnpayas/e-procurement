{{-- @php
  if (! isset($scrollTo)) {
      $scrollTo = 'body';
  }

  $scrollIntoViewJsSnippet = ($scrollTo !== false)
      ? <<<JS
        (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
      JS
      : '';
@endphp --}}

<div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
  <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
    <div>
      <p class="text-sm text-gray-700 leading-5">
        <span>{!! __('Showing') !!}</span>
        <span class="font-medium">{{ $paginator->firstItem() }}</span>
        <span>{!! __('to') !!}</span>
        <span class="font-medium">{{ $paginator->lastItem() }}</span>
        <span>{!! __('of') !!}</span>
        <span class="font-medium">{{ $paginator->total() }}</span>
        <span>{!! __('results') !!}</span>
      </p>
    </div>
    @if ($paginator->hasPages())
      <nav role="navigation" aria-label="Pagination Navigation"
        class="flex items-center justify-between px-4 py-3 sm:px-6">
        <div class="flex justify-between flex-1 sm:hidden">
          <span>
            @if ($paginator->onFirstPage())
              <span
                class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 cursor-default rounded-l-md leading-5">Previous</span>
            @else
              <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')"
                dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before"
                class="relative block rounded px-2 py-1.5 tei-bg-primary drop-shadow-md hover:drop-shadow-lg hover:bg-sky-900 active:bg-tei-bg-primary-active text-white">
                Previous
              </button>
            @endif
          </span>

          <span>
            @if ($paginator->hasMorePages())
              <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')"
                dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before"
                class="relative block rounded px-2 py-1.5 tei-bg-primary drop-shadow-md hover:drop-shadow-lg hover:bg-sky-900 active:bg-tei-bg-primary-active text-white">
                Next
              </button>
            @else
              <span
                class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 cursor-default rounded-l-md leading-5">
                Next
              </span>
            @endif
          </span>
        </div>

        {{-- <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between"> --}}
        {{-- <div>
          <p class="text-sm text-gray-700 leading-5">
            <span>{!! __('Showing') !!}</span>
            <span class="font-medium">{{ $paginator->firstItem() }}</span>
            <span>{!! __('to') !!}</span>
            <span class="font-medium">{{ $paginator->lastItem() }}</span>
            <span>{!! __('of') !!}</span>
            <span class="font-medium">{{ $paginator->total() }}</span>
            <span>{!! __('results') !!}</span>
          </p>
        </div> --}}

        <div>
          <span class="relative z-0 inline-flex rounded-md">
            <span class="mx-2">
              {{-- Previous Page Link --}}
              @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                  <span
                    class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 cursor-default rounded-l-md leading-5"
                    aria-hidden="true">
                    Previous
                  </span>
                </span>
              @else
                <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.remove wire:target="previousPage('{{ $paginator->getPageName() }}')"
                  dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                  class="relative block rounded px-2 py-1.5 tei-bg-primary drop-shadow-md hover:drop-shadow-lg hover:bg-sky-900 active:bg-tei-bg-primary-active text-white"
                  aria-label="{{ __('pagination.previous') }}">
                  Previous
                </button>
                <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4" wire:loading
                  wire:target="previousPage('{{ $paginator->getPageName() }}')">
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
              @endif
            </span>

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
              {{-- "Three Dots" Separator --}}
              @if (is_string($element))
                <span aria-disabled="true">
                  <span
                    class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5 select-none">{{ $element }}</span>
                </span>
              @endif

              {{-- Array Of Links --}}
              @if (is_array($element))
                @foreach ($element as $page => $url)
                  <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                    @if ($page == $paginator->currentPage())
                      <span aria-current="page">
                        <span
                          class="relative z-10 inline-flex items-center hover:bg-orange-500 tei-bg-secondary px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ $page }}</span>
                      </span>
                    @else
                      <button type="button"
                        wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                        aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                        {{ $page }}
                      </button>
                    @endif
                  </span>
                @endforeach
              @endif
            @endforeach

            <span class="mx-2">
              {{-- Next Page Link --}}
              @if ($paginator->hasMorePages())
                <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.remove
                  wire:target="nextPage('{{ $paginator->getPageName() }}')"
                  dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                  class="relative block rounded px-2 py-1.5 tei-bg-primary drop-shadow-md hover:drop-shadow-lg hover:bg-sky-900 active:bg-tei-bg-primary-active text-white"
                  aria-label="{{ __('pagination.next') }}">
                  Next
                </button>
                <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4" wire:loading
                  wire:target="nextPage('{{ $paginator->getPageName() }}')">
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
              @else
                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                  <span
                    class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 cursor-default rounded-l-md leading-5"
                    aria-hidden="true">
                    Next
                  </span>
                </span>
              @endif
            </span>
          </span>
        </div>
      </nav>
    @endif
  </div>
</div>
