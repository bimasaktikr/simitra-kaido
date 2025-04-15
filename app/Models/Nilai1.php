<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai1 extends Model
{
    //

    // add fillable
    protected $fillable = [
        'transaction_id',
        'aspek1',
        'aspek2',
        'aspek3',
        'rerata',
    ];
    // add guaded
    protected $guarded = ['id'];
    // add hidden
    protected $hidden = ['created_at', 'updated_at'];

    public function transaction() : BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

}
