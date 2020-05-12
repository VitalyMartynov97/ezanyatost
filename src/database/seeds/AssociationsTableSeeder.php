<?php

use Illuminate\Database\Seeder;

class AssociationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $associations = [
            [
                'Юный эколог',
                'Проектная деятельность по биологии',
                'Клуб «Юный правовед»',
                'Мой друг - компьютер',
                'Робототехника',
            ],
            [
                'Пионербол',
                'Воллейбол',
                'Футбол',
                'ОФП',
                'Эстетическая гимнастика',
                'Экологический туризм',
            ],
            [
                'Рукоделие',
                'Изостудия «Радуга»',
                'Кружок по деревообработке',
                'Вокальная студия',
            ],
            [
                'Основы духовно-нравственной культуры народов России',
                'Активисты школьного музея',
            ],
        ];

//        \App\Course::all()->each(function ($course) use ($associations) {
//            foreach ($associations[($course->id)-1] as $name) {
//                $association = \App\Association::create([
//                    'name' => $name,
//                    'course_id' => $course->id,
//                ]);
//            };
//        });

        $organisations = \App\Organisation::count();
        for($i = 0; $i < count($associations); ++$i) {
            foreach ($associations[$i] as $association) {
                \App\Organisation::all()->random(rand(1, $organisations))->each(function ($organisation) use ($association, $i) {
                    $assoc = \App\Association::create([
                        'name' => $association,
                        'course_id' => $i + 1,
                        'organisation_id' => $organisation->id,
                    ]);
                });
            }
        }
    }
}
