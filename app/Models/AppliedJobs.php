<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppliedJobs extends Model
{
    use HasFactory;

    protected $table = 'applied_jobs';

    protected $fillable = [
        'title',
        'company',
        'location',
        'url',
        'source_id',
        'status'
    ];
}
