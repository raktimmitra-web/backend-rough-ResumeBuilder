<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Social extends Model
{
       use HasFactory,HasUuids;

    public $timestamps = false;

    protected $fillable = [
        'linkedin',
        'github',
        'portfolio',
        'twitter',
    ];
    
       protected static function booted()
    {
        static::creating(function ($resume) {
            if (! $resume->id) {
                $resume->id = (string) Str::uuid();
            }
        });
    }
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
