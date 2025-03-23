<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Wajah</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        #video {
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
        }
        #canvas {
            display: none;
        }
    </style>
</head>
<body class="bg-light">
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

        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => console.error("Gagal mengakses kamera:", err));

        // Saat tombol scan ditekan
        scanButton.addEventListener('click', async () => {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = canvas.toDataURL('image/png'); // Konversi ke base64

            // Kirim ke backend Laravel
            const response = await fetch('/api/absensi-wajah', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ image: imageData })
            });

            const result = await response.json();
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
</body>
</html>
