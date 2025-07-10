<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
