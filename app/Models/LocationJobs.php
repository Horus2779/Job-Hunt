<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationJobs extends Model
{
    use HasFactory;

    protected $table = 'location_jobs';

    public $timestamps = false;
}
