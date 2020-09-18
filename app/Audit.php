<?php

namespace App;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use Timestamp;

    /**
     * @var string[]
     */
    protected $fillable = [
        'mime_type',
        'file_size',
        'source_language',
        'destination_language',
        'bot',
        'browser',
        'device',
        'ip',
        'os',
        'user_agent',
    ];
}
