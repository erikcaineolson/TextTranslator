<?php

namespace App;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use Timestamp;

    /**
     * @var string[]
     */
    protected $fillable = [
        'code',
        'moniker',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
}
