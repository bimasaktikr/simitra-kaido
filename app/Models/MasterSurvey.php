<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterSurvey extends Model
{
    protected $fillable = [
        'name',
        'code',
        'type', // Rumah Tangga or Perusahaan
    ];

    protected $casts = [
        'type' => 'string',
    ];

    public function surveys()
    {
        return $this->hasMany(Survey::class, 'master_survey_id');
    }
}
