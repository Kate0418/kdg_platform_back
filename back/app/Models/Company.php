<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ["name", "latitude", "longitude"];

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function attend(): HasMany
    {
        return $this->hasMany(Attend::class);
    }

    public function subject(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    public function course(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function lesson(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }
}
