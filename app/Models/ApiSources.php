<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiSources extends Model
{
    use HasFactory;

    protected $table = 'api_sources';
    
    public $timestamps = false;
}
