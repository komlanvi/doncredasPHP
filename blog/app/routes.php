<?php

$app->get('/', function () {
    require __DIR__ .'/../src/model.php';

    $articles = getArticles();

    ob_start();
    require __DIR__ .'/../views/view.php';
    $view = ob_get_clean();
    return $view;
});