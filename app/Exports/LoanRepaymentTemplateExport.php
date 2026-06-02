<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LoanRepaymentTemplateExport implements
    FromArray,
    WithHeadings,
    WithStyles,
    WithColumnWidths,
    WithTitle
{
    /*
    |--------------------------------------------------------------------------
    | Sheet Title
    |--------------------------------------------------------------------------
    */
    public function title(): string
    {
        return 'Loan Repayments';
    }

    /*
    |--------------------------------------------------------------------------
    | Column Headers — must match import keys exactly
    |--------------------------------------------------------------------------
    */
    public function headings(): array
    {
        return [
            'loan_number',
            'payment_date',
            'amount_paid',
            'payment_method',
            'reference_number',
            'remarks',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Sample Row so user understands expected format
    |--------------------------------------------------------------------------
    */
    public function array(): array
    {
        return [
            [
                'ARB-LOAN-20260529-0001',
                date('Y-m-d'),
                '10800',
                'Cash',
                'RC001',
                'Daily Collection',
            ],
            [
                'ARB-LOAN-20260529-0002',
                date('Y-m-d'),
                '25000',
                'Bank Transfer',
                'RC002',
                'Weekly installment',
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Column Widths
    |--------------------------------------------------------------------------
    */
    public function columnWidths(): array
    {
        return [
            'A' => 30,  // loan_number
            'B' => 18,  // payment_date
            'C' => 18,  // amount_paid
            'D' => 20,  // payment_method
            'E' => 22,  // reference_number
            'F' => 30,  // remarks
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Styles
    |--------------------------------------------------------------------------
    */
    public function styles(Worksheet $sheet): array
    {
        // ✅ Header row — green background, white bold text
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size'  => 11,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2E7D32'], // dark green
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // ✅ Sample data rows — light yellow background
        $sheet->getStyle('A2:F3')->applyFromArray([
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFF9C4'],
            ],
            'font' => [
                'italic' => true,
                'color'  => ['rgb' => '555555'],
            ],
        ]);

        // ✅ Border around all used cells
        $sheet->getStyle('A1:F3')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // ✅ Freeze header row
        $sheet->freezePane('A2');

        // ✅ Add a note above the table
        $sheet->getComment('A1')->getText()->createTextRun(
            'Do not change column headers. Date format: YYYY-MM-DD'
        );

        return [];
    }
}