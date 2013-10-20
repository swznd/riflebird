<?php

require_once 'Riflebird/Riflebird.php';

\Riflebird\Riflebird::registerAutoload();

$riflebird = new \Riflebird\Riflebird(array(
  'config.path' => __DIR__ . '/Riflebird/config'
));

$riflebird->run();