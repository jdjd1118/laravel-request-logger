<?php

namespace AlwaysOpen\RequestLogger\Models;

use AlwaysOpen\RequestLogger\Observers\RequestLogObserver;
use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Model;

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
    protected $casts = [
        'occurred_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'body' => 'json',
        'response' => 'json',
    ];

    protected $guarded = [
        'created_at',
        'updated_at',
    ];

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected static function boot()
    {
        parent::boot();
        parent::observe(RequestLogObserver::class);
    }

    public static function make(array $props) : static
    {
        return new static($props + ['occurred_at' => now()]);
    }

    public static function makeFromGuzzle(Request $request) : static
    {
        $instance = new static();
        $instance->occurred_at = now();
        $instance->params = $request->getUri()->getQuery();
        $instance->path = $request->getUri()->getPath();
        $instance->http_method = $request->getMethod();
        $instance->body = $request->getBody()->getContents();

        return $instance;
    }
}
