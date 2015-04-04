<?php

use \Symfony\Component\HttpFoundation\Request;
use Blog\Domain\Comment;
use Blog\Form\Type\CommentType;

$app->get('/', function () use ($app) {
    $articles = $app['Dao.article']->findAll();
    return $app['twig']->render('index.html.twig', array('articles' => $articles));
});

$app->match('/article/{id}', function ($id, Request $request) use ($app) {
    $article = $app['Dao.article']->findArticle($id);

    $user = $app['security']->getToken()->getUser();
    $commentFormView = null;
    if ($app['security']->isGranted('IS_AUTHENTICATED_FULLY')) {
        // A user is fully authenticated : he can add comments
        $comment = new Comment();
        $comment->setArticle($article);
        $comment->setAuthor($user);
        $commentForm = $app['form.factory']->create(new CommentType(), $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $app['Dao.comment']->save($comment);
            $app['session']->getFlashBag()->add('success', 'Your comment was succesfully added.');
        }
        $commentFormView = $commentForm->createView();
    }
    $comments = $app['Dao.comment']->findAllByArticle($id);
    return $app['twig']->render('article.html.twig', array(
        'article' => $article,
        'comments' => $comments,
        'commentForm' => $commentFormView
    ));
});

$app->get('/login', function (Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})->bind('login');
