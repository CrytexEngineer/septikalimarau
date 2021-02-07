<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RoleTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            'id'=>'1',
            'role_name' => 'Admin',
        ]);

        DB::table('roles')->insert([
            'id'=>'2',
            'role_name' => 'Kasi',
        ]);

        DB::table('roles')->insert([
            'id'=>'3',
            'role_name' => 'Kanit',
        ]);

        DB::table('roles')->insert([
            'id'=>'4',
            'role_name' => 'Petugas',
        ]);
    }}
