<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ["company_id", "master_grade_id", "name"];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function masterGrade(): BelongsTo
    {
        return $this->belongsTo(MasterGrade::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function periods(): HasMany
    {
        return $this->hasMany(Period::class);
    }
}
