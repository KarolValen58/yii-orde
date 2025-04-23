<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=192.168.56.10;port=3306;dbname=habits_tracker',
    'username' => 'remote_estu_aordonez',
    'password' => '1234567890',
    'charset' => 'utf8mb4',

    // Schema cache options (for production environment)
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 60,
    'schemaCache' => 'cache',
];
