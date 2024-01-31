<?php

namespace App\Http\Controllers;

use App\Http\Repository\PengajuanRepository;
use App\Http\Repository\ProdiRepository;
use App\Http\Requests\Pengajuan\PengajuanDeleteRequest;
use App\Http\Requests\Pengajuan\PengajuanGetRequest;
use App\Http\Requests\Pengajuan\PengajuanStoreRequest;
use App\Http\Requests\Pengajuan\PengajuanUpdateRequest;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use NumberFormatter;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PengajuanController extends Controller
{
    /**
     * @var PengajuanRepository
     */
    private $pengajuanRepository;

    /**
     * PengajuanController constructor.
     * @param PengajuanRepository $pengajuanRepository
     */
    public function __construct(PengajuanRepository $pengajuanRepository)
    {
        $this->middleware('auth');
        $this->pengajuanRepository = $pengajuanRepository;
    }

    /**
     * @param ProdiRepository $prodiRepository
     * @return Factory|View
     */
    public function index(ProdiRepository $prodiRepository)
    {
        $programStudies = Auth::user()->role == 'prodi' ? $prodiRepository->getProdiByUser() : $prodiRepository->get();
        $academicYears = $this->pengajuanRepository->getAcademicYears();
        $semesters = $this->pengajuanRepository->getSemester();
        return view('pengajuan.index', ['programStudies' => $programStudies, 'academicYears' => $academicYears, 'semesters' => $semesters]);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function datatable(Request $request)
    {
        $submissions = $this->pengajuanRepository->getDataTable($request);
        return DataTables::of($submissions)
            ->addColumn('action', function ($submission) {
                $viewUrl = route('pengajuan.detail', ['id' => $submission]);
                $action = "<a href=\"{$viewUrl}\" class=\"btn btn-primary btn-sm\" target='_blank' title='View Details'\" ><i class=\"fas fa-eye\" ></i ></a >";
                $userRole = Auth::user()->role;

                if ($userRole == 'prodi' && $submission->status == 1) {
                    $action .= "
                        <a href=\"#\" class=\"btn btn-warning btn-sm btn_edit\" title='Edit' data-id=\"{$submission->id}\"><i class=\"fas fa-edit\"></i></a>
                        <a href=\"#\" class=\"btn btn-danger btn-sm btn_delete\" title='Delete' data-id=\"{$submission->id}\"><i class=\"fas fa-trash-alt\"></i></a>
                    ";
                }
                return $action;
            })
            ->editColumn('pagu', function ($submission) {
                $fmt = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
                return $fmt->format($submission->pagu);
            })
            ->editColumn('status', function ($submission) {
                return $submission->status == 1 ? 'Pengajuan' : ($submission->status == 2 ? 'Negosiasi' : ($submission->status == 3 ? 'Pengajuan' : 'Realisasi'));
            })
            ->editColumn('created_at', function ($submission) {
                return $submission->created_at->format('m/d/Y');
            })
            ->editColumn('program_studies', function ($submission) {
                return optional($submission->programStudies)->implode('nama_prodi', ', ');
            })
            ->make(true);
    }

    /**
     * @param PengajuanStoreRequest $request
     * @return JsonResponse
     */
    public function store(PengajuanStoreRequest $request)
    {
        $pengajuan = $this->pengajuanRepository->create($request);
        if ($pengajuan['status'])
            return response()->json(['status' => true, 'message' => 'Success creating pengajuan', 'redirect' => route('pengajuan.detail', ['id' => $pengajuan['data']])]);
        else
            return response()->json(['status' => false, 'message' => $pengajuan['message']], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param PengajuanDeleteRequest $request
     * @return JsonResponse
     */
    public function delete(PengajuanDeleteRequest $request)
    {
        $this->pengajuanRepository->deleteById($request->id);
        return response()->json(['status' => true, 'message' => 'Success deleting pengajuan'], Response::HTTP_OK);
    }

    /**
     * @param PengajuanGetRequest $request
     * @return JsonResponse
     */
    public function get(PengajuanGetRequest $request)
    {
        $pengajuan = $this->pengajuanRepository->getById($request->id);
        return response()->json(['status' => true, 'pengajuan' => $pengajuan], Response::HTTP_OK);
    }

    /**
     * @param PengajuanUpdateRequest $request
     * @return JsonResponse
     */
    public function update(PengajuanUpdateRequest $request)
    {
        $pengajuan = $this->pengajuanRepository->update($request);
        if ($pengajuan['status'])
            return response()->json(['status' => true, 'message' => 'Success updating pengajuan'], Response::HTTP_OK);
        else
            return response()->json(['status' => false, 'message' => $pengajuan['message']], Response::HTTP_BAD_REQUEST);
    }
}
