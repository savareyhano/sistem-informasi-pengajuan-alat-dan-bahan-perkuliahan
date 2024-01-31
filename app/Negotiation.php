<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Negotiation extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function submissionDetail()
    {
        return $this->belongsTo('App\SubmissionDetail');
    }
}
