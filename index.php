<?php
require_once 'Riflebird/Riflebird.php';

\Riflebird\Riflebird::registerAutoload();

$riflebird = new \Riflebird\Riflebird(array(
  'basepath' => __DIR__,
  'config.path' => __DIR__ . '/Riflebird/config'
));

$riflebird->run();