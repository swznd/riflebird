<?php
require_once 'Riflebird/Riflebird.php';

\Riflebird\Riflebird::registerAutoload();

$riflebird = new \Riflebird\Riflebird();

$riflebird->run();