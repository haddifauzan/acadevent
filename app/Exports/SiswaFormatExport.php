<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SiswaFormatExport implements FromCollection, WithHeadings, WithCustomStartCell, WithStyles
{
    // Tidak mengambil data dari database, hanya memberikan format kosong
    public function collection()
    {
        // Mengembalikan koleksi kosong
        return collect([]);
    }

    public function headings(): array
    {
        return [
            'NIS',           // Kolom 1
            'Nama Siswa',    // Kolom 2
            'Kelas',         // Kolom 3
            'Jurusan',       // Kolom 4
            'Email',         // Kolom 5
            'No HP',         // Kolom 6
        ];
    }

    // Memulai dari cell A2
    public function startCell(): string
    {
        return 'A1';
    }

    // Memberikan style untuk ketentuan dan kolom header
    public function styles(Worksheet $sheet)
    {
        // Styling for the headers (A2:F2)
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        // Setting column width to auto for better display
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Center align the headers
        $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal('center');
    }
}
