<?php

namespace App\Http\Controllers;

use App\ProgramStudy;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role != 'prodi') {
            $data = ProgramStudy::with(['submissions' => function ($query) {
                $query->withCount(['realizations as total_realisasi' => function ($query) {
                    $query->select(DB::raw('sum(harga_total)'));
                }])->where('status', 4)->latest();
            }])->get();
        } else {
            $data = $user->programStudies()->with(['submissions' => function ($query) {
                $query->withCount(['submissionDetails as total_pengajuan' => function ($query) {
                    $query->whereHas('negotiation', function ($query) {
                        $query->where('jumlah', '>', 0);
                    });
                }])
                    ->withCount(['realizations as total_pengajuan_realisasi' => function ($query) {
                        $query->whereNotNull('submission_detail_id');
                    }, 'realizations as total_realisasi_baru' => function ($query) {
                        $query->whereNull('submission_detail_id');
                    }, 'realizations as total_realisasi' => function ($query) {
                        $query->select(DB::raw('sum(harga_total)'));
                    }])->where('status', 4)->latest();
            }])->get();
        }
        return view('dashboard.dashboard', compact('data'));
    }
}
