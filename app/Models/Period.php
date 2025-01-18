<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Period extends Model
{
    use HasFactory;

    protected $fillable = ["course_id", "sequence", "start_time", "end_time"];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
