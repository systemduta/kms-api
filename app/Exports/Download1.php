<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Download1 implements FromCollection, WithHeadings
{
    public function collection()
    {
        return DB::table('users')
            ->join('companies', 'companies.id', '=', 'users.company_id')
            ->join('organizations', 'organizations.id', '=', 'users.organization_id')
            ->join('golongans', 'golongans.id', '=', 'users.golongan_id')
            ->select(
                'users.nik',
                'users.name',
                'companies.name as company',
                'organizations.name as org',
                'golongans.name as gol',
                'golongans.code',
                DB::raw("CASE
                WHEN organizations.isAdm = 1 THEN 'administratif'
                WHEN organizations.isAdm = 0 THEN 'lapangan'
                ELSE 'kosong'
            END as org_type")
            )
            ->where('users.status', '!=', 0)
            ->whereNotIn('companies.name', [
                'MAESA HOLDING',
                'ANUGERAH UTAMA MOTOR',
                'BANK ARTHAYA',
                'CUN MOTOR GROUP',
                'DUA TANGAN INDONESIA',
                'ES KRISTAL PMP GROUP',
                'HENNESY CUISINE',
                'KOPERASI SDM',
                'MAESA FOUNDATION',
                'MAESA HOTEL',
                'MIXTRA INTI TEKINDO',
                'PABRIK ES PMP GROUP',
                'PANDHU DISTRIBUTOR',
                'PRAMA LOGISTIC',
                'PT. PUTRA MAESA PERSADA',
                'Panen Mutiara Pakis',
                'HENNESSY CUISINE',
                'WERKST MATERIAL HANDLING',
                'PT. Prama Madya Parama'
            ])
            ->get();
    }

    public function headings(): array
    {
        return [
            'NIK',
            'Nama',
            'Perusahaan',
            'Posisi',
            'Golongan',
            'Kode Golongan',
            'Tipe'
        ];
    }
}
