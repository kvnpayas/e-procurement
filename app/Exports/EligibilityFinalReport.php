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

class EligibilityFinalReport implements FromView, WithEvents, WithTitle
{
  protected $data;

  public function __construct($data)
  {
    $this->data = $data;
    // dd($this->data);
  }

  public function view(): View
  {
    return view('reports.newExcel.multiSheets.eligibility-report', [
      'data' => $this->data,
    ]);
  }

  public function title(): string
  {
    return 'ELIGIBILITY EVALUATION';
  }

  public function registerEvents(): array
  {
    return [
      AfterSheet::class => function (AfterSheet $event) {
        $sheet = $event->sheet->getDelegate();

        // Header
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

        $column = 'K';
        foreach ($this->data['vendors'] as $vendor) {
          // $sheet->getColumnDimension($column)->setWidth(30);
          $nxtColumn = $this->getNextColumn($column, 2);
          $sheet->getStyle("{$column}6:{$nxtColumn}6")->applyFromArray([
            'borders' => [
              'allBorders' => [
                'borderStyle' => Border::BORDER_MEDIUM,
              ],
            ],
            'fill' => [
              'fillType' => Fill::FILL_SOLID,
              'startColor' => [
                'argb' => 'D9D9D9',
              ],
            ],
          ]);
          $column = $this->getNextColumn($nxtColumn, 1);
        }
        $sheet->getStyle('A6:J6')->applyFromArray([
          'borders' => [
            'bottom' => [
              'borderStyle' => Border::BORDER_MEDIUM,
            ],
          ],
        ]);
        // $lastColumn = chr(ord($nxtColumn) - 1);
        $sheet->getRowDimension(6)->setRowHeight(40);
        $sheet->getStyle("A6:{$nxtColumn}6")->applyFromArray([
          'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
            'wrapText' => true,
          ],
          'font' => [
            'bold' => true,
          ],
        ]);
        $itemCounts = $this->data['projectbid']->eligibilities->count();
        $row = $itemCounts + 6;
        $sheet->getStyle('A7:' . $nxtColumn . $row)->applyFromArray([
          'font' => [
            'size' => 10,
          ],
          'alignment' => [
            'wrapText' => true,
            'horizontal' => Alignment::HORIZONTAL_LEFT,
            'vertical' => Alignment::VERTICAL_TOP
          ],
          'borders' => [
            'allBorders' => [
              'borderStyle' => Border::BORDER_THIN,
            ],
          ],
        ]);

        // Open Info
        $openRow = $row + 3;
        $sheet->getStyle("A{$openRow}:B" . $openRow + 1)->applyFromArray([
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
        ]);
        $sheet->getStyle("C{$openRow}:G" . $openRow + 1)->applyFromArray([
          'font' => [
            'size' => 10,
          ],
          'borders' => [
            'allBorders' => [
              'borderStyle' => Border::BORDER_THIN,
              'color' => ['argb' => '808080'],
            ],
          ],
        ]);
      },
    ];
  }

  public function getNextColumn($column, $increment)
  {
    $column = strtoupper($column);
    $length = strlen($column);
    $result = '';

    $carry = $increment;
    for ($i = $length - 1; $i >= 0; $i--) {
      $char = ord($column[$i]) - ord('A') + $carry;
      $carry = intdiv($char, 26);
      $char = $char % 26;
      $result = chr($char + ord('A')) . $result;
    }

    if ($carry > 0) {
      $result = chr($carry - 1 + ord('A')) . $result;
    }

    return $result;
  }

}
