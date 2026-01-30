<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParameterJobs extends Model
{
    use HasFactory;

    protected $table = 'parameter_jobs';

    public $timestamps = false;
}
