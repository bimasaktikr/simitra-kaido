<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //

    // add fillable
    protected $fillable = [
        'name',
        'nip',
        'jenis_kelamin',
        'user_id',
        'tanggal_lahir',
        'team_id',
    ];
    // add guaded
    protected $guarded = ['id'];
    // add hidden
    protected $hidden = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

}
