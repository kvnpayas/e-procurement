<div>
  <div class="grid grid-cols-1 sm:grid-cols-5 md:grid-cols-5 lg:grid-cols-5 justify-center content-center gap-4">
    <div
      class="text-center tei-bg-light shadow-xl rounded-md flex flex-col gap-5 p-4 cursor-default hover:scale-110 transition-transform duration-300">
      <span class="py-5 text-7xl tei-text-secondary font-extrabold">{{ $biddings->count() }}</span>
      <label class="text-xs uppercase font-semibold tei-text-accent">Number of Project Bids</label>
    </div>
    <div
      class="text-center tei-bg-light shadow-xl rounded-md flex flex-col gap-5 p-4 cursor-default hover:scale-110 transition-transform duration-300">
      @php
        $numBidsforEval = $biddings->whereIn('status', ['For Evaluation', 'Under Evaluation'])->count();
      @endphp
      <span class="py-5 text-7xl tei-text-secondary font-extrabold">{{ $numBidsforEval }}</span>
      <label class="text-xs uppercase font-semibold tei-text-accent">Project Bids for evaluation</label>
    </div>
    <div
      class="text-center tei-bg-light shadow-xl rounded-md flex flex-col gap-5 p-4 cursor-default hover:scale-110 transition-transform duration-300">
      @php
        $numBidsforApproval = $biddings->where('status', 'Approved')->count();
      @endphp
      <span class="py-5 text-7xl text-green-500 font-extrabold">{{ $bidApprovalCount }}</span>
      <label class="text-xs uppercase font-semibold tei-text-accent">Project Bids for approval</label>
    </div>
    <div
      class="text-center tei-bg-light shadow-xl rounded-md flex flex-col gap-5 p-4 cursor-default hover:scale-110 transition-transform duration-300">
      @php
        $numBidsforApproval = $biddings->where('status', 'Approved')->count();
      @endphp
      <span class="py-5 text-7xl text-red-500 font-extrabold">{{ $bidRejectedCount }}</span>
      <label class="text-xs uppercase font-semibold tei-text-accent">Project Bids rejected</label>
    </div>
    <div
      class="text-center tei-bg-light shadow-xl rounded-md flex flex-col gap-5 p-4 cursor-default hover:scale-110 transition-transform duration-300">
      <span class="py-5 text-7xl text-red-500 font-extrabold">{{ $biddingProtests->count() }}</span>
      <label class="text-xs uppercase font-semibold tei-text-accent">Project Bids on protest</label>
    </div>

  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-10">
    <div class=" overflow-x-auto">
      <table class="w-full text-sm text-left rtl:text-right text-gray-500 boder-collapse shadow-lg">
        <caption class="py-5 text-lg font-semibold text-left rtl:text-right tei-text-secondary bg-white ">
          Latest winning bidder
          <p class="mt-1 text-sm font-normal text-gray-500 ">Overview of latest successful bidders who have secured
            contracts or won the bid.
          </p>
        </caption>
        <thead class="text-xs tei-text-primary uppercase tei-bg-light font-extrabold">
          <tr>
            <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900 truncate">
              <div class="flex justify-between">
                <span>Vendor Id</span>
              </div>
            </td>
            <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
              <div class="flex justify-between">
                <span>Name</span>
              </div>
            </td>
            <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
              <div class="flex justify-between">
                <span>Rank</span>
              </div>
            </td>
            <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
              <div class="flex justify-between">
                <span>Bid Name</span>
              </div>
            </td>
          </tr>
        </thead>
        <tbody>
          @forelse ($approvedBid as $bid)
            <tr class="text-xs bg-white border-b font-extrabold hover:bg-neutral-200 hover:shadow-xl transition duration-300">
              <th scope="row" class="px-4 py-2 font-medium tei-text-secondary whitespace-nowrap ">
                {{ $bid->winnerApproval ? $bid->winnerApproval->winner_id : '' }}
              </th>
              <td class="px-4 py-2">
                {{ $bid->winnerApproval ? $bid->winnerApproval->winnerVendor->name : '' }}
              </td>
              <td class="px-4 py-2">
                @php
                  $bidResult = $bid->finalResult->where('vendor_id', $bid->winnerApproval ? $bid->winnerApproval->winner_id : '')->first();
                  $rank = $bidResult ? $bidResult->rank : null;
                @endphp
                {{ $rank }}
              </td>
              <td class="px-4 py-2">
                {{ $bid->title }}
              </td>

            </tr>
          @empty
            <tr class="bg-white border-b">
              <th scope="row" colspan="100"
                class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap text-center">
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
          Sales bid
          <p class="mt-1 text-sm font-normal text-gray-500 ">Overview of latest sales bid.
          </p>
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
                <span>title</span>
              </div>
            </td>
            <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
              <div class="flex justify-between">
                <span>status</span>
              </div>
            </td>
            <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
              <div class="flex justify-between">
                <span>Deadline Date</span>
              </div>
            </td>
          </tr>
        </thead>
        <tbody>
          @forelse ($salesBids as $bid)
            <tr class="text-xs bg-white border-b font-extrabold hover:bg-neutral-200 hover:shadow-xl transition duration-300">
              <th scope="row" class="px-4 py-2 font-medium tei-text-secondary whitespace-nowrap ">
                {{ $bid->project_id }}
              </th>
              <td class="px-4 py-2">
                {{ $bid->title }}
              </td>
              <td class="px-4 py-2">
                {{ $bid->status }}
              </td>
              <td class="px-4 py-2">
                {{ $bid->extend_date ? date('F j,Y', strtotime($bid->extend_date)) :  date('F j,Y', strtotime($bid->deadline_date))}}
              </td>

            </tr>
          @empty
            <tr class="bg-white border-b">
              <th scope="row" colspan="100"
                class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap text-center">
                <span class="font-black text-lg tei-text-primary">No bid.</span>
              </th>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
