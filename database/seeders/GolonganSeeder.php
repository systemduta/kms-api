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
                'name'  => 'CEO/Direktur',
                'code'  => '8'
            ],
            [
                'name'  => 'CSO/CFO/CPO/COO/CMO/GM',
                'code'  => '7'
            ],
            [
                'name'  => 'Manager',
                'code'  => '6'
            ],
            [
                'name'  => 'Supervisor',
                'code'  => '5'
            ],
            [
                'name'  => 'Staff',
                'code'  => '4'
            ]
        ]);
    }
}
