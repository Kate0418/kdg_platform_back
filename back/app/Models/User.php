<?php

namespace App\Models;

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
        "course_id",
        "company_id",
        "email",
        "first_password",
        "ip_address",
        "grade_id",
    ];

    protected $hidden = ["password", "first_password"];

    protected $casts = [
        "type" => "integer",
        "course_id" => "integer",
        "company_id" => "integer",
        "grade_id" => "integer",
    ];

    protected $attributes = [
        "course_id" => null,
        "grade_id" => null,
    ];

    public function attend(): HasMany
    {
        return $this->hasMany(Attend::class);
    }

    public function subject(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
