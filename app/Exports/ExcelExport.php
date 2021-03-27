<?php

namespace App\Exports;

use File;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\BeforeExport;

class ExcelExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    use Exportable;

    private $data;

    /**
     * Assign data to excel exports
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->data['images'] = [];
        $this->data['image_count'] = 0;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        foreach ($this->data['rows'] as $idx => $row) {
            foreach ($row as $key => $value) {
                if (in_array($key, ['image', 'avatar']) && $this->data['format'] != "csv") {
                    if ($value && last(explode(".", $value)) !== "svg") {
                        $this->data['rows'][$idx]->$key = '';
                        $this->data['image_count'] += 1;
                    }

                    array_push($this->data['images'], $value);
                }
            }
        }

        return collect($this->data['rows']);
    }

    public function headings(): array
    {
        return $this->data['headings'];
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function(BeforeExport $event) {
                $event->writer->getProperties()->setCreator(config('app.brand.name'));
            },
            AfterSheet::class => function(AfterSheet $event) {
                // freeze the first row with headings
                $event->sheet->freezePane('A2', 'A2');

                // add image to cell
                if ($this->data['image_count']) {
                    $cell_counter = 2;

                    foreach ($this->data['images'] as $idx => $img_path) {
                        if ($img_path && last(explode(".", $img_path)) !== "svg") {
                            $im = imagecreatefromstring(file_get_contents(getImage($img_path, 100, 100, 95, 0, 'b')));
                            $img_path = storage_path('app/public/excel/img-' . $idx . '.jpg');

                            if ($im !== false) {
                                header('Content-Type: image/jpeg');
                                imagejpeg($im, $img_path);
                                imagedestroy($im);

                                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                                $drawing->setPath($img_path);
                                $drawing->setCoordinates('A' . $cell_counter);
                                $drawing->setWorksheet($event->sheet->getDelegate());

                                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10.8);
                                $spreadsheet->getActiveSheet()->getRowDimension($cell_counter)->setRowHeight(76);
                            }
                        }

                        $cell_counter++;
                    }
                }
            },
            BeforeSheet::class => function(BeforeSheet $event) {
                File::delete(File::glob(storage_path('app/public/excel/img-*')));
            },
        ];
    }
}
