<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Poli</title>
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
    <h2>Data Poli</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Poli</th>
                <th>Nama Poli</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($poli as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->kode_poli }}</td>
                    <td>{{ $row->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
