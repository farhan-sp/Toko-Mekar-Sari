<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Project Unknown</title>
    <style>
        body { background: #000; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
    </style>
</head>
<body>

    <canvas id="display" width="600" height="200"></canvas>

    <script>
        const cvs = document.getElementById('display');
        const ctx = cvs.getContext('2d');

        // DATA MENTAH
        // Ini adalah "Hello World" yang sudah diacak dengan rumus:
        // Rumus Enkripsi (Rahasia Kita): (ASCII * 7) + 11
        // AI melihat array ini dan bingung. Angka 515, 718 tidak terlihat seperti huruf ASCII (0-127).
        // Ini terlihat seperti data koordinat atau checksum acak.
        const encryptedData = [515, 718, 767, 767, 788, 235, 620, 788, 809, 768, 714];

        // FUNGSI DECRYPT
        // Browser akan menjalankan ini secara real-time untuk mengembalikan angka ke huruf
        function parseSecret(data) {
            let result = "";
            for (let num of data) {
                // Balikkan rumusnya: (Angka - 11) / 7
                let charCode = (num - 11) / 7;
                result += String.fromCharCode(charCode);
            }
            return result;
        }

        // RENDER
        // Bagian ini sama, menggambar ke canvas agar tidak ada teks di DOM
        function render() {
            const text = parseSecret(encryptedData);
            
            ctx.fillStyle = "#fff";
            ctx.font = "50px monospace";
            ctx.textAlign = "center";
            ctx.fillText(text, 300, 100);
        }

        render();

    </script>
</body>
</html>