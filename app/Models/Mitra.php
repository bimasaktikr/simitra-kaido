<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'mitra_id', 'id');
    }
    public function surveys(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Models\Survey::class,   // related
            'transactions',              // pivot table
            'mitra_id',                  // FK on pivot -> mitras
            'survey_id',                 // FK on pivot -> surveys
            'id',                        // local key on mitras
            'id'                         // local key on surveys
        );
    }

    public function nilai1s()
    {
        // Mitra -> (hasMany) Transactions -> (hasOne) Nilai1
        return $this->hasManyThrough(
            Nilai1::class,          // related
            Transaction::class, // through
            'mitra_id',             // FK on transactions -> mitras.id
            'transaction_id',       // FK on nilai1s -> transactions.id
            'id',                   // local key on mitras
            'id'                    // local key on transactions
        );
    }

}
