<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        "name",
        "password",
        "type",
        "company_id",
        "email",
        "first_password",
    ];

    protected $hidden = ["password", "first_password"];

    public function attend(): HasMany
    {
        return $this->hasMany(Attend::class);
    }

    public function subject(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }
}
