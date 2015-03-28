<?php

$app->get('/', function () use ($app) {
    $articles = $app['Dao.article']->findAll();
    return $app['twig']->render('index.html.twig', array('articles' => $articles));
});