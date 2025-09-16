<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaximalPayment extends Model
{
    protected $fillable = ['name','value','singleton'];
    protected $casts = ['value' => 'int', 'singleton' => 'bool'];

    public static function current(): ?self
    {
        return static::where('singleton', true)->first();
    }

    public static function value(): int
    {
        return (int) static::query()->where('singleton', true)->value('value') ?: 0;
    }

    public static function setValue(int $value, ?string $name = null): self
    {
        return static::query()->updateOrCreate(
            ['singleton' => true],
            ['value' => $value, 'name' => $name ?? 'Monthly Cap']
        );
    }
}

