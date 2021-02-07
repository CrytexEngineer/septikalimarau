<?php

namespace App\Export;

use App\Export\ReportSheet;
use App\Exports\StudentByMajorSheet;
use App\Major;
use App\Models\Report;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use phpDocumentor\Reflection\Types\Nullable;

class ReportExporter implements WithMultipleSheets
{



    /**
     * StudentReportExport constructor.
     */
    private $report_id;

    public function __construct( $report_id)
    {
        $this->report_id=$report_id;

    }

    /**
     * @inheritDoc
     */
    public function sheets():array
    {
        $sheets = [];



        $reports = Report::where('id','=',$this->report_id)->get();

        foreach ($reports as $report) {

            $sheets[] = new ReportSheet($report->id);
            $sheets[] = new ImagesSheet($report->id);

        }

        return $sheets;
    }
}

