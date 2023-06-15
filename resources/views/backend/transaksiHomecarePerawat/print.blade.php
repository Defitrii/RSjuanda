<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Bukti Transaksi Homecare</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 5px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }

        h2 {
            font-size: 18px;
            margin-top: 0;
        }

        .text-bold {
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            text-align: center;
        }
    </style>
</head>

<body>
    <h2 class="text-center">Bukti Transaksi Homecare</h2>
    <table>
        <tr>
            <th>Tanggal</th>
            <td>{{ $waktu }}</td>
        </tr>
        <tr>
            <th>Nama Pasien</th>
            <td>{{ $data->pasien->name }}</td>
        </tr>
        <tr>
            <th>Nomor HP</th>
            <td>{{ $data->pasien->no_telepon }}</td>
        </tr>
        <tr>
            <th>Perawat</th>
            <td>{{ $data->perawat->name }}</td>
        </tr>
        <tr>
            <th>Layanan</th>
            <td>
                <table>
                    @foreach ($hargaLayanan as $namaLayanan => $harga)
                        <tr>
                            <td>{{ $namaLayanan }}</td>
                            <td class="text-right">Rp {{ number_format($harga, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
        <tr>
            <th>Biaya Tambahan</th>
            <td class="text-right">Rp {{ number_format($data->biaya_tambahan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Total Harga</th>
            <td class="text-right">Rp {{ number_format($data->total_biaya, 0, ',', '.') }}</td>
        </tr>
    </table>
    <div class="footer">
        <p>Terima kasih telah menggunakan layanan kami</p>
    </div>
</body>

</html>
