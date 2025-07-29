<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // get data
        $users = User::whereDoesntHave('roles', function ($query) {
                        $query->where('name', '=', 'admin');
                    })->get();

        return view('backend.users.index', compact('users'));
    }

    public function create()
    {
        return view('backend.users.add');
    }

    public function store(Request $request)
    {
        // validation
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|unique:users',
            'password' => 'required|min:8|confirmed',
            'date_of_birth' => 'required',
            'gender' => 'required',
            'work' => 'required',
        ]);

        // insert to tabel users
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'work' => $request->work,
        ]);

        return redirect('users')->with('message', 'Pelanggan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // find data by id
        $user = User::findOrFail($id);

        return view('backend.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        // find data by id
        $user = User::findOrFail($id);

        // check if email unique the user email
        if ($user->email == $request->email) {
            $rules_email = 'required|email';
        } else {
            $rules_email = 'required|email|unique:users';
        }

        // // check if the user password
        if ($request->password) {
            $rules_password = 'required|min:8|confirmed';
        } else {
            $rules_password = '';
        }

        // validation
        $request->validate([
            'name' => 'required|max:255',
            'email' => $rules_email,
            'password' => $rules_password,
            'date_of_birth' => 'required',
            'gender' => 'required',
            'work' => 'required',
        ]);

        // update to table
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'work' => $request->work,
        ]);

        return redirect('users')->with('message', 'Pelanggan berhasil diubah!');
    }

    public function destroy($id)
    {
        // find data by id
        $user = User::findOrFail($id);

        // delete data
        $user->delete();

        return response()->json(['message' => 'Pelanggan berhasil dihapus!']);
    }
}
