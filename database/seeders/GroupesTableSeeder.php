<?php

namespace Database\Seeders;

use App\Models\Groupe;
use Illuminate\Database\Seeder;

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
