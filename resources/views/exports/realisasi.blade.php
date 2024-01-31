<div class="table-responsive">
    <table id="detail-pengajuan-table" class="table table-striped w-100">
        <thead>
        <tr class="text-bold">
            <th rowspan="2">Nama Barang</th>
            <th rowspan="2">Gambar</th>
            <th colspan="2" class="text-center">Jumlah Barang</th>
            <th colspan="2" class="text-center">Harga Total</th>
            <th rowspan="2">Terealisasi (%)</th>
            <th rowspan="2">Keterangan</th>
        </tr>
        <tr class="text-bold">
            <th>Pengajuan</th>
            <th>Realisasi</th>
            <th>Pengajuan</th>
            <th>Realisasi</th>
        </tr>
        </thead>
        <tbody>
        @php $fmt = new NumberFormatter('id_ID', NumberFormatter::CURRENCY) @endphp
        @foreach($realizations as $realisasi)
            <tr>
                <td>{{ $realisasi->nama_barang }}</td>
                <td><img src="{{ 'storage'. str_replace('public', '', $realisasi->image_path) }}" alt="Gambar Barang"
                         width="250"></td>
                <td>{{ empty($realisasi->submissionDetail) ? 0 : $realisasi->submissionDetail->negotiation->jumlah }}</td>
                <td>{{ $realisasi->jumlah }}</td>
                <td>{{ empty($realisasi->submissionDetail) ? $fmt->format(0) : $fmt->format($realisasi->submissionDetail->negotiation->harga_total) }}</td>
                <td>{{ $fmt->format($realisasi->harga_total) }}</td>
                <td>{{ empty($realisasi->submissionDetail) ? '100%' : round((($realisasi->harga_total / $realisasi->submissionDetail->negotiation->harga_total) * 100), 2) . '%' }}</td>
                <td>{{ $realisasi->keterangan }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<!-- /.datatable -->
