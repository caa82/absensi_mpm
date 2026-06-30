<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromArray, WithHeadings, WithStyles, WithDrawings, WithCustomStartCell, ShouldAutoSize
{
    protected $data;
    protected $period;

    public function __construct(array $data, $period)
    {
        $this->data = $data;
        $this->period = $period;
    }

    public function startCell(): string
    {
        // Leaves first 6 rows empty for header logo and report metadata
        return 'A7';
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('MPM Logo');
        $drawing->setDescription('MPM Politeknik Astra Logo');
        $drawing->setPath(public_path('images/logo_mpm.png'));
        $drawing->setHeight(80);
        $drawing->setCoordinates('A2'); // Coordinates for drawing placement
        return $drawing;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'No',
            'NIM',
            'Nama Anggota',
            'Jabatan',
            'Jumlah Hadir',
            'Jumlah Izin',
            'Jumlah Sakit',
            'Jumlah Shift 2 Hadir Sebagian',
            'Jumlah Shift 2 Tidak Hadir',
            'Persentase Kehadiran'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Custom headers in top rows
        $sheet->setCellValue('C2', 'SISTEM ABSENSI ANGGOTA MPM POLITEKNIK ASTRA');
        $sheet->setCellValue('C3', 'LAPORAN REKAPITULASI KEHADIRAN ANGGOTA');
        $sheet->setCellValue('C4', 'Periode Laporan: ' . $this->period);

        // Header styles
        $sheet->getStyle('C2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('C3')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('C4')->getFont()->setItalic(true)->setSize(10);

        // Style the table header row (Row 7)
        $sheet->getStyle('A7:J7')->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE));
        $sheet->getStyle('A7:J7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF0C2340'); // MPM Navy

        // Alignments and borders
        $rowCount = count($this->data) + 7;
        $sheet->getStyle('A7:J' . $rowCount)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $sheet->getStyle('A8:A' . $rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B8:B' . $rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $sheet->getStyle('E8:J' . $rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Add percentage sign to final column values
        for ($i = 8; $i <= $rowCount; $i++) {
            $val = $sheet->getCell('J' . $i)->getValue();
            $sheet->getCell('J' . $i)->setValue($val . '%');
        }

        return [];
    }
}
