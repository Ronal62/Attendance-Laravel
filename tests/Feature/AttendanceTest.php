<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase; // Untuk mereset database setiap kali test dijalankan

    /**
     * Test apakah endpoint GET /api/absensi berfungsi dengan benar.
     */
    public function test_get_attendance_list()
    {
        // Buat user dan absensi dummy
        $user = User::factory()->create();
        Attendance::factory()->create(['user_id' => $user->id]);

        // Panggil endpoint API
        $response = $this->getJson('/api/absensi');

        // Pastikan response sukses dan memiliki data
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id', 'user_id', 'timestamp', 'user']
                 ]);
    }

    /**
     * Test apakah endpoint POST /api/absensi-wajah dapat menyimpan absensi dengan benar.
     */
    public function test_store_attendance()
    {
        // Buat user dummy
        $user = User::factory()->create();

        // Simulasi request absensi
        $response = $this->postJson('/api/absensi-wajah', [
            'user_id' => $user->id,
            'image' => base64_encode('dummy_image_data')
        ]);

        // Pastikan response sukses
        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Absensi berhasil!']);

        // Pastikan data tersimpan di database
        $this->assertDatabaseHas('attendances', ['user_id' => $user->id]);
    }

    /**
     * Test validasi saat User ID tidak ditemukan.
     */
    public function test_store_attendance_with_invalid_user()
    {
        // Simulasi request dengan user_id yang tidak ada
        $response = $this->postJson('/api/absensi-wajah', [
            'user_id' => 9999, // ID tidak ada di database
            'image' => base64_encode('dummy_image_data')
        ]);

        // Pastikan response error
        $response->assertStatus(422);
    }
}
