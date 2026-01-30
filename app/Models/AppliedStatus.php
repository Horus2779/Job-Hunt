<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppliedStatus extends Model
{
    use HasFactory;

    protected $table = 'applied_status';

    public $timestamps = false;
}
