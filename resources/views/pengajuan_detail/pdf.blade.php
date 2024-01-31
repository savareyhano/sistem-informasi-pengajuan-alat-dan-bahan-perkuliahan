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
		<h5>Laporan Detail Pengajuan Prodi {{$pengajuan->programStudies->implode('nama_prodi', ', ')}}</h4>
        <br>
        <br>
	</center>

    <p>Pengajuan {{ $pengajuan->tahun_akademik . ' Semester ' . $pengajuan->semester }}</p>
    <p>{{ $pengajuan->siswa . ' Siswa' }}</p>
    @php $fmt = new NumberFormatter('id_ID', NumberFormatter::CURRENCY) @endphp
    <p>{{  $fmt->format($pengajuan->pagu) }}</p>
    @endforeach
 
	<table id="detail-pengajuan-table" class="table table-striped w-100" border="1px" style="table-layout:fixed;">
        <thead>
        <tr class="text-bold">
            <th style="width:5%; text-align:center;">No</th>
            <th>Nama Barang</th>
            <th style="width:20%">Gambar</th>
            <th>Jumlah Barang</th>
            <th style="width:15%">Harga Satuan</th>
            <th style="width:15%">Harga Total</th>
            <th style="width:20%">Keterangan</th>
        </tr>
        </thead>
        <tbody>
        @php $fmt = new NumberFormatter('id_ID', NumberFormatter::CURRENCY) @endphp
        @foreach($submissionDetail as $detail)
            <tr>
                <td style="word-wrap: break-word; font-size:9px;">{{ $loop->iteration }}</td>
                <td style="word-wrap: break-word;">{{ $detail->nama_barang }}</td>
                <td><img src="{{ 'storage'. str_replace('public', '', $detail->image_path) }}" alt="Gambar Barang"
                         width="100%"></td>
                <td style="word-wrap: break-word;">{{ $detail->jumlah }}</td>
                <td style="word-wrap: break-word;">{{ $fmt->format($detail->harga_satuan) }}</td>
                <td style="word-wrap: break-word;">{{ $fmt->format($detail->harga_total) }}</td>
                <td style="word-wrap: break-word;">{{ $detail->keterangan }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
 
</body>
</html>