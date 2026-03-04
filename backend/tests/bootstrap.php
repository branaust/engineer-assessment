<?php

// Override database env vars before Laravel bootstraps so tests use
// a separate database and don't wipe the seeded production data.
$_SERVER['DB_DATABASE'] = 'starwars_test';
$_ENV['DB_DATABASE'] = 'starwars_test';
putenv('DB_DATABASE=starwars_test');

require __DIR__ . '/../vendor/autoload.php';
