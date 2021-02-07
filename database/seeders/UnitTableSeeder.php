<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UnitTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('units')->insert([
            'id'=>'1',
            'unit_name' => 'AMC',
        ]);

        DB::table('units')->insert([
            'id'=>'2',
            'unit_name' => 'AAB',
        ]);

        DB::table('units')->insert([
            'id'=>'3',
            'unit_name' => 'BANGLAND',
        ]);

        DB::table('units')->insert([
            'id'=>'4',
            'unit_name' => 'ELBAND',
        ]);

        DB::table('units')->insert([
            'id'=>'5',
            'unit_name' => 'LISTRIK',
        ]);

        DB::table('units')->insert([
            'id'=>'6',
            'unit_name' => 'ADMIN',
        ]);
    }}
