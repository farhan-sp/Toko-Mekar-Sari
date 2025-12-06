<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $transaksi->id_transaksi_penjualan }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace; /* Font monospace agar rapi seperti struk */
            font-size: 12px;
            margin: 0;
            padding: 10px;
            width: 80mm; /* Lebar standar kertas struk thermal */
        }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 18px; margin: 0; font-weight: bold; }
        .header p { margin: 2px 0; font-size: 10px; }
        
        .divider { border-top: 1px dashed #000; margin: 10px 0; }
        
        .info-table { width: 100%; font-size: 10px; margin-bottom: 10px; }
        .info-table td { padding: 2px 0; }
        .text-right { text-align: right; }
        
        .item-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .item-table th { text-align: left; font-size: 10px; border-bottom: 1px solid #000; padding-bottom: 5px; }
        .item-table td { padding: 5px 0; vertical-align: top; }
        
        .total-section { width: 100%; font-weight: bold; font-size: 12px; }
        
        .footer { text-align: center; margin-top: 20px; font-size: 10px; }

        @media print {
            body { width: 100%; margin: 0; padding: 0; }
            .no-print { display: none; } /* Sembunyikan tombol saat dicetak */
        }
    </style>
</head>
<body onload="window.print()"> <div class="header">
        <h1>TOKO MEKAR SARI</h1>
        <p>Jl. Raya Material No. 123, Kota Malang</p>
        <p>Telp: 0812-3456-7890</p>
    </div>

    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td>No. Struk</td>
            <td class="text-right">#{{ $transaksi->id_transaksi_penjualan }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td class="text-right">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi_penjualan)->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td>Kasir</td>
            <td class="text-right">{{ optional($transaksi->pengguna)->nama_pengguna ?? 'Admin' }}</td>
        </tr>
        <tr>
            <td>Pelanggan</td>
            <td class="text-right">{{ optional($transaksi->pelanggan)->nama_pelanggan ?? 'Umum' }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="item-table">
        <thead>
            <tr>
                <th style="width: 45%;">Item</th>
                <th style="width: 15%; text-align: center;">Qty</th>
                <th style="width: 40%; text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi->detailPenjualan as $detail)
            <tr>
                <td>{{ optional($detail->barang)->nama_barang ?? 'Item Terhapus' }}</td>
                <td style="text-align: center;">{{ $detail->jumlah_barang }}</td>
                <td class="text-right">Rp {{ number_format($detail->harga_perbarang * $detail->jumlah_barang, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <table class="total-section">
        <tr>
            <td>Total Bayar</td>
            <td class="text-right">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
        </tr>
        {{-- Jika ada Tunai/Kembalian bisa ditambahkan disini --}}
    </table>

    <div class="footer">
        <p>Terima Kasih atas kunjungan Anda!</p>
        <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.history.back()" style="padding: 10px 20px; cursor: pointer;">&larr; Kembali</button>
    </div>

</body>
</html>