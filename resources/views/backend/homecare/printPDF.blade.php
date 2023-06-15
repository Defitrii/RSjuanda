<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Homecare</title>
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
    <h2>Data Homecare</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Homecare</th>
                <th>Nama</th>
                <th>Jenis bayar</th>
                <th>Kategori</th>
                <th>Poli</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($homecare as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->kode_homecare }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->bayar->name }}</td>
                    <td>{{ $row->kategori->name }}</td>
                    <td>{{ $row->poli->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
