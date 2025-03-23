<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Controller //extends Controller
{
public function store(Request $request)
{
// Validasi input
$request->validate([
'name' => 'required|string|max:255',
'email' => 'required|email|unique:users,email',
'password' => 'required|min:6'
]);

// Simpan user ke database
User::create([
'name' => $request->name,
'email' => $request->email,
'password' => Hash::make($request->password)
]);

return redirect('/user')->with('success', 'User berhasil ditambahkan!');
}
}
