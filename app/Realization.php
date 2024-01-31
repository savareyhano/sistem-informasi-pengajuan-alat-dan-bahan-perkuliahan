<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Realization extends Model
{
    protected $fillable = [
        'id', 'submission_id', 'submission_detail_id', 'nama_barang', 'image_path', 'jumlah', 'harga_satuan', 'harga_total', 'keterangan',
    ];
    public function submission()
    {
        return $this->belongsTo('App\Submission');
    }

    public function submissionDetail()
    {
        return $this->belongsTo('App\SubmissionDetail');
    }
}
