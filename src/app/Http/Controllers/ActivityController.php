<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $hasOrganisation = request()->get('hasOrganisation');

        $activities = \DB::table('activities')
            ->join('associations',
                'activities.association_id',
                '=',
                'associations.id')
            ->join('courses',
                'associations.course_id',
                '=',
                'courses.id')
            ->join('organisations',
                'activities.organisation_id',
                '=',
                'organisations.id')
            ->when($hasOrganisation, function ($query, $hasOrganisation) {
                $query->where('organisations.id', $hasOrganisation);
            })
            ->select('activities.*',
                'associations.name AS association',
                'organisations.short_name AS organisation',
                'courses.name AS course')
            ->orderBy('activities.id')
            ->paginate(10);

        return view('closed.admin.activities.index', [
            'activities' => $activities,
        ]);
    }

    public function fetchData() {
        $hasOrganisation = request()->get('hasOrganisation');

        $activities = \DB::table('activities')
            ->join('associations',
                'activities.association_id',
                '=',
                'associations.id')
            ->join('courses',
                'associations.course_id',
                '=',
                'courses.id')
            ->join('organisations',
                'activities.organisation_id',
                '=',
                'organisations.id')
            ->where(function ($query) {
                $search = request()->get('query');

                $query->where('activities.id', 'like', '%'.$search.'%')
                    ->orWhere('associations.name', 'like', '%'.$search.'%')
                    ->orWhere('courses.name', 'like', '%'.$search.'%')
                    ->orWhere('organisations.short_name', 'like', '%'.$search.'%');
            })
            ->when($hasOrganisation, function ($query, $hasOrganisation) {
                $query->where('organisations.id', $hasOrganisation);
            })
            ->select('activities.*',
                'associations.name AS association',
                'organisations.short_name AS organisation',
                'courses.name AS course')
            ->orderBy(request()->get('sortby'), request()->get('sorttype'))
            ->paginate(10);



        return view('closed.admin.activities.index_data', [
            'activities' => $activities
        ])->render();
    }

    public function create()
    {
        $hasOrganisation = request()->get('hasOrganisation');
        $organisations = \App\Organisation::when($hasOrganisation, function ($query, $hasOrganisation) {
            $query->where('id', $hasOrganisation);
        })->get();
        $associations = \App\Association::all();
        $courses = \App\Course::all();
        return view('closed.admin.activities.create', [
            'associations' => $associations,
            'organisations' => $organisations,
            'courses' => $courses,
        ]);
    }

    public function store()
    {
        $data = request()->validate([
            'association_id' => 'required',
            'organisation_id' => 'required',
        ]);

//        $association = request()->input('association_id');
//        $organisation = request()->input('organisation_id');
//        $count = \DB::table('activities')
//            ->where([
//                ['association_id', '=', $association],
//                ['organisation_id', '=', $organisation],
//            ])
//            ->count();
//        if ($count) {
//            return back()->withInput()->withErrors(['Такая запись уже существует']);
//        }

        $activity = \App\Activity::firstOrCreate($data);

        return back()->with('success', 'Связь успешно добавлена');
    }

    public function destroy($id) {
        $activity = \App\Activity::findOrFail($id);
        $activity->delete();
        return back()->with('success', 'Связь успешно удалена');
    }
}
