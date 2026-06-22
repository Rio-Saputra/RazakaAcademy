<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Tryout;
use App\Models\Result;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Kuantitas User & Pembeli
        $total_user = User::where('role', 'user')->count();
        $total_buyers = Transaction::where('status', 'success')
            ->distinct('user_id')
            ->count('user_id');

        // 2. Kuantitas Transaksi Sukses
        $total_transaksi = Transaction::where('status', 'success')->count();

        // 3. Pendapatan (Bulan ini & Tahun ini)
        $now = now();
        $monthly_revenue = Transaction::where('status', 'success')
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->sum('amount');

        $yearly_revenue = Transaction::where('status', 'success')
            ->whereYear('created_at', $now->year)
            ->sum('amount');

        // 4. Paket Terlaris (Best Seller)
        $best_seller = Transaction::where('status', 'success')
            ->whereHas('package')
            ->select('package_id', \DB::raw('count(*) as total_sales'))
            ->groupBy('package_id')
            ->orderBy('total_sales', 'desc')
            ->with('package')
            ->first();

        // 5. Daftar Top 5 Paket
        $top_packages = Transaction::where('status', 'success')
            ->whereHas('package')
            ->select('package_id', \DB::raw('count(*) as total_sales'), \DB::raw('sum(amount) as revenue'))
            ->groupBy('package_id')
            ->orderBy('total_sales', 'desc')
            ->with('package')
            ->take(5)
            ->get();

        // 6. Transaksi Sukses Terbaru (5 Data)
        $recent_transactions = Transaction::where('status', 'success')
            ->with(['user', 'package'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 7. Kompilasi Data Grafik Bulanan (Agnostik DB)
        $currentYearTransactions = Transaction::where('status', 'success')
            ->whereYear('created_at', $now->year)
            ->get();

        $monthly_revenue_data = array_fill(1, 12, 0);
        $monthly_transaction_data = array_fill(1, 12, 0);

        foreach ($currentYearTransactions as $t) {
            $month = (int) $t->created_at->format('m');
            $monthly_revenue_data[$month] += (int) $t->amount;
            $monthly_transaction_data[$month]++;
        }

        // Ubah key array ke 0-indexed agar pas dengan Javascript JSON
        $monthly_revenue_data = array_values($monthly_revenue_data);
        $monthly_transaction_data = array_values($monthly_transaction_data);

        return view('admin.dashboard', compact(
            'total_user',
            'total_buyers',
            'total_transaksi',
            'monthly_revenue',
            'yearly_revenue',
            'best_seller',
            'top_packages',
            'recent_transactions',
            'monthly_revenue_data',
            'monthly_transaction_data'
        ));
    }

    public function kelolaUser()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        
        $total_users = User::count();
        $total_admins = User::where('role', 'admin')->count();
        $total_students = User::where('role', 'user')->count();
        
        $now = now();
        $total_new_this_month = User::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();
            
        return view('admin.kelola_user', compact(
            'users',
            'total_users',
            'total_admins',
            'total_students',
            'total_new_this_month'
        ));
    }
    
    public function storeUser(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'User baru berhasil ditambahkan.');
    }


    
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }
        $user->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus dari database.');
    }

    public function transaksi(\Illuminate\Http\Request $request)
    {
        $query = Transaction::with(['user', 'package', 'tryout'])->orderBy('created_at', 'desc');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($qu) use ($search) {
                    $qu->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('package', function($qu) use ($search) {
                    $qu->where('name', 'like', "%{$search}%");
                })->orWhere('snap_token', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $transactions = $query->paginate(7)->withQueryString();
        
        // Calculate statistics based on total database transactions (unfiltered)
        $total_income = Transaction::where('status', 'success')->sum('amount');
        $successful_trxs = Transaction::where('status', 'success')->count();
        $pending_trxs = Transaction::where('status', 'pending')->count();
        $failed_trxs = Transaction::whereIn('status', ['failed', 'deny', 'cancel', 'expire'])->count();

        return view('admin.transaksi', compact(
            'transactions',
            'total_income',
            'successful_trxs',
            'pending_trxs',
            'failed_trxs'
        ));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }

    public function updateProfile(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'old_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('old_password')) {
            if (!\Illuminate\Support\Facades\Hash::check($request->old_password, $user->password)) {
                return redirect()->back()->with('error', 'Password lama tidak sesuai.');
            }
            if ($request->filled('password')) {
                $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
            }
        }

        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
