<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;
    use \Spiritix\LadaCache\Database\LadaCacheTrait;


    protected $fillable = [
        'user_id',
        'professional_summary',
        'skills',
        'years_of_experience',
        'certifications',
        'educations'
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
