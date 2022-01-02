<?php
if (!isset($_SESSION)) {
    session_start();
}

$app = [];

$app['config'] = require 'config.php';
require 'database/connection.php';
require 'database/query.php';

$app['database'] = new Query(
    Connection::make($app['config']['database'])
);

$app['database']->CreateTable($app['config']['database']['tablename']);

$app['database']->CreateSecondTable(strval($app['config']['database']['tablename']) . '2');