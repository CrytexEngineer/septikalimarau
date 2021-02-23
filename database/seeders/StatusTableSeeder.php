<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusTableSeeder extends Seeder {

    public function run()
    {
        DB::table('status')->insert([
            'id' => '1',
            'status_name' => 'Active',
        ]);

        DB::table('status')->insert([
            'id' => '2',
            'status_name' => 'Submitted',
        ]);

        DB::table('status')->insert([
            'id' => '3',
            'status_name' => 'Kepala Unit',
        ]);
        DB::table('status')->insert([
            'id' => '4',
            'status_name' => 'Kepala Seksi',
        ]);
        DB::table('status')->insert([
            'id' => '5',
            'status_name' => 'Approved',
        ]);
        DB::table('status')->insert([
            'id' => '6',
            'status_name' => 'Rejected',
        ]);
    }

}
