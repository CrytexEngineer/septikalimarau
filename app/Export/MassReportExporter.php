<?php

namespace App\Export;

use App\Major;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MassReportExporter implements WithMultipleSheets
{


    /**
     * StudentReportExport constructor.
     */
    private $reports;

    public function __construct($reports)
    {
        $this->reports = $reports;

    }


    /**
     * @inheritDoc
     */
    public function sheets(): array
    {
        $sheets = [];


        $sheets[] = new MassReportSheet($this->reports);
        $sheets[] = new ImagesSheet($this->reports);


        return $sheets;
    }
}

