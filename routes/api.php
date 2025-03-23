<?php

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserController;
// use Illuminate\Support\Facades\Storage;

Route::post('/attendance', function (Request $request) {
    // Simpan absensi ke database
    $attendance = Attendance::create([
        'user_id' => $request->user_id,
        'timestamp' => now(),
    ]);

    return response()->json(['message' => 'Absensi berhasil!', 'data' => $attendance]);
});

// Route::post('/absensi-wajah', function (Request $request) {
//     // Ambil gambar base64 dari request
//     $image = $request->image;
//     $imageName = 'absensi_' . time() . '.png';

//     // Simpan gambar di storage Laravel (opsional)
//     Storage::disk('public')->put($imageName, base64_decode(explode(',', $image)[1]));

//     return response()->json(['success' => true, 'message' => 'Gambar wajah disimpan!']);
// });

// Route::get('/absensi', function () {
//     $data = Attendance::with('user')->orderBy('timestamp', 'desc')->get();
//     return response()->json($data);
// });


Route::post('/absensi-wajah', [AttendanceController::class, 'store']);
Route::get('/absensi', [AttendanceController::class, 'index']);