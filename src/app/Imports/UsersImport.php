<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class UsersImport implements WithHeadingRow, WithValidation, OnEachRow
{
    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row      = $row->toArray();
        //$row['password'] = \Hash::make($row['password']);

        $role = \DB::table('roles')->where('name', $row['role'])->value('id');

        $user = \App\User::create([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => \Hash::make($row['password']),
            'username' => $row['username'],
            'role_id' => $role,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|min:2|max:50|alpha_space',
            'username' => 'required|min:2|max:50|alphanum_dot|unique:users',
            'email' => 'nullable|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|exists:roles,name',
        ];
    }

    public function customValidationAttributes()
    {
        return [
            'name' => 'Имя',
            'username' => 'Логин',
            'email' => 'Электронный адрес',
            'password' => 'Пароль',
            'role' => 'Роль',
        ];
    }
}
