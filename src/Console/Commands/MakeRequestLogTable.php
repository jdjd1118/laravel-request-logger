<?php

namespace AlwaysOpen\RequestLogger\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class MakeRequestLogTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'request-logger:make-table
                                {object-name? : Name of the request object to log (e.g. facebook becomes FacebookRequestLog)}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes a new migration and model to your application to log requests.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $object_name = $this->argument('object-name');

        while (! $object_name) {
            $object_name = $this->ask('What is the name of the request object you are logging? (e.g. facebook becomes FacebookRequestLog)');
        }

        $config = config('request-logger');

        $object_name = ucwords($object_name);

        $class_name = $object_name . $config['model_suffix'];

        if (class_exists($class_name)) {
            throw new \InvalidArgumentException('Class ' . $class_name . ' already exists');
        }

        $this->line("Generating and table migration for: $class_name");

        $table_name = $this->generateRequestLogTableName($object_name, $config);
        $this->line("Request Log Table will be $table_name");
        $this->createMigration($object_name, $config);

        $this->line("Request Log Model will be $class_name");
        $this->createModel($class_name, $table_name, $config);

        return self::SUCCESS;
    }

    public function generateRequestLogTableName(string $object_name, array $config): string
    {
        return Str::snake($object_name) . $config['table_suffix'];
    }

    public function createModel(string $class_name, string $table_name, array $config): void
    {
        $stub = $this->getStubWithReplacements($config['model_stub'], [
            '{TABLE_NAME}' => $table_name,
            '{CLASS_NAME}' => $class_name,
            '{NAMESPACE}' => 'App\\' . str_replace('/', '\\', str_replace(app_path() . '/', '', $config['model_path'])),
        ]);

        $filename = $config['model_path'] . DIRECTORY_SEPARATOR . $class_name . '.php';

        if (file_put_contents($filename, $stub)) {
            $this->info("Model successfully created at: $filename");
        }
    }

    public function createMigration(string $object_name, array $config): void
    {
        $table_name = $this->generateRequestLogTableName($object_name, $config);
        $file_slug = "create_{$table_name}_table";

        $stub = $this->getStubWithReplacements($config['migration_stub'], [
            '{TABLE_NAME}' => $table_name,
            '{CLASS_NAME}' => Str::studly($file_slug),
            '{PROCESS_IDS_SETUP}' => $this->generateMigrationProcessStamps($config),
            '{PRECISION}' => $this->generatePrecisionValue($config),
        ]);

        $filename = $config['migration_path'] . DIRECTORY_SEPARATOR . $this->generateMigrationFilename($file_slug);

        if (file_put_contents($filename, $stub)) {
            $this->info("Migration successfully created at: $filename");
        }
    }

    public function generateMigrationFilename(string $file_slug): string
    {
        return Str::snake(Str::lower(date('Y_m_d_His') . ' ' . $file_slug . '.php'));
    }

    public function getStubWithReplacements(string $file, array $replacements): string
    {
        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            file_get_contents(realpath($file))
        );
    }

    public function generateMigrationProcessStamps(array $config): string
    {
        if (Arr::get($config, 'enable_process_stamps') === true) {
            return '$table->processIds();';
        }

        return '';
    }

    public function generatePrecisionValue(array $config): string
    {
        return (string) (Arr::get($config, 'log_timestamp_precision', 3) ?? 3);
    }
}
