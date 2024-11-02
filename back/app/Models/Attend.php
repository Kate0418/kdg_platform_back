<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attend extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "lesson_id", "status", "company_id"];

    protected $casts = [
        "user_id" => "integer",
        "lesson_id" => "integer",
        "status" => "integer",
        "company_id" => "integer",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
