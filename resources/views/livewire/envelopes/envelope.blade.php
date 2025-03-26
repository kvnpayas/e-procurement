<div>
  <ul class="hidden text-sm font-medium text-center text-gray-500 rounded-lg shadow sm:flex ">
     @foreach ($envelopes as $envelope=> $value)
    <li class="w-full focus-within:z-10">
      @php
        $originalRouteName = session()->get('originalRouteName');
      @endphp
      <a href="{{ route('bid-lists.' . $envelope . '-envelope', $project->id) }}"
        class="transition duration-300 inline-block w-full p-4  border-r border-gray-200  {{ $originalRouteName == 'bid-lists.' . $envelope . '-envelope' ? 'shadow-xl tei-bg-primary tei-text-secondary' : 'hover:shadow-xl tei-bg-light' }} "
        aria-current="page"><span class="font-black">{{ ucfirst($envelope) }}</span></a>
    </li>
    @endforeach
    <li class="w-full focus-within:z-10">
      <a href="{{ route('bid-lists.summary-and-submission', $project->id) }}"
        class="transition duration-300 inline-block w-full p-4  border-r border-gray-200  bg-green-500 hover:shadow-xl text-white "
        aria-current="page"><span class="font-black">Summary And Submission</span></a>
    </li>
    </ul>
    <div class="border-b-2 flex">
      <div class="flex">
        <div class="p-5 ">
          <div>
            <label for="title" class=" mr-2 text-lg font-extrabold tei-text-primary ">Project No:</label>
            <span class="text-xs font-extrabold tei-text-secondary">{{ $project->project_id }}</span>
          </div>
          <div>
            <label for="title" class=" mr-2 text-lg font-extrabold tei-text-primary ">Project Title:</label>
            <span class="text-xs font-extrabold tei-text-accent">{{ $project->title }}</span>
          </div>
          <div>
            <label for="title" class=" mr-2 text-lg font-extrabold tei-text-primary ">Deadline Date:</label>
            <span
              class="text-xs font-extrabold tei-text-accent">{{ $project->extend_date ? date('F j,Y @ h:i A', strtotime($project->extend_date)) : date('F j,Y @ h:i A', strtotime($project->deadline_date)) }}</span>
          </div>

        </div>
        <div class="p-5 ">
          <div>
            <label for="title" class=" mr-2 text-lg font-extrabold tei-text-primary ">Score Method:</label>
            <span class="text-xs font-extrabold tei-text-accent">
              {{ $project->score_method == 'Cost' ? 'Best Financial Offer' : 'Rating' }}
            </span>
          </div>
          <div>
            <label for="title" class=" mr-2 text-lg font-extrabold tei-text-primary ">Sales:</label>
            <span class="text-xs font-extrabold tei-text-accent">{{ $project->scrap ? 'Yes' : 'No' }}</span>
          </div>
          <div>
            <label for="title" class=" mr-2 text-lg font-extrabold tei-text-primary ">Project Status:</label>
            <span
              class="text-xs uppercase font-extrabold {{ $vendorStatus->complete ? 'text-green-600' : 'text-red-500' }}">
              {{ $vendorStatus->complete ? 'Submitted' : 'Not Submitted' }}
            </span>
          </div>
        </div>
      </div>
      <div class=" w-1/4 ml-auto p-5">

        <span class="block tei-text-secondary font-extrabold">Important Reminder</span>
        <span
          class="text-xs tei-text-accent">{{ $vendorStatus->complete ? 'You have already submitted your bid. If you make any changes to the requirements envelope, please click "Submit Bid" button again.' : 'Please make sure to click "Submit Bid" button to finalize your bid submission.' }}</span>
      </div>
    </div>
  </div>
