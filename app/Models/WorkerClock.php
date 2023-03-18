<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkerClock extends Model
{
    use HasFactory;

    const TYPE_IN = 'in';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'worker_id',
        'time',
        'type'
    ];

    /**
     * set relation with user model
     * @return BelongsTo
     */
    public  function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'worker_id');
    }
}
