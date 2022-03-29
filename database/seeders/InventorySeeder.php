<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('inventories')->insert([
            'name' => 'Modem',
            'user_id' => null,
            'model' => 'RUTX11',
            'serial_number' => '1114915382',
            'comment' => 'Šis gaminys yra supakeistu GSM modeliu. Gaminys neturi korpuso.',
        ]);

        DB::table('inventories')->insert([
            'name' => 'Modem',
            'user_id' => null,
            'model' => 'RUTX911',
            'serial_number' => '1117915382',
            'comment' => 'Šis gaminys yra supakeistu GSM modeliu. Gaminys neturi korpuso.',
        ]);
    }
}
