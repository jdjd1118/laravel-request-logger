<?php

namespace BluefynInternational\ShipEngine\Tests;

use AlwaysOpen\RequestLogger\Models\RequestLogBaseModel;
use GuzzleHttp\Psr7\Request;
use Orchestra\Testbench\TestCase as Orchestra;

class RequestLogTest extends Orchestra
{
    public function test_make_works()
    {
        $log = RequestLogBaseModel::make([
            'params' => 'test=true&log=everything',
            'body' => [],
            'http_method' => 'GET',
            'path' => '/test',
            'response' => [],
            'response_code' => 200,
        ]);

        $this->assertEquals(
            'test=true&log=everything',
            $log->params,
        );

        $this->assertEquals(
            200,
            $log->response_code,
        );

        $this->assertEquals(
            [],
            $log->response,
        );

        $this->assertNotNull($log->occurred_at);
    }

    public function test_make_from_guzzle_works()
    {
        $request = new Request(
            'GET',
            '/v1/test?test=true&log=everything',
            [],
            "['hidden' => 'value',]",
        );

        $log = RequestLogBaseModel::makeFromGuzzle(
            $request
        );

        $this->assertEquals(
            'test=true&log=everything',
            $log->params,
        );

        $this->assertNull(
            $log->response_code,
        );

        $this->assertEquals(
            "['hidden' => 'value',]",
            $log->body,
        );
    }
}
