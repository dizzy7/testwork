<?php

error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

$config = include __DIR__ . '/../config.php';
Application::init($config, true);
$response = Application::handleRequest();
$response->send();