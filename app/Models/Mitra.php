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

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function surveys()
    {
        return $this->hasManyThrough(
            Survey::class,
            Transaction::class,
            'mitra_id',    // Foreign key on transactions table...
            'id',          // Foreign key on surveys table...
            'id_sobat',    // Local key on mitras table...
            'survey_id'    // Local key on transactions table...
        );
    }

}
