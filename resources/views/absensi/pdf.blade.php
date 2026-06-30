<!DOCTYPE html>
<html>
<head>
    <title>Rekap Kehadiran Anggota MPM</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .header h2 {
            margin: 0 0 5px 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header h3 {
            margin: 0 0 5px 0;
            font-size: 12px;
            color: #666;
        }
        .header p {
            margin: 0;
            font-size: 10px;
            font-style: italic;
        }
        .meta-info {
            margin-bottom: 15px;
            font-size: 11px;
        }
        .meta-info table {
            width: 100%;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table-data th {
            background-color: #4f46e5;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            padding: 8px 5px;
            border: 1px solid #111;
        }
        .table-data td {
            padding: 7px 5px;
            border: 1px solid #111;
            text-align: left;
        }
        .text-center {
            text-align: center !important;
        }
        .text-right {
            text-align: right !important;
        }
        .percentage-col {
            font-weight: bold;
            background-color: #f3f4f6;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 10px;
        }
        .footer-date {
            margin-bottom: 50px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Sistem Absensi Anggota MPM Politeknik Astra</h2>
        <h3>Laporan Rekapitulasi Kehadiran Anggota</h3>
        <p>Politeknik Astra - Kampus Cikarang</p>
    </div>

    <div class="meta-info">
        <table>
            <tr>
                <td style="width: 15%;"><strong>Periode Laporan</strong></td>
                <td style="width: 35%;">: {{ $period }}</td>
                <td style="width: 20%;"><strong>Total Agenda Rapat</strong></td>
                <td>: {{ $total_agendas }} Rapat</td>
            </tr>
            <tr>
                <td><strong>Tanggal Unduh</strong></td>
                <td>: {{ date('d F Y H:i') }} WIB</td>
                <td><strong>Unduh Oleh</strong></td>
                <td>: {{ Auth::user()->username }}</td>
            </tr>
        </table>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th class="text-center" style="width: 30px;">No</th>
                <th style="width: 80px;">NIM</th>
                <th>Nama Anggota</th>
                <th>Jabatan</th>
                <th class="text-center" style="width: 65px;">Hadir (1.00)</th>
                <th class="text-center" style="width: 65px;">Izin (0.00)</th>
                <th class="text-center" style="width: 65px;">Sakit (0.00)</th>
                <th class="text-center" style="width: 85px;">Shift 2 Sebagian (0.50)</th>
                <th class="text-center" style="width: 80px;">Shift 2 Absen (0.00)</th>
                <th class="text-center" style="width: 80px;">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $m)
                <tr>
                    <td class="text-center">{{ $m['no'] }}</td>
                    <td class="text-center"><code>{{ $m['nim'] }}</code></td>
                    <td><strong>{{ $m['nama'] }}</strong></td>
                    <td>{{ $m['jabatan'] }}</td>
                    <td class="text-center">{{ $m['hadir'] }}</td>
                    <td class="text-center">{{ $m['izin'] }}</td>
                    <td class="text-center">{{ $m['sakit'] }}</td>
                    <td class="text-center">{{ $m['shift_2_hadir'] }}</td>
                    <td class="text-center">{{ $m['shift_2_absen'] }}</td>
                    <td class="text-center percentage-col">{{ $m['percentage'] }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-date">Cikarang, {{ date('d F Y') }}</div>
        <div>_______________________</div>
        <div style="margin-top: 5px; font-weight: bold;">Sekretaris MPM</div>
    </div>

</body>
</html>
