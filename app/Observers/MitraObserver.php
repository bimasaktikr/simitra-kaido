<?php

namespace App\Observers;

use App\Models\Mitra;
use Illuminate\Support\Str;

class MitraObserver
{
    public function creating(Mitra $mitra)
    {
        if (empty($mitra->uuid)) {
            $mitra->uuid = (string) Str::uuid();
        }
    }

    public function saved(Mitra $mitra)
    {
        // Placeholder: real QR generation may be handled elsewhere
        return;
    }
}
