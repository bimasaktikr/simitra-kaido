<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = ['id'];

    protected $fillable = [
        'mitra_id',
        'survey_id',
        'target',
        'rate',
    ];

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
    
    public function qr()
    {
        return $this->hasOne(TransactionQr::class);
    }

}
