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
    private $reports;

    public function __construct( $reports)
    {
        $this->$reports=$reports;

    }




    /**
     * @inheritDoc
     */
    public function sheets():array
    {
        $sheets = [];


            $sheets[] = new MassReportSheet($this->reports);
//            $sheets[] = new ImagesSheet($report->id);


        return $sheets;
    }
}

