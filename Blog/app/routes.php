<?php

$app->get('/', function () use ($app) {
    $articles = $app['Dao.article']->findAll();

    ob_start();
    require __DIR__ .'/../views/view.php';
    $view = ob_get_clean();
    return $view;
});