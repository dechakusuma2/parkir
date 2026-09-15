<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        // Filter berdasarkan User (Opsional)
        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }

        // Filter berdasarkan Aktivitas (Opsional)
        if ($request->has('aktivitas') && $request->aktivitas != '') {
            $query->where('aktivitas', 'like', '%' . $request->aktivitas . '%');
        }

        // Filter berdasarkan Tanggal (Opsional)
        if ($request->has('tanggal') && $request->tanggal != '') {
            $query->whereDate('created_at', $request->tanggal);
        }

        $logs = $query->latest()->paginate(20);
        $users = User::all(); // Untuk dropdown filter user

        return view('admin.logs.index', compact('logs', 'users'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $log = ActivityLog::findOrFail($id);
            $log->delete();

            return back()->with('success', 'Log aktivitas berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus log: ' . $e->getMessage());
        }
    }

    /**
     * Clear all logs (hati-hati menggunakan ini)
     */
    public function clear()
    {
        // Uncomment jika ingin mengaktifkan fitur hapus semua log
        // ActivityLog::truncate();
        // return back()->with('success', 'Semua log aktivitas berhasil dibersihkan.');

        return back()->with('error', 'Fitur hapus semua log dinonaktifkan untuk keamanan.');
    }
}
