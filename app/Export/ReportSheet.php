<?php


namespace App\Export;


use App\Models\Record;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ReportSheet implements WithEvents, WithStrictNullComparison, ShouldAutoSize, WithCustomStartCell, WithTitle
{

    /**
     * ReportSheet constructor.
     * @param mixed $id
     */

    private $report;
    private $title;
    private $unit;


    public function __construct($id)
    {
        $this->report = Report::where('reports.id', $id)->get()->first();
        $this->unit = Report::where('reports.id', $id)->join("units", "units.id", "=", "reports.unit_id")->get()->first();
        $this->title = Report::where('reports.id', $id)->join("tasks", "tasks.id", "=", "reports.task_id")->get()->first()->task_name;

    }

    /**
     * @inheritDoc
     */
    public function startCell(): string
    {
        // TODO: Implement startCell() method.
    }

    /**
     * @inheritDoc
     */
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {
                $data = Record::where("report_id", "=", $this->report->id)
                    ->join('items', 'items.id', '=', 'records.item_id')->get()->all();
                $sheet = $event->sheet->getDelegate();


                //Gambar Logo
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                try {
                    $drawing->setPath('material/img/logo-dishub.png');
                } catch (Exception $e) {
                }
                $drawing->setHeight(100);
                try {
                    $drawing->setWorksheet($sheet);
                } catch (Exception $e) {
                }
                $drawing->setWidth(100);
                $drawing->setCoordinates('A1');


                //Populate Data
                $sheet->setCellValue('C1', "DIREKTORAT JENDERAL PERHUBUNGAN UDARA KANTOR UPBU KELAS I KALIMARAU");
                $sheet->setCellValue('C3', "DAILY INSPEKSI FASILITAS ");
                $sheet->setCellValue('C4', "UNIT " . $this->unit->unit_name);
                $sheet->setCellValue('A6', "Tanggal:" . Carbon::createFromFormat('Y-m-d H:i:s', $this->report['created_at'])->isoFormat('dddd, D MMMM Y'));
                $sheet->setCellValue('B7', $this->title);
                $sheet->setCellValue('A7', "NO");
                $sheet->setCellValue('E7', $this->report->keterangan);
                $sheet->setCellValue('C7', "KONDISI PAGI");
                $sheet->setCellValue('D7', "KONDISI SIANG");
                $sheet->setCellValue('C8', "JAM:" . date("h:i", strtotime($this->report['created_at'])));
                $sheet->setCellValue('D8', "JAM:" . date("h:i", strtotime($this->report['updated_at'])));

                $row = 9;
                $itemCount = 1;

                foreach ($data as $record) {
                    $sheet->setCellValue('A' . $row, $itemCount);
                    $sheet->setCellValue('B' . $row, $record->item_name);
                    if ($record->kondisi_pagi == 0) {
                        $sheet->setCellValue('C' . $row, "-");
                    }
                    if ($record->kondisi_pagi == 1) {
                        $sheet->setCellValue('C' . $row, "V");
                    }
                    if ($record->kondisi_pagi == 2) {
                        $sheet->setCellValue('C' . $row, "*");
                    }
                    if ($record->kondisi_pagi == 3) {
                        $sheet->setCellValue('C' . $row, "X");
                    }
                    if ($record->kondisi_siang == 0) {
                        $sheet->setCellValue('D' . $row, "-");
                    }
                    if ($record->kondisi_siang == 1) {
                        $sheet->setCellValue('D' . $row, "V");
                    }
                    if ($record->kondisi_siang == 2) {
                        $sheet->setCellValue('D' . $row, "*");
                    }
                    if ($record->kondisi_siang == 3) {
                        $sheet->setCellValue('D' . $row, "X");
                    }
                    $row++;
                    $itemCount++;
                }


                $sheet->setCellValue('A' . ($row + 1), "PETUGAS");
                $sheet->setCellValue('A' . ($row + 3), "KANIT");
                $sheet->setCellValue('A' . ($row + 5), "KASI");
                $petugasPagi = User::where('id', $this->report['petugas_pagi_id'])->get()->first();
                $petugasSiang = User::where('id', $this->report['petugas_siang_id'])->get()->first();
                $sheet->setCellValue('C' . ($row + 1), $petugasPagi['name']);
                $sheet->setCellValue('D' . ($row + 1), $petugasSiang['name']);
                $kanit = User::where('id', $this->report['kanit_id'])->get()->first();
                $sheet->setCellValue('C' . ($row + 3), $kanit['name']);
                $kasi = User::where('id', $this->report['kasi_id'])->get()->first();
                $sheet->setCellValue('C' . ($row + 5), $kasi['name']);
                $sheet->setCellValue('B' . ($row + 7), "Petunjuk:");
                $sheet->setCellValue('C' . ($row + 8), "-");
                $sheet->setCellValue('D' . ($row + 8), "Belum Di Cek");
                $sheet->setCellValue('C' . ($row + 9), "V");
                $sheet->setCellValue('D' . ($row + 9), "Baik");
                $sheet->setCellValue('C' . ($row + 10), "*");
                $sheet->setCellValue('D' . ($row + 10), "Kurang Baik");
                $sheet->setCellValue('C' . ($row + 11), "X");
                $sheet->setCellValue('D' . ($row + 11), "Tidak Baik");
                $sheet->setCellValue('E2', $this->report->keterangan);
                //Set Cell Size
                $sheet->getColumnDimension("A")->setWidth(20);
                $sheet->getColumnDimension("B")->setWidth(30);
                $sheet->getColumnDimension("C")->setWidth(20);
                $sheet->getColumnDimension("D")->setWidth(20);


                //Merge cell
                $cellToMerge = [
                    "A1:B5",
                    "C1:I2",
                    "C3:I3",
                    "C4:I5",
                    "A6:I6",
//                    "A2:D3",
                    "A7:A8",
                    "B7:B8",
                    "A" . ($row + 1) . ":B" . ($row + 2),
                    "A" . ($row + 3) . ":B" . ($row + 4),
                    "A" . ($row + 5) . ":B" . ($row + 6),
                    "C" . ($row + 1) . ":C" . ($row + 2),
                    "C" . ($row + 3) . ":C" . ($row + 4),
                    "C" . ($row + 5) . ":C" . ($row + 6),
                    "D" . ($row + 1) . ":D" . ($row + 2),
                    "D" . ($row + 3) . ":D" . ($row + 4),
                    "D" . ($row + 5) . ":D" . ($row + 6),
                    "E7:I" . ($row + 6)
//                    "B" . ($startRow) . ":B" . $row,
//                    "C" . ($startRow) . ":C" . $row,
//                    "D" . ($startRow) . ":D" . $row,
//                    "E" . ($startRow) . ":E" . $row,
//                    "F" . ($startRow) . ":F" . $row,
//                    "G" . ($startRow) . ":G" . $row,
                ];

                try {
                    $sheet->getStyle('A1:I' . ($row + 6))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                    $sheet->getStyle('B1:I' . $sheet->getHighestRow())
                        ->getAlignment()->setWrapText(true);
                    
                } catch (Exception $e) {
                    dd($e);
                }

                try {
                    foreach ($cellToMerge as $rage) {
                        $sheet->getStyle($rage)->getAlignment()->setVertical('center');
                        $sheet->mergeCells($rage);

                    }

                } catch (Exception $e) {
                    dd($e);
                }


            }];
    }

    /**
     * @inheritDoc
     */
    public function title(): string
    {

        return $this->title;
    }
}
