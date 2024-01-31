@extends('app')

@section('pageTitle', 'Realisasi')
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
                            <h1 class="m-0 text-dark">Realisasi</h1>
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
                                    <h3 class="card-title">Manage Your Realization</h3>
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
                                        @if(Auth::user()->role == 'prodi')
                                            <div class="col-12 col-md-3 mt-3 mt-md-0">
                                                <select name="prodi" id="filter-prodi" class="custom-select">
                                                    @foreach($programStudies as $programStudy)
                                                        <option
                                                            value="{{ $programStudy->id }}">{{ ucwords($programStudy->nama_prodi) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
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
                                    </div>
                                    <!-- datatable -->
                                    <div class="table-responsive">
                                        <table id="pengajuan-table" class="table table-striped w-100">
                                            <thead>
                                            <th>Tahun Akademik</th>
                                            <th>Semester</th>
                                            <th>Pagu</th>
                                            <th>Prodi</th>
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
        @include('templates.footer')
    </div>
    </body>
@endsection
@section('script')
    <script !src="">
        let table;

        function initAlert() {
            alertUtil = {
                showFailedAlert: (message) => {
                    $('#failed-message').html(message);
                    $('#failed-alert').show();
                    $("html, body").animate({scrollTop: 0}, 600);
                },
                showFailedAlertAdd: (message) => {
                    $('#failed-message-add').html(message);
                    $('#failed-alert-add').show();
                    $("#modal-add .modal-body").animate({scrollTop: 0}, 600);
                },
                showFailedAlertEdit: (message) => {
                    $('#failed-message-edit').html(message);
                    $('#failed-alert-edit').show();
                    $("#modal-edit .modal-body").animate({scrollTop: 0}, 600);
                },
                showSuccessToast: (message) => {
                    Toast.fire({icon: 'success', title: message})
                }
            }
        }

        function initDataTable() {
            table = $('#pengajuan-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                searching: false,
                order: [[4, 'desc'], [0, 'desc']],
                ajax: {
                    url: '{{ route('realisasi.datatable') }}',
                    data: function (d) {
                        d.tahunAkademik = $('#filter-tahun-akademik').val();
                        d.semester = $('#filter-semester').val();
                        d.prodi = $('#filter-prodi').val();
                    }
                },
                columns: [
                    {data: 'tahun_akademik', responsivePriority: 0},
                    {data: 'semester', responsivePriority: 3},
                    {data: 'pagu'},
                    {data: 'program_studies'},
                    {
                        data: 'created_at', responsivePriority: 1, render: function (data, type, row) {
                            if (type === 'display') {
                                date = new Date(data);
                                return date.toLocaleDateString()
                            }

                            return data
                        }
                    },
                    {data: 'action', width: '120px', responsivePriority: 2, orderable: false, searchable: false},
                ]
            });
        }

        function initAjaxToken() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
        }

        function initFilter() {
            $('#filter-prodi, #filter-semester, #filter-tahun-akademik').change(function () {
                table.ajax.reload();
            });

            $('#filter-tahun-akademik').select2({
                theme: 'bootstrap4',
                width: '100% !important',
                placeholder: 'All Tahun Akademik'
            });

            $('#filter-semester').select2({
                theme: 'bootstrap4',
                width: '100% !important',
                placeholder: 'All Semester'
            });

            $('#filter-prodi').select2({
                theme: 'bootstrap4',
                width: '100% !important',
                placeholder: 'All Prodi'
            });
        }

        $(document).ready(function () {
            initFilter();
            initAjaxToken();
            initAlert();
            initDataTable();
        })
    </script>
@endsection
