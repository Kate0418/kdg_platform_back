<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "course_id", "master_grade_id", "master_year_id"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function masterGrade(): BelongsTo
    {
        return $this->belongsTo(MasterGrade::class);
    }

    public function masterYear(): BelongsTo
    {
        return $this->belongsTo(MasterYear::class);
    }

    public function scopeBulkUpdate($query, $records, $columns)
    {
        return DB::bulkUpdate($query, "students", $records, $columns, "user_id");
    }
}
