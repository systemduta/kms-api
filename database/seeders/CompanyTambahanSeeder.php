<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyTambahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            [
                'code'  => 'MCC',
                'name'  => '001 - PT. Maesa Cipta Corporindo'
            ],
            [
                'code'  => 'PMPU',
                'name'  => '002 - PT. Prima Maesa Putra'
            ],
            [
                'code'  => 'PMPE',
                'name'  => '003 - PT. Putra Maesa Persada'
            ],
            [
                'code'  => 'CUN1',
                'name'  => '005 - PT. Cahaya Unggul Nusantara'
            ],
            [
                'code'  => 'KSDM',
                'name'  => '006 - Koperasi Sumber Dana Makmur'
            ],
            [
                'code'  => 'MCHO',
                'name'  => '007 - PT. Maesa Cipta Hotelindo'
            ],
            [
                'code'  => 'AIPA',
                'name'  => '008 - PT. Arthaya Indotama Pusaka'
            ],
            [
                'code'  => 'CUN2',
                'name'  => '009 - PT. Citra Usaha Nusantara'
            ],
            [
                'code'  => 'CUN3',
                'name'  => '010 - PT. Cipta Usaha Nusantara'
            ],
            [
                'code'  => 'MIT',
                'name'  => '011 - PT. Mixtra Inti Tekindo'
            ],
            [
                'code'  => 'PND',
                'name'  => '012 - PT. Pandu Mahardika Perdana'
            ],
            [
                'code'  => 'CUN4',
                'name'  => '015 - PT. Cakra Usaha Nusantara'
            ],
            [
                'code'  => 'PD',
                'name'  => '016 - PT. Mitra Tata Esindo'
            ],
            [
                'code'  => 'MKE',
                'name'  => '017 - PT. Mitra Kelola Esindo'
            ],
            [
                'code'  => 'PMPA',
                'name'  => '018 - PT. Prama Madya Parama'
            ],
            [
                'code'  => 'DTI',
                'name'  => '019 - PT. Dua Tangan Indonesia'
            ],
            [
                'code'  => 'AUM',
                'name'  => '020 - CV. Anugerah Utama Motor'
            ],
            [
                'code'  => 'MSE',
                'name'  => '021 - PT. Mitra Samudera Esindo'
            ],
            [
                'code'  => 'CUN5',
                'name'  => '022 - PT. Cempaka Usaha Nusantara'
            ],
            [
                'code'  => 'PMPA',
                'name'  => '023 - PT. Panen Mutiara Pakis'
            ],
        ]);
    }
}
