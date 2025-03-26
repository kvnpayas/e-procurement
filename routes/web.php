<?php

use App\Models\User;
use App\Models\ProjectBidding;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\FileAccessController;

Route::get('/', function () {
  return redirect('login');
});

Route::view('dashboard', 'dashboard')
  ->middleware(['auth', 'verified', 'otp.access', 'initalRoute'])
  ->name('dashboard');

Route::view('profile', 'profile')
  ->middleware(['auth'])
  ->name('profile');

require __DIR__ . '/auth.php';

Route::get('/registration/{token}', function ($token) {
  $user = User::where('token', $token)->first();
  if ($user && $user->role_id == 2) {
    return view('layouts.register', ['user' => $user]);
  } else {
    return view('layouts.error-page', ['code' => 404, 'message' => 'PAGE NOT FOUND']);
  }
})->middleware('guest');

Route::get('/change-password/{token}', function ($token) {
  $user = User::where('token', $token)->first();
  // $user = User::find($user);
  if ($user) {
    return view('layouts.user-password', ['user' => $user]);
  } else {
    return view('layouts.error-page', ['code' => 404, 'message' => 'PAGE NOT FOUND']);
  }
})->name('user-login')->middleware('guest');

Route::get('/reset-password/{token}', function ($token) {
  $user = User::where('token', $token)->first();
  // $user = User::find($user);
  if ($user) {
    return view('layouts.reset-password', ['user' => $user]);
  } else {
    return view('layouts.error-page', ['code' => 404, 'message' => 'PAGE NOT FOUND']);
  }
})->name('user-login')->middleware('guest');

Route::middleware('guest')->group(function () {
  Route::post('register', [VendorController::class, 'registration'])
    ->name('register.vendor');
});

Route::middleware(['auth', 'otp.access'])->group(function () {

  // OTP PAGE
  Route::get('/otp', function () {
    return view('otp-page');
  })->name('otp-page');

  // Admin Sidebar
  Route::middleware(['admin.role', 'access', 'initalRoute'])->group(function () {
    Route::get('/vendor-maintenance', function () {
      return view('admin.vendor-maintenance');
    })->name('vendor-maintenance');

    Route::get('/vendor/{vendorId}', function ($vendorId) {
      return view('admin.vendor-details', compact('vendorId'));
    })->name('vendor-maintenance.vendor-details');

    Route::get('/user-maintenance', function () {
      return view('admin.user-maintenance');
    })->name('user-maintenance');

    Route::get('/access-rights', function () {
      return view('admin.access-rights');
    })->name('access-rights');

    Route::get('/class-maintenance', function () {
      return view('admin.class-maintenance');
    })->name('class-maintenance');

    // Eligibility Maintenance
    Route::get('/eligibility-maintenance', function () {
      return view('envelope-maintenance.eligibility-maintenance');
    })->name('eligibility-envelope');

    Route::get('/eligibility-maintenance/{eligibilityId}', function ($eligibilityId) {
      return view('envelope-maintenance.eligibility-details-maintenance', compact('eligibilityId'));
    })->name('eligibility-envelope.eligibility-details');
    // End Eligibility Maintenance

    // Technical Maintenance
    Route::get('/technical-maintenance', function () {
      return view('envelope-maintenance.technical-maintenance');
    })->name('technical-envelope');
    // End Technical Maintenance

    // Financial Maintenance
    Route::get('/financial-maintenance', function () {
      return view('envelope-maintenance.financial-maintenance');
    })->name('financial-envelope');
    // End Financial Maintenance


    // Scrap Material Maintenance
    Route::get('/scrap-maintenance', function () {
      return view('admin.scrap-maintenance');
    })->name('scrap-maintenance');
    // End Scrap Material Maintenance


    // Project Bidding
    Route::get('/project-bididng', function () {
      return view('admin.bidding.project-bidding');
    })->name('project-bidding');

    Route::get('/project-bididng/create', function () {
      return view('admin.bidding.create-project-bidding');
    })->name('project-bidding.create-project-bidding');

    Route::get('/project-bididng/{biddingId}/edit', function ($biddingId) {
      return view('admin.bidding.edit-project-bidding', compact('biddingId'));
    })->name('project-bidding.edit-project-bidding');

    Route::get('/project-bididng/{biddingId}/view', function ($biddingId) {
      return view('admin.bidding.view-project-bidding', compact('biddingId'));
    })->name('project-bidding.view-project-bidding');

    Route::get('/project-bididng/{biddingId}/bid-bulletin', function ($biddingId) {
      return view('admin.bidding.bid-bulletin', compact('biddingId'));
    })->name('project-bidding.bid-bulletin');

    Route::get('/project-bididng/{biddingId}/bid-results', function ($biddingId) {
      return view('admin.results', compact('biddingId'));
    })->name('project-bidding.bid-results');


    Route::get('/project-bididng/{biddingId}/envelopes', function (ProjectBidding $biddingId) {
      return view('admin.bidding.envelope.envelope', compact('biddingId'));
    })->name('project-bidding.envelopes');

    Route::get('/project-bididng/{biddingId}/eligibility-envelopes', function ($biddingId) {
      return view('admin.bidding.envelope.eligibility-envelope', compact('biddingId'));
    })->name('project-bidding.eligibility-envelope')->middleware('envelope');

    Route::get('/project-bididng/{biddingId}/technical-envelopes', function ($biddingId) {
      return view('admin.bidding.envelope.technical-envelope', compact('biddingId'));
    })->name('project-bidding.technical-envelope')->middleware('envelope');

    Route::get('/project-bididng/{biddingId}/financial-envelopes', function ($biddingId) {
      return view('admin.bidding.envelope.financial-envelope', compact('biddingId'));
    })->name('project-bidding.financial-envelope')->middleware('envelope');

    // Vendor
    Route::get('/project-bididng/{biddingId}/vendor', function ($biddingId) {
      return view('admin.bidding.vendor.bidding-vendor', compact('biddingId'));
    })->name('project-bidding.vendor-lists');

    // Approval
    Route::get('/project-bididng-approval', function () {
      return view('admin.approval.approval');
    })->name('approval');

    // Awarding
    Route::get('/project-bididng-awarding', function () {
      return view('admin.awarding.awarding');
    })->name('awarding');

    // Protest
    Route::get('/project-bididng-protest', function () {
      return view('admin.protest.pending-protest');
    })->name('protest');
    // End Project Bidding

    // Evaluation
    Route::get('/project-bididng/{biddingId}/evaluation', function ($biddingId) {
      return view('admin.evaluation.evaluation', compact('biddingId'));
    })->name('project-bidding.evaluation');

    // Eligibility Report
    Route::get('/project-bididng/{biddingId}/evaluation/eligibility-report', function ($biddingId) {
      $data = session('eligibility_report_data');
      $bid = ProjectBidding::findOrFail($biddingId);
      if (empty($data)) {
        abort(404, 'Error on extracting data.');
      }
      $pdf = Pdf::loadView('reports.newPdf.eligibility-report', ['data' => $data, 'bid' => $bid])->setPaper('a4', 'landscape')->setWarnings(false);
      return $pdf->download($bid->project_id . '-eligibility-' . time() . '.pdf');
    })->name('project-bidding.eligibilityReport');

    // Technical Report
    Route::get('/project-bididng/{biddingId}/evaluation/technical-report', function ($biddingId) {
      $data = session('technical_report_data');
      $bid = ProjectBidding::findOrFail($biddingId);
      if (empty($data)) {
        abort(404, 'Error on extracting data.');
      }
      $pdf = Pdf::loadView('reports.newPdf.technical-report', ['data' => $data, 'bid' => $bid])->setPaper('a4', 'landscape')->setWarnings(false);
      return $pdf->download($bid->project_id . '-technical-' . time() . '.pdf');
    })->name('project-bidding.technicalReport');

    // Financial Report
    Route::get('/project-bididng/{biddingId}/evaluation/financial-report', function ($biddingId) {
      $data = session('financial_report_data');
      $bid = ProjectBidding::findOrFail($biddingId);
      if (empty($data)) {
        abort(404, 'Error on extracting data.');
      }
      $pdf = Pdf::loadView('reports.newPdf.financial-report', ['data' => $data, 'bid' => $bid])->setPaper('a4', 'landscape')->setWarnings(false);
      return $pdf->download($bid->project_id . '-financial-' . time() . '.pdf');
    })->name('project-bidding.financialReport');

    // Final Report
    Route::get('/project-bididng/{biddingId}/evaluation/final-report', function ($biddingId) {
      $data = session('final_report_data');
      $bid = ProjectBidding::findOrFail($biddingId);
      if (empty($data)) {
        abort(404, 'Error on extracting data.');
      }
      $pdf = Pdf::loadView('reports.newPdf.final-report', ['data' => $data, 'bid' => $bid])->setPaper('a4', 'landscape')->setWarnings(false);
      return $pdf->download($bid->project_id . '-final-' . time() . '.pdf');
    })->name('project-bidding.finalReport');
    // End Evaluation

  });
  // Files Access
  Route::get('/file/view/{folder}/{file}', [FileAccessController::class, 'view'])->name('view-file');

  // END Admin Sidebar

  // Vendor Sidebar
  Route::middleware(['vendor.role', 'initalRoute'])->group(function () {
    Route::get('/bid-invitation', function () {
      return view('bidding.bidding-invitation');
    })->name('bid-invitation');

    Route::get('/bid-lists', function () {
      return view('bidding.bidding-lists');
    })->name('bid-lists');

    Route::get('/bid-results', function () {
      return view('bidding.bid-results');
    })->name('bid-results');

    //Envelopes
    Route::get('/bid/{bid}/envelopes', function () {
      return view('bidding.envelope');
    })->name('bid-lists.envelopes');
    // End Envelopes

    // Eligibility Envelopes
    Route::get('/bid/{bid}/eligibility', function ($biddingId) {
      return view('envelopes.eligibility-view', compact('biddingId'));
    })->name('bid-lists.eligibility-envelope')->middleware('vendorEnvelope');
    // End Eligibility Envelopes

    // Technical Envelopes
    Route::get('/bid/{bid}/technical', function ($biddingId) {
      return view('envelopes.technical-view', compact('biddingId'));
    })->name('bid-lists.technical-envelope')->middleware('vendorEnvelope');
    // End Technical Envelopes

    // Financial Envelopes
    Route::get('/bid/{bid}/financial', function ($biddingId) {
      return view('envelopes.financial-view', compact('biddingId'));
    })->name('bid-lists.financial-envelope')->middleware('vendorEnvelope');
    // End Financial Envelopes

    // Summary Envelopes
    Route::get('/bid/{bid}/summary', function ($biddingId) {
      return view('envelopes.summary-view', compact('biddingId'));
    })->name('bid-lists.summary-and-submission')->middleware('vendorEnvelope');
    // End Summary Envelopes

    // Bid Summary Envelopes
    Route::get('/bid/{bid}/bid-summary', function ($biddingId) {
      return view('bidding.bid-summary', compact('biddingId'));
    })->name('bid-lists.summary');
    // End Bid Summary Envelopes

    //Bulletin
    Route::get('/bid/{bid}/bid-bulletin', function ($biddingId) {
      return view('bidding.bid-bulletin', compact('biddingId'));
    })->name('bid-lists.bid-bulletin');
    // End Bulletin

  });
  // End Vendor Sidebar

});