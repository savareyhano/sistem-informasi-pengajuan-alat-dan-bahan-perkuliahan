<?php


namespace App\Http\Repository;

use App\ProgramStudy;
use Illuminate\Support\Facades\Auth;

class ProdiRepository implements RepositoryInterface
{
    /**
     * @param $prodiData
     * @return ProgramStudy|mixed
     */
    public function create($prodiData)
    {
        $programStudy = new ProgramStudy();
        $programStudy->kode_prodi = $prodiData->kode_prodi;
        $programStudy->nama_prodi = $prodiData->nama_prodi;
        $programStudy->pagu = $prodiData->pagu;
        $programStudy->save();

        return $programStudy;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteById($id)
    {
        return ProgramStudy::findOrFail($id)->delete();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return ProgramStudy::findOrFail($id);
    }

    /**
     * @param $prodiData
     * @return mixed
     */
    public function update($prodiData)
    {
        $prodi = ProgramStudy::findOrFail($prodiData->id);
        $prodi->kode_prodi = $prodiData->kode_prodi;
        $prodi->nama_prodi = $prodiData->nama_prodi;
        $prodi->pagu = $prodiData->pagu;
        $prodi->save();

        return $prodi;
    }

    /**
     * @param $prodiData
     * @return mixed
     */
    public function updateKaprodi($prodiData)
    {
        $prodi = ProgramStudy::findOrFail($prodiData->id);
        $prodi->user_id = $prodiData->user;
        $prodi->save();

        return $prodi;
    }

    /**
     * @return mixed
     */
    public function getProdiByUser()
    {
        return Auth::user()->programStudies()->get();
    }

    public function get()
    {
        return ProgramStudy::all();
    }
}
