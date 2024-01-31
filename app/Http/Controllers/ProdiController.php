<?php

namespace App\Http\Controllers;

use App\Http\Repository\ProdiRepository;
use App\Http\Repository\UserRepository;
use App\Http\Requests\Prodi\ProdiDeleteRequest;
use App\Http\Requests\Prodi\ProdiGetRequest;
use App\Http\Requests\Prodi\ProdiStoreRequest;
use App\Http\Requests\Prodi\ProdiUpdateKaprodiRequest;
use App\Http\Requests\Prodi\ProdiUpdateRequest;
use App\ProgramStudy;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use NumberFormatter;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ProdiController extends Controller
{
    private $prodiRepository;

    /**
     * ProdiController constructor.
     * @param ProdiRepository $prodiRepository
     */
    public function __construct(ProdiRepository $prodiRepository)
    {
        $this->middleware('auth');
        $this->prodiRepository = $prodiRepository;
    }

    /**
     * @param UserRepository $userRepository
     * @return Factory|View
     */
    public function index(UserRepository $userRepository)
    {
        Gate::authorize('access-menu', 'wakil_direktur');
        $users = $userRepository->getProdiUser();

        return view('prodi.index', ['users' => $users]);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function datatable()
    {
        $programStudies = ProgramStudy::with('user')->get();
        return DataTables::of($programStudies)
            ->addColumn('action', function ($programStudy) {
                return "
                    <a href=\"#\" class=\"btn btn-primary btn-sm btn_kaprodi\" title='Assign Kaprodi' data-id=\"{$programStudy->id}\"><i class=\"fas fa-user\"></i></a>
                    <a href=\"#\" class=\"btn btn-warning btn-sm btn_edit\" title='Edit' data-id=\"{$programStudy->id}\"><i class=\"fas fa-edit\"></i></a>
                    <a href=\"#\" class=\"btn btn-danger btn-sm btn_delete\" title='Delete' data-id=\"{$programStudy->id}\"><i class=\"fas fa-trash-alt\"></i></a>
                ";
            })
            ->editColumn('kode_prodi', function ($programStudy) {
                return strtoupper($programStudy->kode_prodi);
            })
            ->editColumn('nama_prodi', function ($programStudy) {
                return ucwords($programStudy->nama_prodi);
            })
            ->editColumn('pagu', function ($programStudy) {
                $fmt = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
                return $fmt->format($programStudy->pagu);
            })
            ->editColumn('user', function ($programStudy) {
                return ucwords(optional($programStudy->user)->name);
            })
            ->editColumn('created_at', function ($programStudy) {
                return $programStudy->created_at->format('m/d/Y');
            })
            ->make(true);
    }

    /**
     * @param ProdiStoreRequest $request
     * @return JsonResponse
     */
    public function store(ProdiStoreRequest $request)
    {
        $prodi = $this->prodiRepository->create($request);
        return response()->json(['status' => true, 'message' => 'Success creating prodi', 'prodi' => $prodi], Response::HTTP_CREATED);
    }

    /**
     * @param ProdiDeleteRequest $request
     * @return JsonResponse
     */
    public function delete(ProdiDeleteRequest $request)
    {
        $this->prodiRepository->deleteById($request->id);
        return response()->json(['status' => true, 'message' => 'Success deleting prodi'], Response::HTTP_OK);
    }

    /**
     * @param ProdiGetRequest $request
     * @return JsonResponse
     */
    public function get(ProdiGetRequest $request)
    {
        $prodi = $this->prodiRepository->getById($request->id);
        return response()->json(['status' => true, 'prodi' => $prodi], Response::HTTP_OK);
    }

    /**
     * @param ProdiUpdateRequest $request
     * @return JsonResponse
     */
    public function update(ProdiUpdateRequest $request)
    {
        $this->prodiRepository->update($request);
        return response()->json(['status' => true, 'message' => 'Success updating prodi'], Response::HTTP_OK);
    }

    /**
     * @param ProdiUpdateKaprodiRequest $request
     * @return JsonResponse
     */
    public function updateKaprodi(ProdiUpdateKaprodiRequest $request)
    {
        $this->prodiRepository->updateKaprodi($request);
        return response()->json(['status' => true, 'message' => 'Success assigning kaprodi'], Response::HTTP_OK);
    }
}
