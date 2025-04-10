<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    //

    // add fillable
    protected $fillable = [
        'sobat_id',
        'name',
        'user_id',
        'email',
        'pendidikan',
        'jenis_kelamin',
        'tanggal_lahir',
        'photo',
    ];
    // add guaded
    protected $guarded = ['id'];
    // add hidden
    protected $hidden = ['created_at', 'updated_at'];
}
