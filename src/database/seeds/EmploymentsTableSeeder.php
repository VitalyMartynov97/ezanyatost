<?php

use Illuminate\Database\Seeder;

class EmploymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Association::all()->each(function ($association) {
            $teachers = \App\Teacher::where('organisation_id', $association->organisation_id)->count();
            if ($teachers > 2) $teachers = 2;

            \App\Teacher::where('organisation_id', $association->organisation_id)->get()
                ->random(rand(0, $teachers))->each(function ($teacher) use ($association) {
                $employment = \App\Employment::create([
                    'association_id' => $association->id,
                    'teacher_id' => $teacher->id,
                ]);
            });
        });
    }
}
