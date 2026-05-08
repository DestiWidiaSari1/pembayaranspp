<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where('username', 'like', '%' . $request->search . '%');
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(5);

        return view('admin.data-user', compact('users'));
    }

    public function create()
    {
        return view('admin.create-user');
    }

    public function store(Request $request)
    {
        $request->validate([
        'username' => 'required|unique:users',
        'password' => 'required|confirmed',
        'role'     => 'required',
    ]);

    User::create([
        'name'     => $request->username,
        'email'    => $request->username . '@dummy.com',
        'username' => $request->username,
        'password' => bcrypt($request->password),
        'role'     => $request->role,
    ]);

    return redirect()->route('data-user')->with('success', 'User berhasil ditambahkan');
}

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'username' => $request->username,
            'role' => $request->role,
        ]);

        return redirect()->route('data-user')->with('success', 'User berhasil diupdate!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('data-user')->with('success', 'User berhasil dihapus!');
    }

    
}