<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use \Spiritix\LadaCache\Database\LadaCacheTrait;

class Address extends Model
{
    use HasFactory;
    use \Spiritix\LadaCache\Database\LadaCacheTrait;

    protected $fillable = [
        'user_id',
        'address',
        'city',
        'state',
        'zip'
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
