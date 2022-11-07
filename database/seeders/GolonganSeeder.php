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
                'name'  => 'Staf PKWT',
                'code'  => '1'
            ],
            [
                'name'  => 'Staf PKWT',
                'code'  => '2'
            ],
            [
                'name'  => 'Staf PKWTT',
                'code'  => '3'
            ],
            [
                'name'  => 'Staf PKWTT',
                'code'  => '4'
            ]
        ]);
    }
}
