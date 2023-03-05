<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerClock extends Model
{
    use HasFactory;

    const TYPE_IN = 'in';

    protected $fillable = [
        'worker_id',
        'time',
        'type'
    ];
}
