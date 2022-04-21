<?php

namespace BluefynInternational\ShipEngine\Tests;

use AlwaysOpen\RequestLogger\Console\Commands\MakeRequestLogTable;
use Orchestra\Testbench\TestCase as Orchestra;

class RequestLoggerCommandTest extends Orchestra
{
    public function test_generates_valid_migration_file_name()
    {
        $command = new MakeRequestLogTable();

        $this->assertMatchesRegularExpression(
            '/\d{4}_\d{2}_\d{2}_\d{6}_test_logger\.php/',
            $command->generateMigrationFilename('test_logger'),
        );
    }

    public function test_generate_precision_value_default_value()
    {
        $command = new MakeRequestLogTable();

        $this->assertEquals(3, $command->generatePrecisionValue([]));
    }

    public function test_generate_precision_value_override_value()
    {
        $command = new MakeRequestLogTable();

        $this->assertEquals(9, $command->generatePrecisionValue(['log_timestamp_precision' => 9]));
    }

    public function test_generate_migration_process_stamps()
    {
        $command = new MakeRequestLogTable();

        $this->assertEquals(
            '$table->processIds();',
            $command->generateMigrationProcessStamps(['enable_process_stamps' => true]),
        );

        $this->assertEquals(
            '',
            $command->generateMigrationProcessStamps(['enable_process_stamps' => false]),
        );
    }

    public function test_generate_request_log_table_name()
    {
        $command = new MakeRequestLogTable();

        $this->assertEquals(
            'test_logger_log',
            $command->generateRequestLogTableName('TestLogger', ['table_suffix' => '_log']),
        );
    }
}
