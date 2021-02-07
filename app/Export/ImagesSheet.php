<?php


namespace App\Export;


use App\Models\Images;
use App\Models\Report;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ImagesSheet implements WithEvents, WithStrictNullComparison, ShouldAutoSize, WithCustomStartCell, WithTitle
{
    private $report;
    private $images;

    /**
     * @inheritDoc
     */

    public function __construct($id)
    {
        $this->report = Report::where('reports.id', $id)->get()->first();
        $this->images = Images::where('report_id', $id)->get();


    }


    public function startCell(): string
    {
        // TODO: Implement startCell() method.
    }

    /**
     * @inheritDoc
     */
    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {

            $row = 2;
            $sheet = $event->sheet->getDelegate();

            //row formatting
            $sheet->getColumnDimension("A")->setWidth(50);
            $sheet->getColumnDimension("B")->setWidth(50);
            $sheet->getColumnDimension("C")->setWidth(50);
            $sheet->setCellValue('A1', "No");
            $sheet->setCellValue('B1', "Gambar");
            $sheet->setCellValue('C1', "Keterangan");
            //populate data
            foreach ($this->images as $image) {

                //populate numer
                $sheet->setCellValue('A'.$row, $row-1);

                //populate keterangan
                $sheet->setCellValue('C'.$row, $image->keterangan);

                //draw images
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                try {
                    $drawing->setPath('gambar_harian/'.$image->image_path);
                } catch (Exception $e) {
                }
                $drawing->setHeight(100);
                try {
                    $drawing->setWorksheet($sheet);
                } catch (Exception $e) {
                }
                $drawing->setWidth(100);
                $drawing->setCoordinates('B' . $row);

                $sheet->getRowDimension($row)->setRowHeight(100);
                $row++;

            }

            try {
                $sheet->getStyle('A1:C' . ($row-1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            } catch (Exception $e) {
                dd($e);
            }
        }
        ];
    }

    /**
     * @inheritDoc
     */
    public function title(): string
    {
        return "Gambar";
    }
}
