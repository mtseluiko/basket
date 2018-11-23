<?php

if (isset($_ENV['BOOTSTRAP_RESET_DATABASE']) && $_ENV['BOOTSTRAP_RESET_DATABASE'] == true) {
    echo "Resetting test database...";
    passthru(sprintf(
        'php "%s/../bin/console" doctrine:schema:drop --env=test --force --no-interaction',
        __DIR__
    ));
    passthru(sprintf(
        'php "%s/../bin/console" doctrine:schema:update --env=test --force --no-interaction',
        __DIR__
    ));
    passthru(sprintf(
        'php "%s/../bin/console" doctrine:fixtures:load --env=test --no-interaction',
        __DIR__
    ));
    echo " Done" . PHP_EOL . PHP_EOL;
}
require __DIR__.'/../vendor/autoload.php';