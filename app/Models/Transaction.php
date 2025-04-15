<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //

    // add fillable
    protected $fillable = [
        'mitra_id',
        'survey_id',
        'target',
        'rate',
    ];
    // add guaded
    protected $guarded = ['id'];
    // add hidden
    protected $hidden = ['created_at', 'updated_at'];

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }
    public function nilai()
    {
        return $this->hasOne(Nilai1::class, 'transaction_id');
    }
}
