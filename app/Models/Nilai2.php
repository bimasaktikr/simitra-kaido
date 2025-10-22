<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai2 extends Model
{
    //


    // add fillable
    protected $fillable = [
    'mitra_teladan_id',
    'user_id',
    'aspek1',
    'aspek2',
    'aspek3',
    'aspek4',
    'aspek5',
    'aspek6',
    'aspek7',
    'aspek8',
    'aspek9',
    'aspek10',
    'rerata',
    'is_final',
    ];
    // add guaded
    protected $guarded = ['id'];
    // add hidden
    protected $hidden = ['created_at', 'updated_at'];

    public function mitraTeladan(): BelongsTo
    {
        return $this->belongsTo(MitraTeladan::class, 'mitra_teladan_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
