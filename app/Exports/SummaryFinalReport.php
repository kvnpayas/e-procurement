<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SummaryFinalReport implements FromView, WithEvents, WithTitle
{
  protected $data;

  public function __construct($data)
  {
    $this->data = $data;
    // dd($this->data);
  }

  public function view(): View
  {
    return view('reports.newExcel.multiSheets.summary-report', [
      'data' => $this->data,
    ]);
  }

  public function title(): string
  {
    return 'BID TABULATION SUMMARY';
  }

  public function registerEvents(): array
  {
    return [
      AfterSheet::class => function (AfterSheet $event) {
        $sheet = $event->sheet->getDelegate();
        $column = 'H';

        $allEnvelopes = [
          'eligibility' => (bool) $this->data['projectbid']->eligibility,
          'technical' => (bool) $this->data['projectbid']->technical,
          'financial' => (bool) $this->data['projectbid']->financial,
        ];
        $envelopes = array_filter($allEnvelopes, function ($value) {
          return $value === true;
        });
        $headerTitle = [
          'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
            'wrapText' => true,
          ],
          'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
              'argb' => 'D9D9D9',
            ],
          ],
          'borders' => [
            'allBorders' => [
              'borderStyle' => Border::BORDER_MEDIUM,
              'color' => ['argb' => '808080'],
            ],
          ],
          'font' => [
            'bold' => true,
          ],
        ];
        $vendor = [
          'font' => [
            'size' => 10,
          ],
          'alignment' => [
            'wrapText' => true,
          ],
          'borders' => [
            'allBorders' => [
              'borderStyle' => Border::BORDER_THIN,
              'color' => ['argb' => '808080'],
            ],
          ],
        ];
        $label = [
          'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
              'argb' => 'D9D9D9',
            ],
          ],
          'borders' => [
            'allBorders' => [
              'borderStyle' => Border::BORDER_THIN,
              'color' => ['argb' => '808080'],
            ],
          ],
          'font' => [
            'bold' => true,
          ],
        ];
        $detail = [
          'font' => [
            'size' => 10,
          ],
          'borders' => [
            'allBorders' => [
              'borderStyle' => Border::BORDER_THIN,
              'color' => ['argb' => '808080'],
            ],
          ],
        ];
        $bgWhite = [
          'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
              'argb' => 'ffffff',
            ],
          ],
        ];
        $bgGray = [
          'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
              'argb' => 'D9D9D9',
            ],
          ],
        ];
        $sheet->getStyle("A1:C3")->applyFromArray($bgWhite);
        $sheet->getStyle("R1:T3")->applyFromArray($bgWhite);
        $sheet->getStyle("D1:Q3")->applyFromArray([
          $bgWhite,
          'font' => [
            'size' => 24,
            'bold' => true,
          ],
        ]);
        $sheet->getStyle("A4:Q4")->applyFromArray($bgGray);
        $sheet->getRowDimension(6)->setRowHeight(35);
        foreach ($envelopes as $envelope) {
          // $sheet->getColumnDimension($column)->setWidth(30);
          $lastColumn = chr(ord($column) + 2);
          $sheet->getStyle("{$column}6:{$lastColumn}6")->applyFromArray($headerTitle);
          $column = chr(ord($column) + 3);
        }
        // $envelopeHeader = chr(ord($column) - 1);
        // $sheet->getColumnDimension('A')->setWidth(10);
        // $sheet->getColumnDimension('B')->setWidth(50);
        $sheet->getStyle("A6:G6")->applyFromArray($headerTitle);
        // $sheet->getStyle('A4:' . ($envelopeHeader) . '4')->applyFromArray([
  
        // ]);
        if ($this->data['projectbid']->score_method == "Rating") {
          $sheet->getStyle(($column) . '6')->applyFromArray($headerTitle);
        }

        $vendorCount = $this->data['vendors'] ? count($this->data['vendors']) : 0;
        $count = $vendorCount + 6;
        $lastColumnVendor = $this->data['projectbid']->score_method == "Rating" ? $column : $column = chr(ord($column) - 1);
        $sheet->getStyle("A7:" . $lastColumnVendor . $count)->applyFromArray($vendor);

        $nxtRow = $count + 3;
        // Score Method
        $sheet->getStyle("A{$nxtRow}:B{$nxtRow}")->applyFromArray($label);
        $sheet->getStyle("C{$nxtRow}:G{$nxtRow}")->applyFromArray($detail);

        // Winning Bidder
        $winRow = $nxtRow + 1;
        if ($this->data['projectbid']->status != 'Under Evaluation') {
          $sheet->getStyle("A{$winRow}:B{$winRow}")->applyFromArray($label);
          $sheet->getStyle("C{$winRow}:G{$winRow}")->applyFromArray($detail);
        }


        // Approver
        $approverRow = $winRow;
        if ($this->data['projectbid']->winnerApproval && $this->data['projectbid']->winnerApproval->approverUser) {
          $approverRow = $winRow + 1;
          $sheet->getStyle("A{$approverRow}:B{$approverRow}")->applyFromArray($label);
          $sheet->getStyle("C{$approverRow}:G{$approverRow}")->applyFromArray($detail);
        }

        // Final Approver
        $approverFinalRow = $approverRow;
        if ($this->data['projectbid']->winnerApproval && $this->data['projectbid']->winnerApproval->finalApproverUser) {
          $approverFinalRow = $approverRow + 1;
          $sheet->getStyle("A{$approverFinalRow}:B{$approverFinalRow}")->applyFromArray($label);
          $sheet->getStyle("C{$approverFinalRow}:G{$approverFinalRow}")->applyFromArray($detail);
        }

        // Awarded
        if ($this->data['projectbid']->status == 'Awarded') {
          $awarededRow = $approverFinalRow + 1;
          $awarededNxtRow = $awarededRow + 1;
          $sheet->getStyle("A{$awarededRow}:B{$awarededNxtRow}")->applyFromArray($label);
          $sheet->getStyle("C{$awarededRow}:G{$awarededNxtRow}")->applyFromArray($detail);
        }
        // foreach ($this->data['vendors'] as $vendors) {
        //   $sheet->getStyle('A' . $row)->applyFromArray([
        //     'alignment' => [
        //       'horizontal' => Alignment::HORIZONTAL_CENTER,
        //     ],
        //   ]);
        //   $sheet->getStyle('A' . $row . ':' . $column . $row)->applyFromArray([
        //     'font' => [
        //       'size' => 10,
        //     ],
        //     'alignment' => [
        //       'wrapText' => true,
        //     ],
        //   ]);
        //   $row++;
        // }
      },
    ];
  }

}
