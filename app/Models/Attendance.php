<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances'; // Nama tabel

    protected $fillable = ['user_id', 'timestamp'];

    public $timestamps = false; // Matikan timestamps default Laravel

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}