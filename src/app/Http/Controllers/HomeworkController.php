<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeworkController extends Controller
{
    public function index()
    {
        $hasOrganisation = request()->get('hasOrganisation');
        $hasAssociation = request()->get('hasAssociation');

        $homeworks = \DB::table('homeworks')
            ->join('associations',
                'homeworks.association_id', '=', 'associations.id')
            ->join('organisations',
                'associations.organisation_id', '=', 'organisations.id')
            ->when($hasOrganisation, function ($query, $hasOrganisation) {
                $query->where('organisations.id', $hasOrganisation);
            })
            ->when($hasAssociation, function ($query, $hasAssociation) {
                $query->where('associations.id', $hasAssociation);
            })
            ->where([
                ['homeworks.date', '>=', (new \DateTime())->modify('-7 day')->format('Y-m-d')],
                ['homeworks.date', '<=', (new \DateTime())->modify('+7 day')->format('Y-m-d')],
            ])
            ->select('associations.name AS association',
                'organisations.short_name AS organisation',
                'homeworks.id AS id', 'homeworks.date AS date', 'homeworks.value AS homework')
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('closed.admin.homeworks.index', [
            'homeworks' => $homeworks,
        ]);
    }

    public function fetchData() {
        $hasOrganisation = request()->get('hasOrganisation');
        $hasAssociation = request()->get('hasAssociation');

        $homeworks = \DB::table('homeworks')
            ->join('associations',
                'homeworks.association_id', '=', 'associations.id')
            ->join('organisations',
                'associations.organisation_id', '=', 'organisations.id')
            ->where(function ($query) {
                $search = request()->get('search');

                $query->where('associations.name', 'like', '%'.$search.'%')
                    ->orWhere('organisations.short_name', 'like', '%'.$search.'%')
                    ->orWhere('homeworks.date', 'like', '%'.$search.'%')
                    ->orWhere('homeworks.value', 'like', '%'.$search.'%');
            })
//            ->where(function ($query) {
//                $start = request()->get('start');
//                $end = request()->get('end');
//
//                $query->where([
//                    ['homeworks.date', '>=', new \DateTime($start)],
//                    ['homeworks.date', '<=', new \DateTime($end)],
//                ]);
//            })
            ->when(request()->get('start'), function ($query, $start) {
                $query->where('homeworks.date', '>=', new \DateTime($start));
            })
            ->when(request()->get('end'), function ($query, $end) {
                $query->where('homeworks.date', '<=', new \DateTime($end));
            })
            ->when($hasOrganisation, function ($query, $hasOrganisation) {
                $query->where('organisations.id', $hasOrganisation);
            })
            ->when($hasAssociation, function ($query, $hasAssociation) {
                $query->where('associations.id', $hasAssociation);
            })
            ->select('associations.name AS association',
                'organisations.short_name AS organisation',
                'homeworks.id AS id', 'homeworks.date AS date', 'homeworks.value AS homework')
            ->orderBy(request()->get('column_name'), request()->get('sort_type'))
            ->paginate(10);

        return view('closed.admin.homeworks.index_data', [
            'homeworks' => $homeworks,
        ])->render();
    }

    public function create()
    {
        $hasOrganisation = request()->get('hasOrganisation');

        $organisations = \App\Organisation::when($hasOrganisation, function ($query, $hasOrganisation) {
            $query->where('id', $hasOrganisation);
        })->get();

        return view('closed.admin.homeworks.create', [
            'organisations' => $organisations,
        ]);
    }

    public function store()
    {
        $data = request()->validate([
            'organisation_id' => 'required',
            'association_id' => 'required',
            'date' => 'required|date_format:Y-m-d',
            'value' => 'required',
        ]);

        $homework = \App\Homework::create([
            'association_id' => $data['association_id'],
            'date' => $data['date'],
            'value' => $data['value'],
        ]);

        return back()->with('success', 'Запись успешно добавлена');
    }

    public function fetchAssociations() {
        $hasAssociation = request()->get('hasAssociation');

        $associations = \DB::table('associations')
            ->when($hasAssociation, function ($query, $hasAssociation) {
                $query->where('associations.id', $hasAssociation);
            })
            ->where('associations.organisation_id', request()->input('values.0'))
            ->get();

        return view('includes.options', [
            'options' => $associations,
            'name' => 'объединение',
        ]);
    }

    public function destroy($id) {
        $homework = \App\Homework::findOrFail($id);
        $homework->delete();
        return false;
    }
}
