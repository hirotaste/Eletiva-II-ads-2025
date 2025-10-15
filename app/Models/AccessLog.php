<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'description',
    ];

    /**
     * Get the user that owns the access log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
