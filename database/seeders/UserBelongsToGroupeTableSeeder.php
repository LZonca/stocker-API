<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Groupe;

class UserBelongsToGroupeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fetch all users and groups
        $users = User::all();
        $groupes = Groupe::all();

        // For each user, attach random groups
        foreach ($users as $user) {
            $numberOfGroupes = min($groupes->count(), rand(1, 3));
            $randomGroupes = $groupes->random($numberOfGroupes)->pluck('id');
            $user->groupes()->attach($randomGroupes);
        }
    }
}
