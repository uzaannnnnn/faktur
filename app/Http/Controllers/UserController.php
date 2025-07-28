<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Obat;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function dashboard()
    {
        $totalObat = Obat::where('status', 'tersedia')->count();
        $totalFaktur = Order::count();
        $totalOrdersBulanIni = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $totalUsers = User::count();


        $stokMinimum = Obat::where('quantity', '<', 15)->where('quantity', '>', 0)->get();


        $hampirExpired = Obat::where('ed', '<=', now()->addDays(60))
            ->where('ed', '>', now())
            ->where('status', 'tersedia')
            ->get();


        return view('admin.dashboard', compact(
            'totalObat',
            'totalFaktur',
            'totalOrdersBulanIni',
            'totalUsers',
            'stokMinimum',
            'hampirExpired'
        ));
    }

    public function index()
    {
        $users = User::paginate(10);
        return view('admin.users', compact('users'));
    }

    public function create()
    {
        return view('admin.add-user');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,fakturis,gudang,customer',
            'alamat' => 'nullable|string|max:255',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'alamat' => $request->alamat,
            'status' => 'aktif',
        ]);

        return redirect()->route('users')->with('success', 'User berhasil ditambahkan.');
    }

    public function tambahSales(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'sales' => 'required|string|max:100',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->sales = $request->sales;
        $user->save();

        return back()->with('success', 'Sales berhasil ditambahkan ke user.');
    }
    public function aktifkan($id)
    {
        $user = User::findOrFail($id);

        if ($user->status === 'block') {
            $user->status = 'aktif';
            $user->tagihan = 0;
            $user->jatuh_tempo = null;
            $user->save();
        }

        return back()->with('success', 'User berhasil diaktifkan kembali.');
    }
}
