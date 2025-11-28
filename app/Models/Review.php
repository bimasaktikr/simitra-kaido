<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;

class Review extends Model
{
    //

    // add fillable
    protected $fillable = [
        'transaction_id',
        'email',
        'rating',
        'comment',
    ];
    // add guaded
    protected $guarded = ['id'];
    // add hidden
    protected $hidden = ['created_at', 'updated_at'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}