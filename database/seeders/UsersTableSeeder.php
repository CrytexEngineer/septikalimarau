<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'admin@kalimarau.id',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
            'unit_id'=>'6',
            'nip'=>'1997072320201201',
            'role_id'=>'1',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
