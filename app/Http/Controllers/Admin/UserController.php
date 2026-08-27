<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        return redirect()->route('admin.users.index')->with('success', 'Đã cập nhật thông tin người dùng!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Đã xóa tài khoản người dùng!');
    }
}