@extends('app')

@section('pageTitle', 'Pengajuan')
@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('style')
    <style>
        .select2-search.select2-search--inline {
            width: 100% !important;
        }
    </style>
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
                            <h1 class="m-0 text-dark">Pengajuan</h1>
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
                                    <h3 class="card-title">Manage Your Pengajuan</h3>
                                    @if(Auth::user()->role == 'prodi')
                                        <div class="ml-auto">
                                            <button type="button" data-toggle="modal" data-target="#modal-add"
                                                    class="btn btn-primary" id="btn-tambah"><i class="fas fa-plus"></i>
                                                Tambah
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <!-- /.card-header -->
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
                                    <div class="row mb-3">
                                        <div class="col-12 col-md-3">
                                            <select name="tahun_akademik" id="filter-tahun-akademik"
                                                    class="custom-select">
                                                <option value="">All Tahun Akademik</option>
                                                <option value="0">All Tahun Akademik</option>
                                                @foreach($academicYears as $academicYear)
                                                    <option
                                                        value="{{ $academicYear->tahun_akademik }}">{{ $academicYear->tahun_akademik }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-3 mt-3 mt-md-0">
                                            <select name="semester" id="filter-semester" class="custom-select">
                                                <option value="">All Semester</option>
                                                <option value="0">All Semester</option>
                                                @foreach($semesters as $semester)
                                                    <option
                                                        value="{{ $semester->semester }}">{{ $semester->semester }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if(Auth::user()->role != 'prodi')
                                            <div class="col-12 col-md-3 mt-3 mt-md-0">
                                                <select name="prodi" id="filter-prodi" class="custom-select">
                                                    <option value="">All Prodi</option>
                                                    <option value="0">All Prodi</option>
                                                    @foreach($programStudies as $programStudy)
                                                        <option
                                                            value="{{ $programStudy->id }}">{{ ucwords($programStudy->nama_prodi) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                        <div class="col-12 col-md-3 mt-3 mt-md-0">
                                            <select name="status" id="filter-status" class="custom-select">
                                                <option value="">All Status</option>
                                                <option value="0">All Status</option>
                                                <option value="1">Pengajuan</option>
                                                <option value="2">Negosiasi</option>
                                                <option value="4">Realisasi</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- datatable -->
                                    <div class="table-responsive">
                                        <table id="pengajuan-table" class="table table-striped w-100">
                                            <thead>
                                            <th>Tahun Akademik</th>
                                            <th>Semester</th>
                                            <th>Pagu</th>
                                            <th>Prodi</th>
                                            <th>Status</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
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
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        @include('pengajuan._add')
        @include('pengajuan._edit')
        @include('templates.footer')
    </div>
    </body>
@endsection
@section('script')
    @include('pengajuan._script')
@endsection
