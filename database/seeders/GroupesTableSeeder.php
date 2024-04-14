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
        Groupe::create([
            'nom' => 'Test Groupe',
            'proprietaire_id' => 1, // assuming a user with id 1 exists
        ]);
    }
}
