<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Chatpayment</title>
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
    <h2>Data Chatpayment</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Pasien</th>
                <th>No Telepon</th>
                <th>Dokter</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
                <th>Biaya Chat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($chatpayments as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->user->name }}</td>
                    <td>{{ $row->user->no_telepon }}</td>
                    <td>{{ $row->dokter->user->name }}</td>
                    <td>{{ $row->waktu_mulai }}</td>
                    <td>{{ $row->waktu_selesai }}</td>
                    <td>{{ $row->biaya_chat }}</td>
                    <td>{{ $row->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
