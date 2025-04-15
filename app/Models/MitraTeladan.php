<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MitraTeladan extends Model
{
    //

    // add fillable
    protected $fillable = [
        'mitra_id',
        'team_id',
        'year',
        'quarter',
        'avg_rating_1',
        'avg_rating_2',
        'status_phase_2',
        'surveys_count',
    ];
    // add guaded
    protected $guarded = ['id'];
    // add hidden
    protected $hidden = ['created_at', 'updated_at'];

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id', 'id_sobat');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }

    public function nilai2()
    {
        return $this->hasMany(Nilai2::class, 'mitra_teladan_id', 'id');
    }
}
