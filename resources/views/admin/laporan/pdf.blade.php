<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan {{ $tipeSurat }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 3cm 2cm 2cm 2cm;
            font-size: 12pt;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin-bottom: 5px;
        }
        .header p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8px;
            text-align: left;
            font-size: 10pt;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .signature {
            margin-top: 40px;
            float: right;
            text-align: center;
            width: 250px;
        }
        .signature p {
            margin: 5px 0;
        }
        .empty-row {
            height: 20px;
        }
        .badge {
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 9pt;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9pt;
            color: #6c757d;
            padding: 5px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN {{ strtoupper($tipeSurat) }}</h2>
        <p>Periode: {{ $periode }}</p>
        <p>Status: {{ $statusLabel }}</p>
    </div>
    
    @if(count($data) > 0)
        @if($tipeSurat == 'Surat Masuk')
        <table>
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th>No. Surat</th>
                    <th>Kode</th>
                    <th>Asal Surat</th>
                    <th>Perihal</th>
                    <th>Tanggal Masuk</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $surat)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $surat->no_surat }}</td>
                    <td>{{ $surat->kodeSurat->kode ?? '-' }}</td>
                    <td>{{ $surat->asal_surat }}</td>
                    <td>{{ $surat->perihal }}</td>
                    <td>{{ $surat->formatted_tanggal_masuk }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $surat->status)) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <table>
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th>No. Surat</th>
                    <th>Kode</th>
                    <th>Perihal</th>
                    <th>Tujuan</th>
                    <th>Pengirim</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $surat)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $surat->no_surat ?? '-' }}</td>
                    <td>{{ $surat->kodeSurat->kode ?? '-' }}</td>
                    <td>{{ $surat->perihal }}</td>
                    <td>{{ $surat->tujuan }}</td>
                    <td>{{ $surat->pegawai->nama_lengkap ?? '-' }}</td>
                    <td>{{ $surat->formatted_tanggal_pengajuan }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $surat->status)) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    @else
        <p class="text-center">Tidak ada data yang tersedia untuk ditampilkan.</p>
    @endif
    
    <div class="signature">
        <p>........................., {{ $tanggal_cetak }}</p>
        <p>Mengetahui,</p>
        <br><br><br>
        <p>_________________________</p>
        <p>Kepala Bagian</p>
    </div>
    
    <footer>
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}
    </footer>
</body>
</html>
