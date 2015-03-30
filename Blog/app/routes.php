<?php

$app->get('/', function () use ($app) {
    $articles = $app['Dao.article']->findAll();
    return $app['twig']->render('index.html.twig', array('articles' => $articles));
});

$app->get('/article/{id}', function ($id) use ($app) {
    $article = $app['Dao.article']->findArticle($id);
    $comments = $app['Dao.comment']->findAllByArticle($id);
    return $app['twig']->render('article.html.twig', array(
        'article' => $article,
        'comments' => $comments
    ));
});