<?php

use Illuminate\Database\Seeder;

class SchedulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $teachers = \App\Teacher::all();
//
//        $teachers->each(function($teacher) {
//            $schedule = factory(\App\Schedule::class, rand(1, 2))->create([
//                'teacher_id' => $teacher->id,
//            ]);
//        });

        $associations = \App\Association::all();

        $associations->each(function($association) {
            $teacher = \App\Teacher::where('association_id', $association->id)->get()->random();
            $schedule = factory(\App\Schedule::class, rand(1, 3))->create([
                'teacher_id' => $teacher->id,
            ]);
        });
    }
}
