@extends('app')

@section('pageTitle', 'Dashboard')

@section('body')
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
    @include('templates/navbar')

    @include('templates/sidebar')

    <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Dashboard</h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        @if(Auth::user()->role != 'prodi')
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header border-0">
                                        <h3 class="card-title">Progress Realiasasi Terbaru</h3>
                                        <div class="card-tools">
                                            <a href="{{ route('realisasi') }}" class="btn btn-tool btn-sm">
                                                <i class="fas fa-bars"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body table-responsive p-0">
                                        <table class="table table-striped table-valign-middle w-100">
                                            <thead>
                                            <tr>
                                                <th rowspan="2">Program Study</th>
                                                <th rowspan="2">Tahun Akademik</th>
                                                <th rowspan="2">Semester</th>
                                                <th colspan="2" class="text-center">Terealisasi</th>
                                                <th rowspan="2">Action</th>
                                            </tr>
                                            <tr>
                                                <td>Progress</td>
                                                <td>Label</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($data as $realisasi)
                                                @php
                                                    $terealisasi = round((($realisasi->latestSubmission->total_realisasi ?? 0) / ($realisasi->latestSubmission->pagu ?? 1)) * 100)
                                                @endphp
                                                <tr>
                                                    <td>{{ $realisasi->nama_prodi }}</td>
                                                    <td>{{ $realisasi->latestSubmission->tahun_akademik ?? '-' }}</td>
                                                    <td>{{ $realisasi->latestSubmission->semester ?? '-' }}</td>
                                                    <td>
                                                        <div class="progress bg-secondary">
                                                            <div class="progress-bar bg-info" role="progressbar"
                                                                 aria-valuenow="{{ $terealisasi ?? 0 }}"
                                                                 aria-valuemin="0" aria-valuemax="100"
                                                                 style="width: {{ $terealisasi ?? 0 }}%">
                                                                <div class="sr-only">{{ $terealisasi ?? 0 }}% Complete
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>{{ $terealisasi ?? 0 }}%</div>
                                                    </td>
                                                    <td>
                                                        @if(isset($realisasi->latestSubmission))
                                                            <a href="{{ route('realisasi.detail', ['id' => $realisasi->latestSubmission->id]) }}"
                                                               class="text-muted">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            @if($terealisasi > 0)
                                                            <a href="{{ route('realisasi.detail.exportexcel', ['id' => $realisasi->latestSubmission->id]) }}"
                                                               class="text-muted" target="_blank">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>
                        @else
                            @foreach($data as $prodi)
                                @php
                                    $terealisasi = round((($prodi->latestSubmission->total_realisasi ?? 0) / ($prodi->latestSubmission->pagu ?? 1)) * 100)
                                @endphp
                                <div class="col-12 col-md-6">
                                    <div class="card">
                                        <div class="card-header border-0">
                                            <h3 class="card-title">Progress Realisasi Terakhir
                                                Prodi {{ ucwords($prodi->nama_prodi) }}</h3>
                                            @if(isset($prodi->latestSubmission))
                                                <div class="card-tools">
                                                    @if($terealisasi > 0)
                                                        <a href="{{ route('realisasi.detail.exportexcel', ['id' => $prodi->latestSubmission->id]) }}"
                                                           class="btn btn-sm btn-tool" target="_blank">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('realisasi.detail', ['id' => $prodi->latestSubmission->id]) }}"
                                                       class="btn btn-sm btn-tool">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <div
                                                class="d-flex justify-content-between align-items-center border-bottom mb-3">
                                                <p class="text-success text-xl">
                                                    <i class="ion ion-ios-refresh-empty"></i>
                                                </p>
                                                <p class="d-flex flex-column text-right">
                                                <span class="font-weight-bold">
                                                  {{ $prodi->latestSubmission->total_pengajuan_realisasi ?? 0 }} / {{ $prodi->latestSubmission->total_pengajuan ?? 0 }}
                                                </span>
                                                    <span
                                                        class="text-muted">TOTAL ALAT & BAHAN PENGAJUAN TEREALISASI</span>
                                                </p>
                                            </div>
                                            <!-- /.d-flex -->
                                            <div
                                                class="d-flex justify-content-between align-items-center border-bottom mb-3">
                                                <p class="text-warning text-xl">
                                                    <i class="ion ion-ios-cart-outline"></i>
                                                </p>
                                                <p class="d-flex flex-column text-right">
                                                <span class="font-weight-bold">
                                                  {{ $prodi->latestSubmission->total_realisasi_baru ?? 0 }}
                                                </span>
                                                    <span class="text-muted">TOTAL ALAT & BAHAN TEREALISASI DILUAR PENGAJUAN</span>
                                                </p>
                                            </div>
                                            <!-- /.d-flex -->
                                            <div class="d-flex justify-content-between align-items-center mb-0">
                                                <p class="text-danger text-xl">
                                                    <i class="ion ion-ios-people-outline"></i>
                                                </p>
                                                <p class="d-flex flex-column text-right">
                                                <span class="font-weight-bold">
                                                    {{ $terealisasi }}%
                                                </span>
                                                    <span class="text-muted">PERSENTASI PENGAJUAN TEREALISASI</span>
                                                </p>
                                            </div>
                                            <!-- /.d-flex -->
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        @include('templates/footer')
    </div>
</body>
@endsection
