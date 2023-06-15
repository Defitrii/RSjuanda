<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Transaksi paket Homecare</title>
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
    <h2>Data Transaksi paket Homecare</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Pasien</th>
                <th>Perawat</th>
                <th>Dokter</th>
                <th>Layanan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksiHomecare as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->pasien->name }}</td>
                    <td>{{ $row->perawat->name }}</td>
                    <td>{{ $row->dokter->name }}</td>
                    <td>{{ $row->homecare->name }}</td>
                    @if ($row->status == 0)
                        <td>Aktif</td>
                    @elseif($row->status == 1)
                        <td>Pending</td>
                    @else
                        <td>Tidak Aktif</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
