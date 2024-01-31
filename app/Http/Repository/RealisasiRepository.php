<?php


namespace App\Http\Repository;


use App\Realization;
use App\Submission;
use App\SubmissionDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RealisasiRepository
{

    /**
     * @inheritDoc
     */
    public function create($realisasiData, $submission)
    {
        $max = $submission->pagu;
        $count = $this->getTotalHarga($submission);

        if ($count + ($realisasiData->harga_satuan * $realisasiData->jumlah_barang) > $max) {
            return ['status' => false, 'message' => 'Total harga melebihi batas pagu'];
        }

        $realisasi = new Realization();
        $realisasi->submission_id = $submission->id;

        if ($realisasiData->type == 'existing') {
            $barang = SubmissionDetail::findOrFail($realisasiData->barang);
            $realisasi->submission_detail_id = $barang->id;
            $realisasi->nama_barang = $barang->nama_barang;
            $realisasi->image_path = $barang->image_path;
        } else {
            $path = $realisasiData->file('gambar')->store('public/images/realisasi');
            $realisasi->nama_barang = $realisasiData->nama_barang;
            $realisasi->image_path = $path;
        }

        $realisasi->jumlah = $realisasiData->jumlah_barang;
        $realisasi->harga_satuan = $realisasiData->harga_satuan;
        $realisasi->harga_total = $realisasiData->jumlah_barang * $realisasiData->harga_satuan;
        $realisasi->keterangan = $realisasiData->keterangan;
        $realisasi->save();

        return ['status' => true, 'data' => $realisasi];
    }

    private function getTotalHarga($submission)
    {
        $realizations = $submission->realizations()->get();
        $count = 0;
        foreach ($realizations as $realization) {
            $count += $realization->harga_total;
        }

        return $count;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($id)
    {
        $realisasi = Realization::findOrFail($id);
        // if (empty($realisasi->submission_detail_id)) Storage::delete($realisasi->image_path);
        return $realisasi->delete();
    }

    /**
     * @inheritDoc
     */
    public function getById($id)
    {
        return Realization::findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function update($data, $submission)
    {
        $max = $submission->pagu;
        $count = $this->getTotalHarga($submission);

        $realisasi = Realization::findOrFail($data->id);
        $count -= $realisasi->harga_total;

        if ($count + ($data->harga_satuan * $data->jumlah_barang) > $max) {
            return ['status' => false, 'message' => 'Total harga melebihi batas pagu'];
        }

        $realisasi->jumlah = $data->jumlah_barang;
        $realisasi->harga_satuan = $data->harga_satuan;
        $realisasi->harga_total = $data->harga_satuan * $data->jumlah_barang;
        $realisasi->keterangan = $data->keterangan;
        $realisasi->save();

        return ['status' => true, 'data' => $realisasi];
    }

    public function getDataTable($datatableRequest)
    {
        $user = Auth::user();
        if ($user->relo == 'prodi') {
            $programStudies = $user->programStudies()->pluck('id');
            $submissions = Submission::whereHas('programStudies', function ($query) use ($programStudies) {
                $query->whereIn('program_studies.id', $programStudies);
            });
        } else {
            $submissions = Submission::with('programStudies');
        }

        $submissions->where('status', 4);

        if (!empty($datatableRequest->tahunAkademik)) $submissions->where('tahun_akademik', $datatableRequest->tahunAkademik);
        if (!empty($datatableRequest->semester)) $submissions->where('semester', $datatableRequest->semester);
        if (!empty($datatableRequest->prodi)) {
            $submissions->whereHas('programStudies', function ($query) use ($datatableRequest) {
                $query->where('program_studies.id', $datatableRequest->prodi);
            });
        }

        return $submissions->get();
    }

    public function getBySubmission(Submission $submission)
    {
        return $submission->realizations()->with('submissionDetail.negotiation')->get();
    }
}
