<?php

return [
    /*
     * This is the default suffix applied to a Model's table name.
     */
    'table_suffix' => '_request_logs',

    /*
     * This is the default suffix applied to models' class names.
     *
     * Example: The facebook object name would have a request log model of FacebookRequestLog.
     */
    'model_suffix' => 'RequestLog',

    'model_path' => app_path('Models'),

    'model_stub' => __DIR__ . '/../stubs/model.stub',

    'migration_path' => database_path('migrations'),

    'migration_stub' => __DIR__ . '/../stubs/migration.stub',

    /*
     * Enable the process stamps (sub) package to log which process/url/job invoked the call
     */
    'enable_process_stamps' => true,

    /*
     * Precision value used in generating occurred_at for request log
     */
    'log_timestamp_precision' => 3,
];
