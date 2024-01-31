@extends('app')

@section('pageTitle', 'Detail Pengajuan')
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
                            <h1 class="m-0 text-dark">Detail Pengajuan</h1>
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
                                        Pengajuan {{ $pengajuan->tahun_akademik . ' Semester ' . $pengajuan->semester }}</h3>
                                        <div class="ml-auto">
                                            @if(Auth::user()->role == 'prodi')
                                            @if(Auth::user()->role == 'prodi' && ($pengajuan->status == 1 || $pengajuan->status == 3 ))
                                                <button type="button" data-toggle="modal" data-target="#modal-add"
                                                        class="btn btn-primary"><i class="fas fa-plus"></i> Tambah
                                                </button>
                                                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-file-import"></i> Import</a>
                                            @endif
                                                <a href="{{ route('pengajuan.detail.export', ['id' => $pengajuan]) }}" class="btn btn-primary"><i class="fas fa-file-export"></i> Export</a>
                                            @endif
                                                <a href="{{ route('pengajuan.detail.exportexcel', ['id' => $pengajuan]) }}" class="btn btn-primary"><i class="fas fa-download"></i> Download Excel</a>
                                                <a href="{{ route('pengajuan.detail.exportpdf', ['id' => $pengajuan]) }}" target="_blank" class="btn btn-primary"><i class="fas fa-eye"></i> View PDF</a>
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
                                    @php $fmt = new NumberFormatter('id_ID', NumberFormatter::CURRENCY) @endphp
                                    <p>{{  $fmt->format($pengajuan->pagu) }}</p>
                                    <!-- datatable -->
                                    <div class="table-responsive">
                                        <table id="detail-pengajuan-table" class="table table-striped w-100">
                                            <thead>
                                            <th>Nama Barang</th>
                                            <th>Gambar</th>
                                            <th>Jumlah Barang</th>
                                            <th>Harga Satuan</th>
                                            <th>Harga Total</th>
                                            <th>Keterangan</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <!-- /.datatable -->
                                </div>
                                <!-- /.card-body -->
                            @if(Auth::user()->role == 'prodi' && ($pengajuan->status == 1 || $pengajuan->status == 3))
                                <!-- card-footer -->
                                    <div class="card-footer">
                                        <a href="{{ route('pengajuan.detail.negosiasi', ['id' => $pengajuan]) }}"
                                           title="Lanjut ke negosiasi" class="btn btn-primary float-right">Lanjut Ke
                                            Negosiasi</a>
                                    </div>
                                    <!-- /.card-footer -->
                            @elseif(Auth::user()->role != 'prodi' && $pengajuan->status == 2)
                                <!-- card-footer -->
                                    <div class="card-footer">
                                        <div class="float-right">
                                            <a href="{{ route('pengajuan.detail.pengajuan', ['id' => $pengajuan]) }}"
                                               title="Kembali ke pengajuan" class="btn btn-primary">Kembali Ke
                                                Pengajuan</a>
                                            <a href="{{ route('pengajuan.detail.realisasi', ['id' => $pengajuan]) }}"
                                               title="Lanjut ke realisasi" class="btn btn-primary">Lanjut Ke
                                                Realisasi</a>
                                        </div>
                                    </div>
                                    <!-- /.card-footer -->
                                @endif
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
                                    <form action="{{ route('pengajuan.detail.import', ['id' => $pengajuan]) }}" method="post" enctype="multipart/form-data">

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
        @if(Auth::user()->role == 'prodi' && ($pengajuan->status == 1 || $pengajuan->status == 3))
            @include('pengajuan_detail._add')
            @include('pengajuan_detail._edit')
        @elseif(Auth::user()->role != 'prodi' && $pengajuan->status == 2)
            @include('pengajuan_detail._status')
        @endif
        @include('templates.footer')
    </div>
    </body>
@endsection
@section('script')
    @include('pengajuan_detail._script')
@endsection
