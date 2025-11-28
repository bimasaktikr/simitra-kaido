<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Mitra extends Model
{
    //

    // add fillable
    protected $fillable = [
        'uuid',
        'sobat_id',
        'name',
        'user_id',
        'email',
        'pendidikan',
        'jenis_kelamin',
        'tanggal_lahir',
        'photo',
        'qr_path',
    ];
    // add guaded
    protected $guarded = ['id'];
    // add hidden
    protected $hidden = ['created_at', 'updated_at'];
    
    /**
     * Boot method untuk auto-generate UUID saat creating
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

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

    /**
     * Accessor untuk mendapatkan URL QR code
     */
    public function getQrUrlAttribute(): ?string
    {
        if (empty($this->qr_path)) {
            return null;
        }
        
        return asset('storage/' . $this->qr_path);
    }

    /**
     * Accessor untuk mendapatkan URL halaman publik
     */
    public function getPublicUrlAttribute(): string
    {
        return url('/check/mitra/' . $this->uuid);
    }

}
