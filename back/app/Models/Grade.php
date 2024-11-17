<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = ["name"];

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function course(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}
