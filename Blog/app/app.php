<?php
/**
 * Created by PhpStorm.
 * User: doncredas
 * Date: 28/03/15
 * Time: 1.54
 */

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register service providers.
$app->register(new Silex\Provider\DoctrineServiceProvider());

// Register services.
$app['Dao.article'] = $app->share(function ($app) {
    return new Blog\Dao\ArticleDAO($app['db']);
});