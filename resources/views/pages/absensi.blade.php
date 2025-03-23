@extends('layouts.master')
@section('title', 'Absensi Wajah')
@section('content')
    <!-- Navbar -->

    <div class="container mt-5">
        <h2 class="text-center mb-4">📷 Absensi Wajah</h2>

        <div class="card p-4 shadow">
            <!-- Live Camera Preview -->
            <div class="text-center">
                <video id="video" autoplay></video>
            </div>

            <!-- Tombol Scan -->
            <div class="text-center mt-3">
                <button id="scanButton" class="btn btn-primary">🔍 Scan Wajah</button>
            </div>

            <canvas id="canvas"></canvas>
        </div>

        <!-- Daftar Absensi -->
        <div class="card mt-4 p-4 shadow">
            <h4 class="text-center">📋 Daftar Absensi</h4>
            <ul id="absensiList" class="list-group mt-3"></ul>
        </div>
    </div>

    <script>
        // Mengaktifkan kamera
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const scanButton = document.getElementById('scanButton');
        const absensiList = document.getElementById('absensiList');

        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => console.error("Gagal mengakses kamera:", err));

        // Saat tombol scan ditekan
        scanButton.addEventListener('click', async () => {
            const userId = prompt("Masukkan User ID:"); // Sementara pakai input manual

            if (!userId) {
                alert("⚠️ User ID diperlukan!");
                return;
            }

            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = canvas.toDataURL('image/png'); // Konversi ke base64

            // Kirim ke backend Laravel
            const response = await fetch('/api/absensi-wajah', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    user_id: userId,
                    image: imageData
                })
            });

            const result = await response.json();

            if (response.status === 404) {
                alert('⚠️ User ID tidak ditemukan! Cek kembali ID Anda.');
                return;
            }

            if (result.success) {
                alert('✅ Absensi berhasil!');
                loadAbsensi(); // Update daftar absensi
            } else {
                alert('⚠️ ' + result.message);
            }
        });


        // Memuat daftar absensi dari backend
        async function loadAbsensi() {
            const response = await fetch('/api/absensi');
            const data = await response.json();
            absensiList.innerHTML = "";
            data.forEach(item => {
                absensiList.innerHTML += `<li class="list-group-item">🕒 ${item.timestamp} - ${item.nama}</li>`;
            });
        }

        loadAbsensi(); // Load saat halaman dibuka
    </script>

@endsection
