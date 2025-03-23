<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunAttendanceTest extends Command
{
    /**
     * Nama dan signature dari command.
     *
     * @var string
     */
    protected $signature = 'test:attendance';

    /**
     * Deskripsi command.
     *
     * @var string
     */
    protected $description = 'Menjalankan AttendanceTest.php untuk menguji fitur absensi';

    /**
     * Jalankan perintah ini.
     */
    public function handle()
    {
        $this->info("Menjalankan AttendanceTest...");
        $exitCode = shell_exec('php artisan test --filter=AttendanceTest');

        // Tampilkan output hasil test
        $this->info($exitCode);
    }
}