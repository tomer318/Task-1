<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    // 1. Trang Tổng quan
    public function edit(Request $request)
    {
        $user = $request->user();
        $orders = \App\Models\Order::with('items')->where('user_id', $user->id)->latest()->get();
        
        $ordersCount = $orders->count();
        $totalSpent = $orders->where('payment_status', 'Đã thanh toán')->sum('total_price');

        return view('shop.member-profile', [
            'user' => $user,
            'ordersCount' => $ordersCount,
            'totalSpent' => $totalSpent,
            'recentOrders' => $orders,
            'activeTab' => 'overview'
        ]);
    }

    // 2. Trang Lịch sử mua hàng
    public function orders(Request $request)
    {
        $user = $request->user();
        $orders = \App\Models\Order::with('items')->where('user_id', $user->id)->latest()->get();
        $ordersCount = $orders->count();
        $totalSpent = $orders->where('payment_status', 'Đã thanh toán')->sum('total_price');

        return view('shop.member-profile', [
            'user' => $user,
            'ordersCount' => $ordersCount,
            'totalSpent' => $totalSpent,
            'recentOrders' => $orders,
            'activeTab' => 'orders'
        ]);
    }

    // 3. Trang Hạng thành viên & Ưu đãi
    public function promotion(Request $request)
    {
        $user = $request->user();
        $ordersCount = \App\Models\Order::where('user_id', $user->id)->count();
        $totalSpent = \App\Models\Order::where('user_id', $user->id)->where('payment_status', 'Đã thanh toán')->sum('total_price');

        return view('shop.member-profile', [
            'user' => $user,
            'ordersCount' => $ordersCount,
            'totalSpent' => $totalSpent,
            'activeTab' => 'promotion'
        ]);
    }

    // 4. Trang Thông tin tài khoản & Sổ địa chỉ
    public function userInfo(Request $request)
    {
        $user = $request->user();
        $addresses = method_exists($user, 'addresses') ? $user->addresses : collect([]);
        $ordersCount = \App\Models\Order::where('user_id', $user->id)->count();
        $totalSpent = \App\Models\Order::where('user_id', $user->id)->where('payment_status', 'Đã thanh toán')->sum('total_price');

        return view('shop.member-profile', [
            'user' => $user,
            'addresses' => $addresses,
            'ordersCount' => $ordersCount,
            'totalSpent' => $totalSpent,
            'activeTab' => 'user-info'
        ]);
    }

    // Cập nhật thông tin cá nhân
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'string', 'in:Nam,Nữ,Khác'],
            'birthday' => ['nullable', 'date'],
        ]);

        $user->fill($validated);
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        return Redirect::route('profile.user.info')->with('status', 'profile-updated');
    }

    // Xóa tài khoản
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}