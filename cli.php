#!/usr/bin/php
<?php

require __DIR__.'/vendor/autoload.php';

echo "Nubank CLI Application\n\n";

\App\Command\Process::run();

echo "\nProcessamento finalizado!\n";
