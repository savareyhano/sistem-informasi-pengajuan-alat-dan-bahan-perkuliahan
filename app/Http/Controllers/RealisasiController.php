<?php

namespace App\Http\Controllers;

use App\Exports\RealizationExport;
use App\Exports\RealizationExportExcel;
use App\Imports\RealizationImport;
use App\Http\Repository\PengajuanRepository;
use App\Http\Repository\ProdiRepository;
use App\Http\Repository\RealisasiRepository;
use App\Http\Requests\Realisasi\RealisasiDeleteRequest;
use App\Http\Requests\Realisasi\RealisasiGetRequest;
use App\Http\Requests\Realisasi\RealisasiStoreRequest;
use App\Http\Requests\Realisasi\RealisasiUpdateRequest;
use App\Negotiation;
use App\Realization;
use App\Submission;
use App\SubmissionDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use NumberFormatter;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use PDF;

class RealisasiController extends Controller
{
    /**
     * @var RealisasiRepository
     */
    private $realisasiRepository;

    public function __construct(RealisasiRepository $realisasiRepository)
    {
        $this->middleware('auth');
        $this->realisasiRepository = $realisasiRepository;
    }

    public function submission(ProdiRepository $prodiRepository, PengajuanRepository $pengajuanRepository)
    {
        $programStudies = Auth::user()->role == 'prodi' ? $prodiRepository->getProdiByUser() : $prodiRepository->get();
        $academicYears = $pengajuanRepository->getAcademicYears();
        $semesters = $pengajuanRepository->getSemester();
        return view('realisasi.submission', ['programStudies' => $programStudies, 'academicYears' => $academicYears, 'semesters' => $semesters]);
    }

    public function datatableSubmission(Request $request)
    {
        $submissions = $this->realisasiRepository->getDataTable($request);
        return DataTables::of($submissions)
            ->addColumn('action', function ($submission) {
                $viewUrl = route('realisasi.detail', ['id' => $submission]);
                return "<a href=\"{$viewUrl}\" class=\"btn btn-primary btn-sm\" target='_blank' title='View Details'\" ><i class=\"fas fa-eye\" ></i ></a >";
            })
            ->editColumn('pagu', function ($submission) {
                $fmt = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
                return $fmt->format($submission->pagu);
            })
            ->editColumn('created_at', function ($submission) {
                return $submission->created_at->format('m/d/Y');
            })
            ->editColumn('program_studies', function ($submission) {
                return optional($submission->programStudies)->implode('nama_prodi', ', ');
            })
            ->make(true);
    }

    public function index(Submission $id)
    {
        return view('realisasi.index', ['pengajuan' => $id]);
    }

    public function datatable(Submission $id)
    {
        $realizations = $this->realisasiRepository->getBySubmission($id);
        return DataTables::of($realizations)
            ->addColumn('realization', function ($realization) {
                return empty($realization->submissionDetail) ? '100%' : round((($realization->harga_total / $realization->submissionDetail->negotiation->harga_total) * 100), 2) . '%';
            })
            ->addColumn('action', function ($realization) use ($id) {
                if (Auth::user()->role == 'prodi' && $id->status == 4) {
                    return "
                        <a href=\"#\" class=\"btn btn-warning btn-sm btn_edit\" title='Edit' data-id=\"{$realization->id}\"><i class=\"fas fa-edit\"></i></a>
                        <a href=\"#\" class=\"btn btn-danger btn-sm btn_delete\" title='Delete' data-id=\"{$realization->id}\"><i class=\"fas fa-trash-alt\"></i></a>
                    ";
                }

                return '';
            })
            ->addColumn('pengajuan_jumlah', function ($realization) {
                return empty($realization->submissionDetail) ? 0 : $realization->submissionDetail->negotiation->jumlah;
            })
            ->addColumn('pengajuan_harga_total', function ($realization) {
                $fmt = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
                return empty($realization->submissionDetail) ? $fmt->format(0) : $fmt->format($realization->submissionDetail->negotiation->harga_total);
            })
            ->editColumn('harga_total', function ($realization) {
                $fmt = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
                return $fmt->format($realization->harga_total);
            })
            ->editColumn('nama_barang', function ($realization) {
                return ucfirst($realization->nama_barang);
            })
            ->editColumn('image_path', function ($realization) {
                $imgUrl = asset('storage' . str_replace('public', '', $realization->image_path));
                return "<img src=\"{$imgUrl}\" alt=\"Gambar Barang\" width=\"250\" />";
            })
            ->rawColumns(['image_path', 'action'])
            ->make(true);
    }

    public function getBarang(Request $request, $id)
    {
        $barang = SubmissionDetail::whereHas('negotiation', function (Builder $query) {
            $query->where('jumlah', '>', 0);
        })
            ->doesntHave('realization')
            ->where('submission_id', $id)
            ->select(['id', 'nama_barang as text']);
        if (!empty($request->search)) $barang->where('text', 'like', "%{$request->search}%");
        $barang = $barang->get();
        return response()->json(['results' => $barang]);
    }

    public function getItem(Request $request, $id)
    {
        $barang = Negotiation::where('submission_detail_id', $request->id)->first();
        if (!empty($barang)) {
            return response()->json(['status' => true, 'data' => $barang]);
        } else {
            return response()->json(['status' => false, 'message' => 'Data not found']);
        }
    }

    public function store(RealisasiStoreRequest $request, Submission $id)
    {
        $realisasi = $this->realisasiRepository->create($request, $id);
        if ($realisasi['status'])
            return response()->json(['status' => true, 'message' => 'Success adding realization', 'data' => $realisasi['data']], Response::HTTP_CREATED);
        else
            return response()->json(['status' => false, 'message' => $realisasi['message']], Response::HTTP_BAD_REQUEST);
    }

    public function delete(RealisasiDeleteRequest $request, $id)
    {
        $this->realisasiRepository->deleteById($request->id);
        return response()->json(['status' => true, 'message' => 'Success deleting realization'], Response::HTTP_OK);
    }

    public function get(RealisasiGetRequest $request, $id)
    {
        $realisasi = $this->realisasiRepository->getById($request->id);
        return response()->json(['status' => true, 'data' => $realisasi], Response::HTTP_OK);
    }

    public function update(RealisasiUpdateRequest $request, Submission $id)
    {
        $realisasi = $this->realisasiRepository->update($request, $id);
        if ($realisasi['status'])
            return response()->json(['status' => true, 'message' => 'Success updating detail', 'data' => $realisasi['data']], Response::HTTP_OK);
        else
            return response()->json(['status' => false, 'message' => $realisasi['message']], Response::HTTP_BAD_REQUEST);
    }

    public function export(Request $request){
        return Excel::download(new RealizationExport($request->id), 'realisasi.xlsx');
    }

    public function exportexcel(Submission $id)
    {
        if ($id->realizations()->exists()) {
            if ($id->realizations()->where('image_path', '-')->exists()) {
                return redirect()->back()->with("status", ["status" => false, "message" => "Download failed: data has missing image"]);
            }
    
            return Excel::download(new RealizationExportExcel($id), strtotime('now') . '.xlsx');
        }

        return redirect()->back()->with("status", ["status" => false, "message" => "No items to download"]);
    }

    public function exportpdf($id)
    {
        $submission = Submission::all()->where('id', $id);
        $sub = $submission->load('programStudies');
        $realizations = Realization::all()->where('submission_id', $id);
    
        $pdf = PDF::loadview('realisasi.pdf',['submission'=>$sub, 'realizations'=>$realizations]);
        return $pdf->stream();
    }

    public function import(Request $request) {
        Excel::import(new RealizationImport($request->id), $request->file('file')->store('files'));
        return redirect()->back()->with("status", ["status" => true, "message" => "Success importing detail"]);
    }
}
