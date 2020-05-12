<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = App\User::create([
            'name' => 'Свирин Сергей Дмитриевич',
            'email' => 'Darkraver2012@gmail.com',
            'username' => 'darkraver2012',
            'password' => \Hash::make('kilroy'),
            'role_id' => 1,
        ]);
        //$users = factory(App\User::class, 10)->create(['role_id' => 2]);
        //$users = factory(App\User::class, 30)->create(['role_id' => 3]);
        //$users = factory(App\User::class, 10)->create(['role_id' => 5]);
    }
}
