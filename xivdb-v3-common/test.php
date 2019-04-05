<?php

require __DIR__ .'/vendor/autoload.php';

$logs = \XIVCommon\Logger\Logger::read('check', \XIVCommon\Logger\Logger::TYPE_DEBUG);

print_r($logs);
