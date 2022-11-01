<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GolonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('golongans')->insert([
            [
                'name'  => 'PKWTT',
                'code'  => '1'
            ],
            [
                'name'  => 'PKWTT',
                'code'  => '2'
            ],
            [
                'name'  => 'Karyawan PKWT',
                'code'  => '3'
            ],
            [
                'name'  => 'Karyawan PKWT',
                'code'  => '4'
            ]
        ]);
    }
}
