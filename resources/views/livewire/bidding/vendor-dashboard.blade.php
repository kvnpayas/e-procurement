<div>
  <div class="grid grid-cols-1 sm:grid-cols-5 md:grid-cols-5 lg:grid-cols-5 justify-center content-center gap-4">
    <div
      class="text-center tei-bg-light shadow-xl rounded-md flex flex-col gap-5 p-4 cursor-default hover:scale-110 transition-transform duration-300">
      <span class="py-5 text-7xl tei-text-primary font-extrabold">{{ $vendor->biddings->count() }}</span>
      <label class="text-xs uppercase font-semibold tei-text-accent">Total Number of Projects</label>
    </div>
    <div
      class="text-center tei-bg-light shadow-xl rounded-md flex flex-col gap-5 p-4 cursor-default hover:scale-110 transition-transform duration-300">
      <span class="py-5 text-7xl tei-text-primary font-extrabold">{{ $joinProjects }}</span>
      <label class="text-xs uppercase font-semibold tei-text-accent">Intent to join</label>
    </div>
    <div
      class="text-center tei-bg-light shadow-xl rounded-md flex flex-col gap-5 p-4 cursor-default hover:scale-110 transition-transform duration-300">
      <span class="py-5 text-7xl tei-text-secondary font-extrabold">{{ $submitProjects->count() }}</span>
      <label class="text-xs uppercase font-semibold tei-text-accent">Proposal(s) Submitted</label>
    </div>
    <div
      class="text-center tei-bg-light shadow-xl rounded-md flex flex-col gap-5 p-4 cursor-default hover:scale-110 transition-transform duration-300">
      <span class="py-5 text-7xl text-green-500 font-extrabold">{{ $wonProjects->count() }}</span>
      <label class="text-xs uppercase font-semibold tei-text-accent">Won</label>
    </div>
    <div
      class="text-center tei-bg-light shadow-xl rounded-md flex flex-col gap-5 p-4 cursor-default hover:scale-110 transition-transform duration-300">
      <span class="py-5 text-7xl text-red-500 font-extrabold">{{ $lostProjects->count() }}</span>
      <label class="text-xs uppercase font-semibold tei-text-accent">Lost</label>
    </div>

  </div>
  <div class=" overflow-x-auto mt-5">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 boder-collapse shadow-lg">
      <caption class="py-5 text-lg font-semibold text-left rtl:text-right tei-text-secondary bg-white ">
        Project Names
        {{-- <p class="mt-1 text-sm font-normal text-gray-500 ">Overview of latest successful bidders who have secured
          contracts or won the bid.
        </p> --}}
      </caption>
      <thead class="text-xs tei-text-primary uppercase tei-bg-light font-extrabold">
        <tr>
          <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900 truncate">
            <div class="flex justify-between">
              <span>Project No</span>
            </div>
          </td>
          <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
            <div class="flex justify-between">
              <span>Project Name</span>
            </div>
          </td>
          <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
            <div class="flex justify-between">
              <span>Publicatiion Date</span>
            </div>
          </td>
          <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
            <div class="flex justify-between">
              <span>Offer Submission Due Date</span>
            </div>
          </td>
          <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
            <div class="flex justify-between">
              <span>Intent To Join</span>
            </div>
          </td>
          <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
            <div class="flex justify-between">
              <span>Submitted Proposal</span>
            </div>
          </td>
          <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
            <div class="flex justify-between">
              <span>Status</span>
            </div>
          </td>
        </tr>
      </thead>
      <tbody class="text-xs">
        @forelse ($biddings as $bidding)
          <tr class="bg-white border-b font-extrabold hover:bg-neutral-200 hover:shadow-xl transition duration-300">
            <th scope="row" class="px-6 py-2 font-medium tei-text-secondary whitespace-nowrap ">
              {{ $bidding->project_id }}
            </th>
            <td class="px-6 py-2">
              {{ $bidding->title }}
            </td>
            <td class="px-6 py-2">
              {{ date('F j,Y h:i A', strtotime($bidding->start_date)) }}
            </td>
            <td class="px-6 py-2">
              {{ $bidding->extend_date ? date('F j,Y h:i A', strtotime($bidding->extend_date)) : date('F j,Y h:i A', strtotime($bidding->deadline_date)) }}
            </td>
            <td class="px-6 py-2">
              @php
                $confirm = $bidding->vendors->where('pivot.vendor_id', Auth::user()->id)->first();
              @endphp
              <span class="{{ $confirm && $confirm->pivot->confirm ? 'text-green-500' : 'text-red-500' }}">
                {{ $confirm && $confirm->pivot->confirm ? 'Yes' : 'No' }}
              </span>
            </td>
            <td class="px-6 py-2">
              @php
                $submit = $vendor->vendorStatus->where('bidding_id', $bidding->id)->first();
              @endphp
              <span class="{{ $submit && $submit->complete ? 'text-green-500' : 'text-red-500' }}">
                {{ $submit && $submit->complete ? 'Yes' : 'No' }}
              </span>
            </td>
            <td class="px-6 py-2">
              @php
                $status = $bidding->vendors->where('pivot.vendor_id', Auth::user()->id)->first();
              @endphp
              <span class="text-black">
                {{ $status ? $status->pivot->status : '' }}
              </span>
            </td>
          </tr>
        @empty
          <tr class="bg-white border-b">
            <th scope="row" colspan="100" class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap text-center">
              <span class="font-black text-lg tei-text-primary">No bid.</span>
            </th>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class=" overflow-x-auto">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 boder-collapse shadow-lg">
      <caption class="py-5 text-lg font-semibold text-left rtl:text-right tei-text-secondary bg-white ">
        Bid Bulletins
        {{-- <p class="mt-1 text-sm font-normal text-gray-500 ">Overview of latest successful bidders who have secured
          contracts or won the bid.
        </p> --}}
      </caption>
      <thead class="text-xs tei-text-primary uppercase tei-bg-light font-extrabold">
        <tr>
          <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900 truncate">
            <div class="flex justify-between">
              <span>Date</span>
            </div>
          </td>
          <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
            <div class="flex justify-between">
              <span>project No.</span>
            </div>
          </td>
          <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
            <div class="flex justify-between">
              <span>project Name</span>
            </div>
          </td>
          <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
            <div class="flex justify-between">
              <span>Bid Bulletin No.</span>
            </div>
          </td>
          <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
            <div class="flex justify-between">
              <span>Bid Bulletin Title</span>
            </div>
          </td>
          <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
            <div class="flex justify-between">
              <span>Bid Bulletin Details</span>
            </div>
          </td>
        </tr>
      </thead>
      <tbody class="text-xs">
        @forelse ($bulletins as $bulletin)
          <tr class="bg-white border-b font-extrabold hover:bg-neutral-200 hover:shadow-xl transition duration-300">
            <th scope="row" class="px-6 py-2 ">
              {{ date('F j,Y h:i A', strtotime($bulletin->created_at)) }}
            </th>
            <td class="px-6 py-2 font-medium tei-text-secondary whitespace-nowrap ">
              {{ $bulletin->bid->project_id }}
            </td>
            <td class="px-6 py-2">
              {{ $bulletin->bid->title }}
            </td>
            <td class="px-6 py-2">
              {{ $bulletin->id }}
            </td>
            <td>
              {{ $bulletin->title }}
            </td>
            <td class="w-96">
              @if ($showFullText[$bulletin->id])
                <div>
                  {!! nl2br(e($bulletin->description)) !!}
                </div>
                <div>
                  @if (strlen($bulletin->description) > 100)
                    <button class="text-xs uppercase font-extrabold tei-text-secondary"
                      wire:click="toggleText({{ $bulletin->id }})">Read
                      Less</button>
                  @endif
                </div>
              @else
                <div>
                  {!! nl2br(e(Str::limit($bulletin->description, 100))) !!}
                </div>
                <div>
                  @if (strlen($bulletin->description) > 100)
                    <button class="text-xs uppercase font-extrabold tei-text-secondary"
                      wire:click="toggleText({{ $bulletin->id }})">Read
                      More</button>
                  @endif
                </div>
              @endif
            </td>

          </tr>
        @empty
          <tr class="bg-white border-b">
            <th scope="row" colspan="100" class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap text-center">
              <span class="font-black text-lg tei-text-primary">No bid.</span>
            </th>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>
