<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class systemConfig extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Systemconfig')->insert([
            'Server_ip' => '192.168.51.28:9999',
            'thermal_ip' => '192.168.51.16,192.168.99.10',
            'hiface_ip' => '192.168.51.213',
            'username' => 'quangdn@tinhvan.com',
            'password' => 'Tinhvan123',
        ]);
    }
}
