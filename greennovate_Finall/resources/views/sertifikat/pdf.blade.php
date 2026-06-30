<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat Penanaman Pohon</title>
    <style>
        @page {
            margin: 0px;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f0fdf4; /* green-50 */
            color: #111827; /* gray-900 */
        }
        .container {
            width: 100%;
            height: 100%;
            position: relative;
            text-align: center;
            border: 20px solid #166534; /* green-800 */
            box-sizing: border-box;
            background-color: white;
            background-image: radial-gradient(#dcfce7 1px, transparent 1px);
            background-size: 20px 20px;
        }
        .inner-border {
            border: 2px solid #22c55e; /* green-500 */
            margin: 10px;
            height: calc(100% - 24px);
            box-sizing: border-box;
            position: relative;
            padding: 40px;
        }
        .header {
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #15803d; /* green-700 */
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .title {
            font-size: 48px;
            font-weight: bold;
            color: #14532d; /* green-900 */
            margin: 10px 0;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 18px;
            color: #4b5563; /* gray-600 */
            letter-spacing: 1px;
        }
        .content {
            margin: 40px 0;
        }
        .awarded-to {
            font-size: 16px;
            color: #374151; /* gray-700 */
            margin-bottom: 10px;
        }
        .name {
            font-size: 42px;
            font-weight: bold;
            color: #166534; /* green-800 */
            margin: 10px 0 20px 0;
            font-style: italic;
            border-bottom: 2px solid #22c55e;
            display: inline-block;
            padding-bottom: 5px;
            min-width: 400px;
        }
        .description {
            font-size: 16px;
            color: #4b5563;
            line-height: 1.6;
            margin: 0 auto;
            max-width: 800px;
        }
        .highlight {
            font-weight: bold;
            color: #15803d;
        }
        .stats-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 15px;
            margin: 30px auto;
            max-width: 600px;
        }
        .stats-table {
            width: 100%;
        }
        .stats-table td {
            width: 50%;
            text-align: center;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #15803d;
        }
        .stat-label {
            font-size: 12px;
            color: #4b5563;
            text-transform: uppercase;
        }
        .footer {
            position: absolute;
            bottom: 40px;
            left: 40px;
            right: 40px;
            display: table;
            width: calc(100% - 80px);
        }
        .footer-left {
            display: table-cell;
            text-align: left;
            vertical-align: bottom;
            width: 33%;
        }
        .footer-center {
            display: table-cell;
            text-align: center;
            vertical-align: bottom;
            width: 33%;
        }
        .footer-right {
            display: table-cell;
            text-align: right;
            vertical-align: bottom;
            width: 33%;
        }
        .cert-number {
            font-size: 12px;
            color: #6b7280;
            font-family: monospace;
        }
        .signature-line {
            border-bottom: 1px solid #111827;
            width: 200px;
            margin-bottom: 5px;
            display: inline-block;
        }
        .signature-name {
            font-weight: bold;
            font-size: 14px;
        }
        .signature-title {
            font-size: 12px;
            color: #4b5563;
        }
        .badge {
            width: 100px;
            height: 100px;
            background-color: #22c55e;
            border-radius: 50%;
            display: inline-block;
            line-height: 100px;
            color: white;
            font-weight: bold;
            font-size: 20px;
            border: 4px solid #14532d;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="inner-border">
            <div class="header">
                <div class="logo">GREENNOVATE</div>
            </div>

            <div class="title">Sertifikat Penghargaan</div>
            <div class="subtitle">Penanaman Pohon & Penghijauan Bumi</div>

            <div class="content">
                <div class="awarded-to">Diberikan dengan bangga kepada:</div>
                <div class="name">{{ $nama_penyumbang }}</div>
                
                <div class="description">
                    Atas kontribusi dan kepedulian yang luar biasa terhadap pelestarian lingkungan melalui partisipasi dalam program <span class="highlight">{{ $nama_kegiatan }}</span> yang berlokasi di <span class="highlight">{{ $lokasi }}</span> pada tanggal <span class="highlight">{{ \Carbon\Carbon::parse($tanggal_penanaman)->translatedFormat('d F Y') }}</span>.
                </div>

                <div class="stats-box">
                    <table class="stats-table">
                        <tr>
                            <td>
                                <div class="stat-value">{{ $jumlah_pohon }} Pohon</div>
                                <div class="stat-label">Telah Ditanam</div>
                            </td>
                            <td>
                                <div class="stat-value">{{ number_format($o2_trx, 2) }} kg/bulan</div>
                                <div class="stat-label">Kontribusi Oksigen (O2)</div>
                            </td>
                        </tr>
                    </table>
                </div>
                
                @if($total_o2_user > 0)
                <div style="font-size: 12px; color: #6b7280; margin-top: -10px;">
                    Total akumulasi oksigen Anda sejauh ini: <strong>{{ number_format($total_o2_user, 2) }} kg/bulan</strong>
                </div>
                @endif
            </div>

            <div class="footer">
                <div class="footer-left">
                    <div class="cert-number">No: {{ $nomor_sertifikat }}</div>
                    <div class="cert-number" style="margin-top: 5px;">Tanggal Terbit: {{ $tanggal_terbit }}</div>
                </div>
                <div class="footer-center">
                    <div class="badge">O2 HERO</div>
                </div>
                <div class="footer-right">
                    <div class="signature-line"></div>
                    <div class="signature-name">Direktur Greennovate</div>
                    <div class="signature-title">Official Green Initiative</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
