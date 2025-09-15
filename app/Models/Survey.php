<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    //

    // add fillable
    protected $fillable = [
        'name',
        'code',
        'triwulan',
        'year',
        'master_survey_id',
        'payment_id',
        'payment_month',
        'start_date',
        'end_date',
        'rate',
        'team_id',
        'file',
        'is_scored',
        'is_synced',
    ];
    // add guaded
    protected $guarded = ['id'];
    // add hidden
    protected $hidden = ['created_at', 'updated_at'];

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'survey_id', 'id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function mitras()
    {
        return $this->hasManyThrough(
            Mitra::class,
            Transaction::class,
            'survey_id',   // FK on transactions
            'id',    // PK on mitras
            'id',          // PK on surveys
            'mitra_id'     // FK on transactions
        );
    }

    public function masterSurvey()
    {
        return $this->belongsTo(MasterSurvey::class, 'master_survey_id');
    }

    // In Survey.php
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

}
