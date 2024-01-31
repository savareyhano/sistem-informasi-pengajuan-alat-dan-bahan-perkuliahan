<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubmissionDetail extends Model
{
    protected $fillable = [
        'submission_id', 'nama_barang', 'image_path', 'jumlah', 'harga_satuan', 'harga_total', 'keterangan',
    ];
    public function submission()
    {
        return $this->belongsTo('App\Submission');
    }

    public function negotiation()
    {
        return $this->hasOne('App\Negotiation');
    }

    public function realization()
    {
        return $this->hasOne('App\Realization');
    }
}
