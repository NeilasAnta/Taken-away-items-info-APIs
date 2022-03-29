<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Neilas',
            'surname' => 'Antanavičius',
            'email' => 'n.antanavicius2000@gmail.com',
            'password' => bcrypt('kretinga'),
            'isAdmin' => false
        ]);

        DB::table('users')->insert([
            'name' => 'Justas',
            'surname' => 'Ubartas',
            'email' => 'jubartas@gmail.com',
            'password' => bcrypt('lexus'),
            'isAdmin' => false
        ]);
    }
}
