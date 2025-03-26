<?php

namespace App\Exports;

use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProjectDetails implements FromView, WithEvents, WithTitle
{
  public function __construct(protected $data)
  {
    $this->data = $data;
  }
  public function view(): View
  {
    return view('reports.newExcel.multiSheets.project-details', [
      'project' => $this->data,
      'vendors' => $this->data->vendors,
    ]);
  }

  public function title(): string
  {
    return 'PROJECT BID DETAILS';
  }

  public function registerEvents(): array
  {
    return [
      AfterSheet::class => function (AfterSheet $event) {
        $sheet = $event->sheet->getDelegate();
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
        $headerTitle = [
          'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
              'argb' => 'D9D9D9',
            ],
          ],
          'borders' => [
            'allBorders' => [
              'borderStyle' => Border::BORDER_MEDIUM,
            ],
          ],
          'alignment' => [
            'vertical' => Alignment::VERTICAL_CENTER,
          ],
          'font' => [
            'bold' => true,
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
            ],
          ],
          'font' => [
            'bold' => true,
          ],
        ];
        $detail = [
          'borders' => [
            'allBorders' => [
              'borderStyle' => Border::BORDER_THIN,
            ],
          ],
          'font' => [
            'size' => 10
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
        $sheet->getRowDimension(7)->setRowHeight(35);
        $sheet->getRowDimension(13)->setRowHeight(35);
        $sheet->getRowDimension(17)->setRowHeight(35);

        // Label
        $sheet->getStyle("B8:D11")->applyFromArray($label);
        $sheet->getStyle("K8:M11")->applyFromArray($label);
        $sheet->getStyle("B14:D15")->applyFromArray($label);
        $sheet->getStyle("K14:M15")->applyFromArray($label);
        $sheet->getStyle("B18:D19")->applyFromArray($label);
        $sheet->getStyle("K18:M19")->applyFromArray($label);
        $sheet->getStyle("B20:S20")->applyFromArray($label);

        // Detail
        $sheet->getStyle("E8:J11")->applyFromArray($detail);
        $sheet->getStyle("N8:S11")->applyFromArray($detail);
        $sheet->getStyle("E14:J15")->applyFromArray($detail);
        $sheet->getStyle("N14:S15")->applyFromArray($detail);
        $sheet->getStyle("E18:J19")->applyFromArray($detail);
        $sheet->getStyle("N18:S19")->applyFromArray($detail);
        
        $vendorCount = $this->data->vendors ? $this->data->vendors->count() : 0;
        $count = $vendorCount + 20;
        $sheet->getStyle("B21:".'S'.$count)->applyFromArray($detail);
        

        // Header
        $sheet->getStyle("B7:S7")->applyFromArray($headerTitle);
        $sheet->getStyle("B13:S13")->applyFromArray($headerTitle);
        $sheet->getStyle("B17:S17")->applyFromArray($headerTitle);

        $sheet->getStyle("B7:S11")->applyFromArray([
          'borders' => [
            'outline' => [
              'borderStyle' => Border::BORDER_MEDIUM,
            ],
          ],
        ]);
        $sheet->getStyle("B13:S15")->applyFromArray([
          'borders' => [
            'outline' => [
              'borderStyle' => Border::BORDER_MEDIUM,
            ],
          ],
        ]);

        $sheet->getStyle("B17:S".$count)->applyFromArray([
          'borders' => [
            'outline' => [
              'borderStyle' => Border::BORDER_MEDIUM,
            ],
          ],
        ]);
      },
    ];
  }
}
