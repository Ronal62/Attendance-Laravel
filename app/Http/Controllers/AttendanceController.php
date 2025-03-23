<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;


class AttendanceController //extends RoutingController
{
    /**
     * Menampilkan semua data absensi
     */
    public function index()
    {
        $attendances = Attendance::with('user')->orderBy('timestamp', 'desc')->get();
        return response()->json($attendances);
    }

    /**
     * Menyimpan data absensi dengan validasi
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'image' => 'required|string' // Harus berupa string base64
        ]);

        // Cek apakah user_id ada di database
        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User ID tidak ditemukan!'], 404);
        }

        // Simpan absensi jika user ditemukan
        $attendance = Attendance::create([
            'user_id' => $request->user_id,
            'timestamp' => now()
        ]);

        if ($attendance) {
            return response()->json(['success' => true, 'message' => 'Absensi berhasil!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Absensi gagal!'], 500);
        }
    }
}
