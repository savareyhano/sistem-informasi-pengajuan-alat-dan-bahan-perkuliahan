<!DOCTYPE html>
<html>
<head>
	<title>{{strtotime('now')}}</title>
	<link rel="stylesheet" href="css/app.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}
	</style>
	<center>
    @foreach($submission as $pengajuan)
		<h5>Laporan Realisasi Prodi {{$pengajuan->programStudies->implode('nama_prodi', ', ')}}</h4>
        <br>
        <br>
	</center>

    <p>Realisasi {{ $pengajuan->tahun_akademik . ' Semester ' . $pengajuan->semester }}</p>
    <p>{{ $pengajuan->siswa . ' Siswa' }}</p>
    @php $fmt = new NumberFormatter('id_ID', NumberFormatter::CURRENCY) @endphp
    <p>{{  $fmt->format($pengajuan->pagu) }}</p>
    @endforeach
 
	<table id="detail-pengajuan-table" class="table table-striped w-100" border="1px" style="table-layout:fixed;">
        <thead>
        <tr class="text-bold">
            <th rowspan="2" style="width:5%; text-align:center;">No</th>
            <th rowspan="2">Nama Barang</th>
            <th rowspan="2" style="width:15%;">Gambar</th>
            <th colspan="2" class="text-center">Jumlah Barang</th>
            <th colspan="2" class="text-center">Harga Total</th>
            <th rowspan="2" style="font-size:11px; text-align:center;">Terealisasi (%)</th>
            <th rowspan="2" style="width:15%;">Keterangan</th>
        </tr>
        <tr class="text-bold">
            <th style="font-size:11px; text-align:center;">Pengajuan</th>
            <th style="font-size:11px; text-align:center;">Realisasi</th>
            <th style="width:15%;">Pengajuan</th>
            <th style="width:15%;">Realisasi</th>
        </tr>
        </thead>
        <tbody>
        @php $fmt = new NumberFormatter('id_ID', NumberFormatter::CURRENCY) @endphp
        @foreach($realizations as $realisasi)
            <tr>
                <td style="word-wrap: break-word; font-size:9px;">{{ $loop->iteration }}</td>
                <td style="word-wrap: break-word;">{{ $realisasi->nama_barang }}</td>
                <td><img src="{{ 'storage'. str_replace('public', '', $realisasi->image_path) }}" alt="Gambar Barang"
                         width="100%"></td>
                <td style="word-wrap: break-word;">{{ empty($realisasi->submissionDetail) ? 0 : $realisasi->submissionDetail->negotiation->jumlah }}</td>
                <td style="word-wrap: break-word;">{{ $realisasi->jumlah }}</td>
                <td style="word-wrap: break-word;">{{ empty($realisasi->submissionDetail) ? $fmt->format(0) : $fmt->format($realisasi->submissionDetail->negotiation->harga_total) }}</td>
                <td style="word-wrap: break-word;">{{ $fmt->format($realisasi->harga_total) }}</td>
                <td style="word-wrap: break-word;">{{ empty($realisasi->submissionDetail) ? '100%' : round((($realisasi->harga_total / $realisasi->submissionDetail->negotiation->harga_total) * 100), 2) . '%' }}</td>
                <td style="word-wrap: break-word;">{{ $realisasi->keterangan }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
 
</body>
</html>