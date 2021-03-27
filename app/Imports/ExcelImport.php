<?php

namespace App\Imports;

use Storage;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;
use Illuminate\Http\Request;

class ExcelImport implements WithHeadingRow, WithEvents
{
    private $data;

    /**
     * Assign data to excel imports
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function(AfterImport $event) {
                Storage::delete($this->data['file']);
            }
        ];
    }
}
