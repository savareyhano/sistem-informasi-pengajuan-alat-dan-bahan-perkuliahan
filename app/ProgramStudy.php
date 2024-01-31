<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProgramStudy extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function submissions()
    {
        return $this->belongsToMany('App\Submission')
            ->withPivot('siswa')
            ->withTimestamps();
    }

    public function getLatestSubmissionAttribute()
    {
        return $this->submissions->first();
    }
}
