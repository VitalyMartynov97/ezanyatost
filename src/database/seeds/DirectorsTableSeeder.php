<?php

use Illuminate\Database\Seeder;

class DirectorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::where('role_id', 2)->get()->each(function ($user) {
            $director = \App\Director::create([
                'user_id' => $user->id,
            ]);
        });
    }
}
