@extends('app')

@section('pageTitle', 'Prodi')
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
                            <h1 class="m-0 text-dark">Prodi</h1>
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
                                    <h3 class="card-title">Manage Your Prodi</h3>
                                    <div class="ml-auto">
                                        <button type="button" data-toggle="modal" data-target="#modal-add"
                                                class="btn btn-primary"><i class="fas fa-plus"></i> Tambah
                                        </button>
                                    </div>
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
                                    <!-- datatable -->
                                    <div class="table-responsive">
                                        <table id="prodi-table" class="table table-striped w-100">
                                            <thead>
                                            <th>Kode Prodi</th>
                                            <th>Nama Prodi</th>
                                            <th>Pagu Per Mahasiswa</th>
                                            <th>Kaprodi/Kajur</th>
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
        @include('prodi._add')
        @include('prodi._edit')
        @include('prodi._kaprodi')
        @include('templates.footer')
    </div>
    </body>
@endsection
@section('script')
    @include('prodi._script')
@endsection
