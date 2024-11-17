<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    use HasFactory;

    protected $fillable = ["course_id", "period", "start_time", "end_time"];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
