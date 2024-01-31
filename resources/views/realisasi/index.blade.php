@extends('app')

@section('pageTitle', 'Detail Realisasi')
@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('body')
    <body class="hold-transition sidebar-mini">
    <div class="wrapper">
    @include('templates.navbar')

    @include('templates.sidebar')

    <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Detail Realisasi</h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <!-- card-header -->
                                <div class="card-header d-flex align-items-center">
                                    <h3 class="card-title">
                                        Realisasi {{ $pengajuan->tahun_akademik . ' Semester ' . $pengajuan->semester }}</h3>
                                    <div class="ml-auto">
                                        @if(Auth::user()->role == 'prodi')
                                        @if(Auth::user()->role == 'prodi' && $pengajuan->status == 4)
                                            <button type="button" data-toggle="modal" data-target="#modal-add"
                                                    class="btn btn-primary" id="btn-tambah"><i class="fas fa-plus"></i>
                                                Tambah
                                            </button>
                                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-file-import"></i> Import</a>
                                        @endif
                                            <a href="{{ route('realisasi.detail.export', ['id' => $pengajuan]) }}" class="btn btn-primary"><i class="fas fa-file-export"></i> Export</a>
                                        @endif
                                        <a href="{{ route('realisasi.detail.exportexcel', ['id' => $pengajuan]) }}"
                                           class="btn btn-primary">
                                            <i class="fas fa-download"></i> Download Excel
                                        </a>
                                        <a href="{{ route('realisasi.detail.exportpdf', ['id' => $pengajuan]) }}" target="_blank" class="btn btn-primary"><i class="fas fa-eye"></i> View PDF</a>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <!-- card-body -->
                                <div class="card-body">
                                    <!-- failed-alert -->
                                    <div class="alert alert-danger alert-dismissible fade show" id="failed-alert"
                                         role="alert" style="display: none">
                                        <span id="failed-message"></span>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <!-- /.failed-alert -->
                                    <p>{{ $pengajuan->siswa . ' Siswa' }}</p>
                                    @php
                                        $fmt = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
                                        $sum = $pengajuan->realizations()->sum('harga_total');
                                        $percent = ($sum / $pengajuan->pagu) * 100
                                    @endphp
                                    <p>{{ $fmt->format($pengajuan->pagu) }}</p>
                                    <!-- datatable -->
                                    <div class="table-responsive">
                                        <table id="detail-pengajuan-table" class="table table-striped w-100">
                                            <thead>
                                            <tr>
                                                <th rowspan="2">Nama Barang</th>
                                                <th rowspan="2">Gambar</th>
                                                <th colspan="2" class="text-center">Jumlah Barang</th>
                                                <th colspan="2" class="text-center">Harga Total</th>
                                                <th rowspan="2">Terealisasi (%)</th>
                                                <th rowspan="2">Keterangan</th>
                                                <th rowspan="2">Action</th>
                                            </tr>
                                            <tr>
                                                <th>Pengajuan</th>
                                                <th>Realisasi</th>
                                                <th>Pengajuan</th>
                                                <th>Realisasi</th>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <!-- /.datatable -->
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Import Data</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('realisasi.detail.import', ['id' => $pengajuan]) }}" method="post" enctype="multipart/form-data">

                                    <div class="modal-body">
                                        <div class="form-group">

                                        
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <input type="file" name="file" required="required" accept=".xlsx">

                                            </div>
                                        
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Import</button>
                                    </div>
                                    </div>
                                    </form>
                                    
                                </div>
                                </div>

            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        @if(Auth::user()->role == 'prodi' && $pengajuan->status == 4)
            @include('realisasi._add')
            @include('realisasi._edit')
        @endif
        @include('templates.footer')
    </div>
    </body>
@endsection
@section('script')
    @include('realisasi._script')
@endsection
