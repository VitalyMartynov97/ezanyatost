<?php

use Illuminate\Database\Seeder;

class HomeworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Association::all()->each(function ($association) {
            $homeworks = factory(App\Homework::class, rand(40, 60))->create(['association_id' => $association->id]);
        });
    }
}
