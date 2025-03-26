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

class TechnicalFinalReport implements FromView, WithEvents, WithTitle
{
  protected $data;

  public function __construct($data)
  {
    $this->data = $data;
    // dd($this->data);
  }

  public function view(): View
  {
    return view('reports.newExcel.multiSheets.technical-report', [
      'data' => $this->data,
    ]);
  }

  public function title(): string
  {
    return 'TECHNICAL EVALUATION';
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

        $column = 'H';
        foreach ($this->data['vendors'] as $vendor) {
          $nextColumn = $this->getNextColumn($column, 3);
          $sheet->getStyle("{$column}6:{$nextColumn}6")->applyFromArray([
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

          $column = $this->getNextColumn($nextColumn, 1);
        }
        $sheet->getStyle('A6:G6')->applyFromArray([
          'borders' => [
            'bottom' => [
              'borderStyle' => Border::BORDER_MEDIUM,
            ],
          ],
        ]);
        $sheet->getRowDimension(6)->setRowHeight(40);
        $sheet->getStyle("A6:{$nextColumn}6")->applyFromArray([
          'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
            'wrapText' => true,
          ],
          'font' => [
            'bold' => true,
          ],
        ]);
        $sheet->getStyle('H7:' . $nextColumn . '7')->applyFromArray([
          'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
              'argb' => 'D9D9D9',
            ],
          ],
          'font' => [
            'bold' => true,
          ],
        ]);
        $itemCounts = $this->data['projectbid']->technicals->count();
        $row = $itemCounts + 7;
        $sheet->getStyle('A7:' . $nextColumn . $row)->applyFromArray([
          'font' => [
            'size' => 10,
          ],
          'alignment' => [
            'wrapText' => true,
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
