<?php


namespace App\Http\Repository;


use App\Submission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PengajuanRepository implements RepositoryInterface
{

    /**
     * @param $pengajuanData
     * @return Submission|bool|mixed
     */
    public function create($pengajuanData)
    {
        // if ($this->checkDuplicate($pengajuanData)) {
        //     return ['status' => false, 'message' => "There's duplicate pengajuan for tahun akademik {$pengajuanData->tahun_akademik} and semester {$pengajuanData->semester}"];
        // }

        if (!$this->checkSiswa($pengajuanData)) {
            return ['status' => false, 'message' => "Total program study and student isn't match, please check again"];
        }
        $pengajuan = new Submission();
        $pengajuan->tahun_akademik = $pengajuanData->tahun_akademik;
        $pengajuan->semester = $pengajuanData->semester;
        $pengajuan->save();

        $this->syncProdi($pengajuan, $pengajuanData);
        $programStudies = $pengajuan->programStudies()->get();
        $this->updateSiswaAndPagu($pengajuan, $programStudies);

        return ['status' => true, 'data' => $pengajuan];
    }

    /**
     * @param $pengajuanData
     * @return bool
     */
    public function checkSiswa($pengajuanData)
    {
        $siswaArr = explode(',', $pengajuanData->siswa);
        $prodiArr = $pengajuanData->prodi;
        if (count($siswaArr) == count($prodiArr)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param Submission $submission
     * @param $pengajuanData
     */
    public function syncProdi(Submission $submission, $pengajuanData)
    {
        $submission->programStudies()->detach();

        $siswaArr = explode(',', $pengajuanData->siswa);
        $prodiArr = $pengajuanData->prodi;
        for ($x = 0; $x < count($prodiArr); $x++) {
            $submission->programStudies()->attach($prodiArr[$x], ['siswa' => $siswaArr[$x]]);
        }
    }

    /**
     * @param Submission $submission
     * @param $programStudies
     */
    public function updateSiswaAndPagu(Submission $submission, $programStudies)
    {
        $totalSiswa = 0;
        $totalPagu = 0;
        foreach ($programStudies as $programStudy) {
            $totalSiswa += $programStudy->pivot->siswa;
            $totalPagu += $programStudy->pagu * $programStudy->pivot->siswa;
        }

        $submission->siswa = $totalSiswa;
        $submission->pagu = $totalPagu;
        $submission->save();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteById($id)
    {
        return Submission::findOrFail($id)->delete();
    }

    /**
     * @param $id
     * @return Builder|Builder[]|Collection|Model|mixed
     */
    public function getById($id)
    {
        return Submission::with('programStudies')->findOrFail($id);
    }

    /**
     * @param $pengajuanData
     * @return bool|mixed
     */
    public function update($pengajuanData)
    {
        // if ($this->checkDuplicate($pengajuanData)) {
        //     return ['status' => false, 'message' => "There's duplicate pengajuan for tahun akademik {$pengajuanData->tahun_akademik} and semester {$pengajuanData->semester}"];
        // }

        if (!$this->checkSiswa($pengajuanData)) {
            return ['status' => false, 'message' => "Total program study and student isn't match, please check again"];
        }

        $pengajuan = Submission::findOrFail($pengajuanData->id);
        $pengajuan->tahun_akademik = $pengajuanData->tahun_akademik;
        $pengajuan->semester = $pengajuanData->semester;
        $pengajuan->save();

        $this->syncProdi($pengajuan, $pengajuanData);
        $programStudies = $pengajuan->programStudies()->get();
        $this->updateSiswaAndPagu($pengajuan, $programStudies);

        return ['status' => true, 'data' => $pengajuan];
    }

    /**
     * @return Builder[]|Collection
     */
    public function getDataTable($datatableRequest)
    {
        $user = Auth::user();
        if ($user->role == 'prodi') {
            $programStudies = $user->programStudies()->pluck('id');
            $submissions = Submission::whereHas('programStudies', function ($query) use ($programStudies) {
                $query->whereIn('program_studies.id', $programStudies);
            });
        } else {
            $submissions = Submission::with('programStudies');
        }

        if (!empty($datatableRequest->tahunAkademik)) $submissions->where('tahun_akademik', $datatableRequest->tahunAkademik);
        if (!empty($datatableRequest->semester)) $submissions->where('semester', $datatableRequest->semester);
        if (!empty($datatableRequest->prodi)) {
            $submissions->whereHas('programStudies', function ($query) use ($datatableRequest) {
                $query->where('program_studies.id', $datatableRequest->prodi);
            });
        }
        if (!empty($datatableRequest->status)) {
            if ($datatableRequest->status == 1) {
                $submissions->where(function ($query) use ($datatableRequest) {
                    $query->where('status', $datatableRequest->status)
                        ->orWhere('status', 3);
                });
            } else {
                $submissions->where('status', $datatableRequest->status);
            }
        }

        return $submissions->get();
    }

    public function getAcademicYears()
    {
        return Submission::select('tahun_akademik')->groupBy('tahun_akademik')->orderBy('tahun_akademik')->get();
    }

    public function getSemester()
    {
        return Submission::select('semester')->groupBy('semester')->orderBy('semester')->get();
    }

    private function checkDuplicate($pengajuanData)
    {
        $pengajuan = Submission::where([
            ['tahun_akademik', '=', $pengajuanData->tahun_akademik],
            ['semester', '=', $pengajuanData->semester]
        ])->whereHas('programStudies', function (Builder $query) use ($pengajuanData) {
            $query->whereIn('program_studies.id', $pengajuanData->prodi);
        })->first();

        if (empty($pengajuan)) {
            return false;
        } elseif (isset($pengajuanData->id) && $pengajuanData->id == $pengajuan->id) {
            return false;
        }

        return true;
    }
}
