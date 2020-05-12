<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmploymentController extends Controller
{
    public function index()
    {
        $hasOrganisation = request()->get('hasOrganisation');

        $employments = \DB::table('employments')
            ->join('associations',
                'employments.association_id',
                '=',
                'associations.id')
            ->join('organisations',
                'associations.organisation_id',
                '=',
                'organisations.id')
            ->join('teachers',
                'employments.teacher_id',
                '=',
                'teachers.id')
            ->join('users',
                'teachers.user_id',
                '=',
                'users.id')
            ->when($hasOrganisation, function ($query, $hasOrganisation) {
                $query->where('organisations.id', $hasOrganisation);
            })
            ->select('employments.*',
                'associations.name AS association',
                'organisations.short_name AS organisation',
                'users.name AS teacher')
            ->paginate(20);

        return view('closed.admin.employments.index', [
            'employments' => $employments,
        ]);
    }

    public function fetchData() {
        $hasOrganisation = request()->get('hasOrganisation');

        $employments = \DB::table('employments')
            ->join('associations',
                'employments.association_id',
                '=',
                'associations.id')
            ->join('organisations',
                'associations.organisation_id',
                '=',
                'organisations.id')
            ->join('teachers',
                'employments.teacher_id',
                '=',
                'teachers.id')
            ->join('users',
                'teachers.user_id',
                '=',
                'users.id')
            ->where(function ($query) {
                $search = request()->get('query');

                $query->where('associations.name', 'like', '%'.$search.'%')
                    ->orWhere('users.name', 'like', '%'.$search.'%')
                    ->orWhere('organisations.short_name', 'like', '%'.$search.'%');
            })
            ->when($hasOrganisation, function ($query, $hasOrganisation) {
                $query->where('organisations.id', $hasOrganisation);
            })
            ->select('employments.*',
                'associations.name AS association',
                'organisations.short_name AS organisation',
                'users.name AS teacher')
            ->orderBy(request()->get('sortby'), request()->get('sorttype'))
            ->paginate(20);



        return view('closed.admin.employments.index_data', [
            'employments' => $employments,
        ])->render();
    }

    public function create()
    {
        $hasOrganisation = request()->get('hasOrganisation');

        $organisations = \App\Organisation::when($hasOrganisation, function ($query, $hasOrganisation) {
            $query->where('id', $hasOrganisation);
        })->get();

        return view('closed.admin.employments.create', [
            'organisations' => $organisations,
        ]);
    }

    public function store()
    {
        $data = request()->validate([
            'teacher_id' => 'required|unique:employments,teacher_id,NULL,NULL,association_id,'.request()->get('association_id'),
            'association_id' => 'required|unique:employments,association_id,NULL,NULL,teacher_id,'.request()->get('teacher_id'),
            'organisation_id' => 'required',
        ]);

        $employment = \App\Employment::create([
            'association_id' => $data['association_id'],
            'teacher_id' => $data['teacher_id'],
        ]);

        return back()->with('success', 'Связь успешно добавлена');
    }

    public function destroy($id) {
        $employment = \App\Employment::findOrFail($id);
        $employment->delete();
        return false;
    }

    public function fetchAssociations() {
        $associations = \DB::table('associations')
            ->where('associations.organisation_id', request()->input('values.0'))
            ->get();

        return view('includes.options', [
            'options' => $associations,
            'name' => 'объединение',
        ]);
    }

    public function fetchTeachers() {
        $teachers = \DB::table('teachers')
            ->join('users',
                'teachers.user_id',
                '=',
                'users.id')
            ->where('teachers.organisation_id', request()->input('values.0'))
            ->select('users.name', 'teachers.id')
            ->get();

        return view('includes.options', [
            'options' => $teachers,
            'name' => ' преподавателя',
        ]);
    }
}
