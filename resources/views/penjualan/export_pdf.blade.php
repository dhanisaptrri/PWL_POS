<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body{
            font-family: "Times New Roman", Times, serif;
            margin: 6px 20px 5px 20px;
            line-height: 15px;
        }
        table {
            width:100%;
            border-collapse: collapse;
        }
        td, th {
            padding: 4px 3px;
        }
        th{
            text-align: left;
        }
        .d-block{
            display: block;
        }
        img.image{
            width: auto;
            height: 80px;
            max-width: 150px;
            max-height: 150px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        p-1{
            padding: 5px 1px 5px 1px;
        }
        .font-10{
            font-size: 10pt;
        }
        .font-11{
            font-size: 11pt;
        }
        .font-12{
            font-size: 12pt;
        }
        .font-13{
            font-size: 13pt;
        }
        .border-bottom-header{
            border-bottom: 1px solid;
        }
        .border-all, .border-all th, .border-all td{
            border: 1px solid;
        }
    </style>
</head>
<body>
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center"><img src="{{ asset('polimea-bw.png') }}"/></td>
            <td width="85%" class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</td>
        </tr>
        <tr>
            <td></td>
            <td class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI MALANG</span>
        </tr>
        <tr>
            <td></td>
            <td class="text-center d-block font-10">Jl. Soekarno-Hatta No. 9 Malang 65141</span>
        </tr>
        <tr>
            <td></td>
            <td class="text-center d-block font-10">Telpon (0341) 404424 Pes. 101-105, 0341-404428, Fax. (0341) 404420</span>
        </tr>
        <tr>
            <td></td>
            <td class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
        </tr>
    </table>
    <h1 class="text-center">LAPORAN DATA PENJUALAN</h1>
    <table class="border-all">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Kode Penjualan</th>
                <th class="text-center">Nama Barang</th>
                <th class="text-center">Jumlah</th>
                <th class="text-center">Harga (Rp)</th>
                <th class="text-center">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($penjualan as $index => $p)
            @php $total += $p['total']; @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $p['kode_penjualan'] }}</td>
                <td>{{ $p['nama_barang'] }}</td>
                <td class="text-center">{{ $p['jumlah'] }}</td>
                <td class="text-right">{{ number_format($p['harga'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($p['total'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="5" class="text-right"><strong>Total Keseluruhan:</strong></td>
                <td class="text-right"><strong>{{ number_format($total, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>
</body>
</html>