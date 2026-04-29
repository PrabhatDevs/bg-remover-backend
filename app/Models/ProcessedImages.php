<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessedImages extends Model
{
    protected $fillable = [
        'user_id',
        'ip_address',
        'original_url',
        'result_url',
        'status',
        'prediction_id',
    ];
}
