<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'employee_file',
        'employer_file',
        'manager_file',
        'employer_status',
        'manager_status',
        'movement',
        'employer_feedback',
        'manager_feedback',
        'employee_id',
        'employer_id',
        'manager_id'
    ];
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
