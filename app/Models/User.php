<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        "company_id",
        'name',
        'email',
        'password',
        "first_password",
        "type",
    ];

    protected $hidden = ["password", "first_password"];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    public function scopeBulkUpdate($query, $records, $columns)
    {
        return DB::bulkUpdate($query, "users", $records, $columns);
    }
}
