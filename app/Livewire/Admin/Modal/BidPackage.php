<?php

namespace App\Livewire\Admin\Modal;

use File;
use ZipArchive;
use Livewire\Component;
use App\Exports\FinalReport;
use App\Models\ProjectBidding;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\FinancialReport;
use App\Exports\TechnicalReport;
use App\Exports\EligibilityReport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Reports\AllResultsReport;
use App\Helpers\Reports\FinancialIndividualReport;
use App\Helpers\Reports\TechnicalIndividualReport;
use App\Helpers\Reports\EligibilityIndividualReport;

class BidPackage extends Component
{
  public $listeners = ['bidPackageModal'];
  public $project, $vendorResults, $envelopes;

  public function bidPackageModal($projectId, $vendorResults)
  {
    $this->project = ProjectBidding::findOrFail($projectId);
    $this->vendorResults = $vendorResults;
    $allEnvelopes = [
      'eligibility' => (bool) $this->project->eligibility,
      'technical' => (bool) $this->project->technical,
      'financial' => (bool) $this->project->financial,
    ];

    $envelopes = array_filter($allEnvelopes, function ($value) {
      return $value === true;
    });

    foreach ($envelopes as $envelope => $value) {
      $weight = $this->project->weights->where('envelope', $envelope)->first();
      $envelopes[$envelope] = $weight ? $weight->weight : null;
    }
    $this->envelopes = $envelopes;
    $this->dispatch('openPackageModal');
  }
  public function downloadZip()
  {

    $zipFileName = $this->project->project_id . '_bid_package.zip';
    $zipDirectory = storage_path('app/public/bid-package/' . $this->project->project_id . '/');

    // // Check if the zip file already exists
    // if (file_exists($zipDirectory . $zipFileName)) {
    //   return response()->download($zipDirectory . $zipFileName);
    // }

    // Create the directory if it doesn't exist
    if (!is_dir($zipDirectory)) {
      File::makeDirectory($zipDirectory, 0755, true);
    }
    $zip = new ZipArchive();
    if ($zip->open($zipDirectory . $zipFileName, ZipArchive::CREATE) === TRUE) {

      $remarks = null;
      $data = [
        'vendors' => $this->vendorResults,
        'projectbid' => $this->project,
        'remarks' => $remarks ? $remarks->remarks : null,
      ];

      // Creeate directory for every vendor
      foreach ($data['vendors'] as $vendor) {
        // $vendorDir = strtolower(str_replace(' ', '_', $vendor['name']));
        $vendorDir = preg_replace('/[^a-zA-Z0-9_-]+/', '', strtolower($vendor['name']));

        $fullVendorDir = $zipDirectory . $vendorDir;
        if (!is_dir($fullVendorDir)) {
          File::makeDirectory($fullVendorDir, 0755, true);
          chmod($fullVendorDir, 0755);
        }
      }

      // Generate eligibility report
      if ($this->project->eligibility) {

        $fileEligibilityDir = storage_path('app/public/vendor-file/eligibility/');
        foreach ($data['vendors'] as $vendor) {
          $vendorEligibilityDir = preg_replace('/[^a-zA-Z0-9_-]+/', '', strtolower($vendor['name']));
          $eligibilityData['vendors'][] = $vendor['eligibility'];
          foreach ($vendor['eligibility']['data'] as $eligibility) {
            if ($eligibility['files']) {
              foreach ($eligibility['files'] as $file) {
                if (file_exists($fileEligibilityDir . $file)) {
                  // dd($fileDir . $file);
                  copy($fileEligibilityDir . $file, $zipDirectory . $vendorEligibilityDir . '/' . $file);
                  $zip->addFile($zipDirectory . $vendorEligibilityDir . '/' . $file, $vendorEligibilityDir . '/' . $file);
                }
              }
            }
          }
        }
        $eligibilityData['projectbid'] = $this->project;
        $eligibilityData['remarks'] = $remarks ? $remarks->remarks : null;
        $eligibilityExcelFileName = $this->project->project_id . '-eligibility.xlsx';
        $eligibilityPdfFileName = $this->project->project_id . '-eligibility.pdf';
        $eligibilityExcelPath = $zipDirectory . $eligibilityExcelFileName;
        $eligibilityPdfPath = $zipDirectory . $eligibilityPdfFileName;
        if (!file_exists($eligibilityExcelPath)) {
          Excel::store(new EligibilityReport($eligibilityData), 'bid-package/' . $this->project->project_id . '/' . $eligibilityExcelFileName, 'public');
        }
        if (!file_exists($eligibilityPdfPath)) {
          $pdf = Pdf::loadView('reports.newPdf.eligibility-report', ['data' => $eligibilityData['vendors'], 'bid' => $eligibilityData['projectbid']])->setPaper('a4', 'landscape')->setWarnings(false);
          Storage::disk('public')->put('bid-package/' . $this->project->project_id . '/' . $eligibilityPdfFileName, $pdf->output());
        }
        $zip->addFile($eligibilityExcelPath, $eligibilityExcelFileName);
        $zip->addFile($eligibilityPdfPath, $eligibilityPdfFileName);
      }

      // Generate technical report
      if ($this->project->technical) {
        $fileTechnicalDir = storage_path('app/public/vendor-file/technical/');
        foreach ($data['vendors'] as $vendor) {
          $vendorTechnicalDir = preg_replace('/[^a-zA-Z0-9_-]+/', '', strtolower($vendor['name']));
          $technicalData['vendors'][] = $vendor['technical'];
          foreach ($vendor['technical']['data'] as $technical) {
            if ($technical['files']) {
              foreach ($technical['files'] as $file) {
                if (file_exists($fileTechnicalDir . $file)) {
                  copy($fileTechnicalDir . $file, $zipDirectory . $vendorTechnicalDir . '/' . $file);
                  $zip->addFile($zipDirectory . $vendorTechnicalDir . '/' . $file, $vendorTechnicalDir . '/' . $file);
                }
              }
            }
          }
        }
        $technicalData['projectbid'] = $this->project;
        $technicalData['remarks'] = $remarks ? $remarks->remarks : null;
        $technicalDataExcelFileName = $this->project->project_id . '-technical.xlsx';
        $technicalDataPdfFileName = $this->project->project_id . '-technical.pdf';
        $technicalExcelDataPath = $zipDirectory . $technicalDataExcelFileName;
        $technicalPdfDataPath = $zipDirectory . $technicalDataPdfFileName;
        if (!file_exists($technicalExcelDataPath)) {
          Excel::store(new TechnicalReport($technicalData), 'bid-package/' . $this->project->project_id . '/' . $technicalDataExcelFileName, 'public');
        }
        if (!file_exists($technicalPdfDataPath)) {
          $pdf = Pdf::loadView('reports.newPdf.technical-report', ['data' => $technicalData['vendors'], 'bid' => $technicalData['projectbid']])->setPaper('a4', 'landscape')->setWarnings(false);
          Storage::disk('public')->put('bid-package/' . $this->project->project_id . '/' . $technicalDataPdfFileName, $pdf->output());
        }
        $zip->addFile($technicalExcelDataPath, $technicalDataExcelFileName);
        $zip->addFile($technicalPdfDataPath, $technicalDataPdfFileName);
      }

      // Generate financial report
      if ($this->project->financial) {
        $fileFinancialDir = storage_path('app/public/vendor-file/financial/');
        foreach ($data['vendors'] as $vendor) {
          $vendorFinancialDir = preg_replace('/[^a-zA-Z0-9_-]+/', '', strtolower($vendor['name']));
          $financialData['vendors'][] = $vendor['financial'];
          if ($vendor['financial']['files']) {
            foreach ($vendor['financial']['files'] as $file) {
              if (file_exists($fileFinancialDir . $file)) {
                copy($fileFinancialDir . $file, $zipDirectory . $vendorFinancialDir . '/' . $file);
                $zip->addFile($zipDirectory . $vendorFinancialDir . '/' . $file, $vendorFinancialDir . '/' . $file);
              }
            }
          }
        }
        $financialData['projectbid'] = $this->project;
        $financialData['remarks'] = $remarks ? $remarks->remarks : null;
        $financialDataExcelFileName = $this->project->project_id . '-financial.xlsx';
        $financialDataPdfFileName = $this->project->project_id . '-financial.pdf';
        $financialDataExcelPath = $zipDirectory . $financialDataExcelFileName;
        $financialDataPdfPath = $zipDirectory . $financialDataPdfFileName;
        if (!file_exists($financialDataExcelPath)) {
          Excel::store(new FinancialReport($financialData), 'bid-package/' . $this->project->project_id . '/' . $financialDataExcelFileName, 'public');
        }
        if (!file_exists($financialDataPdfPath)) {
          $pdf = Pdf::loadView('reports.newPdf.financial-report', ['data' => $financialData['vendors'], 'bid' => $financialData['projectbid']])->setPaper('a4', 'landscape')->setWarnings(false);
          Storage::disk('public')->put('bid-package/' . $this->project->project_id . '/' . $financialDataPdfFileName, $pdf->output());
        }
        $zip->addFile($financialDataExcelPath, $financialDataExcelFileName);
        $zip->addFile($financialDataPdfPath, $financialDataPdfFileName);
      }

      // Generate Final report
      $excelFileName = $this->project->project_id . '-final.xlsx';
      $excelPath = $zipDirectory . $excelFileName;
      $pdfFileName = $this->project->project_id . '-final.pdf';
      $pdfPath = $zipDirectory . $pdfFileName;


      Excel::store(new FinalReport($data), 'bid-package/' . $this->project->project_id . '/' . $excelFileName, 'public');

      $pdf = Pdf::loadView('reports.newPdf.final-report', ['data' => $data['vendors'], 'bid' => $data['projectbid']])->setPaper('a4', 'landscape')->setWarnings(false);
      Storage::disk('public')->put('bid-package/' . $this->project->project_id . '/' . $pdfFileName, $pdf->output());

      $zip->addFile($excelPath, $excelFileName);
      $zip->addFile($pdfPath, $pdfFileName);

      $zip->close();
      $this->dispatch('closePackageModal');
      return response()->download($zipDirectory . $zipFileName)->deleteFileAfterSend(true);
    } else {
      $this->dispatch('closePackageModal');
      return response()->json(['error' => 'Failed to create zip file.'], 500);
    }
  }

  public function closePackageModal()
  {
    $this->dispatch('closePackageModal');
  }
  public function render()
  {
    return view('livewire.admin.modal.bid-package');
  }
}
