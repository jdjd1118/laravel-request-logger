<?php

namespace AlwaysOpen\RequestLogger\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * AlwaysOpen\RequestLogger\Models\RequestLogBaseModel
 *
 * @property string|null          $path
 * @property string|null          $params
 * @property string               $http_method
 * @property int|null             $response_code
 * @property array|string|null    $body
 * @property array|string|null    $response
 * @property string|null          $exception
 * @property \Carbon\Carbon|null  $occurred_at
 */
class RequestLogBaseModel extends Model
{
    use SoftDeletes;

    protected $casts = [
        'occurred_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'body' => 'json',
        'response' => 'json',
    ];

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected static function boot()
    {
        parent::boot();
        parent::observe();
    }
}
