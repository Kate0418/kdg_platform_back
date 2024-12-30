<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "name", "company_id"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lesson(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    public function scopeBulkUpdate($query, $records, $columns)
    {
        return DB::bulkUpdate($query, "subjects", $records, $columns);
    }
}
