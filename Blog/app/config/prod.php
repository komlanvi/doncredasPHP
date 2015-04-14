<?php
/**
 * Created by PhpStorm.
 * User: doncredas
 * Date: 28/03/15
 * Time: 1.59
 */

// Doctrine (db)
$app['db.options'] = array(
    'driver'   => 'pdo_mysql',
    'charset'  => 'utf8',
    'host'     => 'localhost',
    'port'     => '3306',
    'dbname'   => 'myphp',
    'user'     => 'doncredas',
    'password' => 'secret',
);

//Define log level
$app['monolog.level'] = 'WARNING';