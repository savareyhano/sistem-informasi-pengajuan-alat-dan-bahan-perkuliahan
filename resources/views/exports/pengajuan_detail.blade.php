<div class="table-responsive">
    <table id="detail-pengajuan-table" class="table table-striped w-100">
        <thead>
        <tr class="text-bold">
            <th>Nama Barang</th>
            <th>Gambar</th>
            <th>Jumlah Barang</th>
            <th>Harga Satuan</th>
            <th>Harga Total</th>
            <th>Keterangan</th>
        </tr>
        </thead>
        <tbody>
        @php $fmt = new NumberFormatter('id_ID', NumberFormatter::CURRENCY) @endphp
        @foreach($submissionDetail as $detail)
            <tr>
                <td>{{ $detail->nama_barang }}</td>
                <td><img src="{{ 'storage'. str_replace('public', '', $detail->image_path) }}" alt="Gambar Barang"
                         width="250"></td>
                <td>{{ $detail->jumlah }}</td>
                <td>{{ $fmt->format($detail->harga_satuan) }}</td>
                <td>{{ $fmt->format($detail->harga_total) }}</td>
                <td>{{ $detail->keterangan }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<!-- /.datatable -->
