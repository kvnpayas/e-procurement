<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ProjectBidding;
use Maatwebsite\Excel\Facades\Excel;

class PrintReportsModal extends Component
{
  public $model, $data, $bidId;
  protected $listeners = ['openReportModal'];

  public function openReportModal($data, $model, $bidId)
  {
    $this->data = $data;
    $this->model = $model;
    $this->bidId = $bidId;
    $this->dispatch('openReportsModal');
  }

  public function printPdf()
  {
    $route = 'project-bidding.'.$this->model.'Report';
    session([$this->model.'_report_data' => $this->data]);

    $this->dispatch('closeReportsModal');
    return redirect()
    ->route($route, $this->bidId);
  }
  

  public function printExcel()
  {
    $bid = ProjectBidding::findOrFail($this->bidId);
    $modelName = ucfirst($this->model).'Report';
    $class = "App\\Exports\\{$modelName}";
    $remarks =  $bid->envelopeRemarks->where('envelope', $this->model)->first();
    $data = [
      'vendors' => $this->data,
      'projectbid' => $bid,
      'remarks' => $remarks ? $remarks->remarks : null,
    ];
    $this->dispatch('closeReportsModal');
    return Excel::download(new $class($data), $bid->project_id.'-'.$this->model.'-'.time().'.xlsx');
  }
  
  public function closeReportsModal()
  {
    $this->dispatch('closeReportsModal');
  }
    public function render()
    {
        return view('livewire.admin.print-reports-modal');
    }
}
