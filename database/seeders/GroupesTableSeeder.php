<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Groupe;

class GroupesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Groupe::factory(10)->create();
    }
}
