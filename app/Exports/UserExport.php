<?php


namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

use Maatwebsite\Excel\Concerns\FromCollection;

class UserExport implements FromArray, ShouldAutoSize, WithMapping, WithHeadings, WithEvents
{
    use Exportable;
    public $index = 0;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        $table = 'users';
        $dataUser = DB::table($table)->get()->toArray();
        return $dataUser;
    }

    public function map($dataUser): array
    {

        $this->index += 1;
        return [
            $this->index,
            $dataUser->fullname,
            $dataUser->email,
            $dataUser->reciept_number,
            date('Y-m-d', strtotime($dataUser->purchase_date)),
            $dataUser->score,
            date('Y-m-d', strtotime($dataUser->created_at)),
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Full Name',
            'Email',
            'Reciept Number',
            'Purchase Date',
            'Score',
            'Tanggal Daftar',
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:R1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            }
        ];
    }
}
