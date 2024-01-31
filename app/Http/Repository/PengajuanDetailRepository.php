<?php


namespace App\Http\Repository;


use App\Negotiation;
use App\Submission;
use App\SubmissionDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengajuanDetailRepository
{

    /**
     * @param $id
     * @return Builder|Builder[]|Collection|Model
     */
    public function getById($id)
    {
        return SubmissionDetail::with('negotiation')->findOrFail($id);
    }

    /**
     * @param $submission
     * @return mixed
     */
    public function getBySubmission($submission)
    {
        return $submission->submissionDetails()->with('negotiation')->get();
    }

    public function updateNegotiation($data, Submission $submission)
    {
        $max = $submission->pagu;
        $count = $this->getTotalHarga($submission);

        $detail = SubmissionDetail::findOrFail($data->id);
        $count -= $detail->harga_total;

        if ($count + ($data->harga_satuan * $data->jumlah_barang) > $max) {
            return ['status' => false, 'message' => 'Total harga melebihi batas pagu'];
        }

        $negotiation = new Negotiation();
        $negotiation->submission_detail_id = $data->id;
        $negotiation->status = $data->status;
        $negotiation->jumlah = $data->status == 1 ? $detail->jumlah : ($data->status == 2 ? $data->jumlah_barang : 0);
        $negotiation->harga_satuan = $data->status == 1 ? $detail->harga_satuan : ($data->status == 2 ? $data->harga_satuan : 0);
        $negotiation->harga_total = $data->status == 1 ? $detail->harga_total : ($data->status == 2 ? $data->jumlah_barang * $data->harga_satuan : 0);
        $negotiation->keterangan = $data->keterangan;
        $negotiation->user_id = Auth::id();
        $negotiation->save();

        return ['status' => true, 'data' => $negotiation];
    }

    private function getTotalHarga($submission)
    {
        $submissionDetails = $submission->submissionDetails()->with('negotiation')->get();
        $count = 0;
        foreach ($submissionDetails as $submissionDetail) {
            $count += empty($submissionDetail->negotiation) ? $submissionDetail->harga_total : $submissionDetail->negotiation->harga_total;
        }

        return $count;
    }

    public function create($data, Submission $submission)
    {
        $max = $submission->pagu;
        $count = $this->getTotalHarga($submission);

        if ($count + ($data->harga_satuan * $data->jumlah_barang) > $max) {
            return ['status' => false, 'message' => 'Total harga melebihi batas pagu'];
        }
        $path = $data->file('gambar')->store('public/images/detail_pengajuan');
        $detail = new SubmissionDetail();
        $detail->nama_barang = $data->nama_barang;
        $detail->submission_id = $submission->id;
        $detail->image_path = $path;
        $detail->jumlah = $data->jumlah_barang;
        $detail->harga_satuan = $data->harga_satuan;
        $detail->harga_total = $data->harga_satuan * $data->jumlah_barang;
        $detail->keterangan = $data->keterangan;
        $detail->save();

        return ['status' => true, 'data' => $detail];
    }

    public function update($data, Submission $submission)
    {
        $max = $submission->pagu;
        $count = $this->getTotalHarga($submission);

        $detail = SubmissionDetail::findOrFail($data->id);
        $count -= $detail->harga_total;

        if ($count + ($data->harga_satuan * $data->jumlah_barang) > $max) {
            return ['status' => false, 'message' => 'Total harga melebihi batas pagu'];
        }

        $detail->nama_barang = $data->nama_barang;
        if ($data->hasFile('gambar')) {
            Storage::delete($detail->image_path);
            $path = $data->file('gambar')->store('public/images/detail_pengajuan');
            $detail->image_path = $path;
        }

        $detail->jumlah = $data->jumlah_barang;
        $detail->harga_satuan = $data->harga_satuan;
        $detail->harga_total = $data->harga_satuan * $data->jumlah_barang;
        $detail->keterangan = $data->keterangan;
        $detail->save();

        return ['status' => true, 'data' => $detail];
    }

    public function deleteById($id)
    {
        $detail = SubmissionDetail::findOrFail($id);
        // Storage::delete($detail->image_path);
        return $detail->delete();
    }
}
