<?php

use Blog\Domain\Article;
use Blog\Domain\User;
use Blog\Form\Type\ArticleType;
use Blog\Form\Type\UserType;
use \Symfony\Component\HttpFoundation\Request;
use Blog\Domain\Comment;
use Blog\Form\Type\CommentType;

$app->get('/', function () use ($app) {
    $articles = $app['Dao.article']->findAll();
    return $app['twig']->render('index.html.twig', array('articles' => $articles));
})->bind('blog_home_page');

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
})->assert('id', '\d+');

$app->get('/login', function (Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})->bind('login');


// Admin home page
$app->get('/admin', function() use ($app) {
    $articles = $app['Dao.article']->findAll();
    $comments = $app['Dao.comment']->findAll();
    $users = $app['Dao.user']->findAll();
    return $app['twig']->render('admin.html.twig', array(
        'articles' => $articles,
        'comments' => $comments,
        'users' => $users));
})->bind('admin');

// Add a new article
$app->match('/admin/article/add', function(Request $request) use ($app) {
    $article = new Article();
    $articleForm = $app['form.factory']->create(new ArticleType(), $article);
    $articleForm->handleRequest($request);
    if ($articleForm->isSubmitted() && $articleForm->isValid()) {
        $app['Dao.article']->save($article);
        $app['session']->getFlashBag()->add('success', 'The article was successfully created.');
    }
    return $app['twig']->render('article_form.html.twig', array(
        'title' => 'New article',
        'articleForm' => $articleForm->createView()));
});

// Edit an existing article
$app->match('/admin/article/{id}/edit', function($id, Request $request) use ($app) {
    $article = $app['Dao.article']->findArticle($id);
    $articleForm = $app['form.factory']->create(new ArticleType(), $article);
    $articleForm->handleRequest($request);
    if ($articleForm->isSubmitted() && $articleForm->isValid()) {
        $app['Dao.article']->save($article);
        $app['session']->getFlashBag()->add('success', 'The article was succesfully updated.');
    }
    return $app['twig']->render('article_form.html.twig', array(
        'title' => 'Edit article',
        'articleForm' => $articleForm->createView()));
})->assert('id', '\d+');

// Remove an article
$app->get('/admin/article/{id}/delete', function($id, Request $request) use ($app) {
    // Delete all associated comments
    $app['Dao.comment']->deleteAllByArticle($id);
    // Delete the article
    $app['Dao.article']->delete($id);
    $app['session']->getFlashBag()->add('success', 'The article was succesfully removed.');
    return $app->redirect($app['url_generator']->generate('admin'));
});

// Edit an existing comment
$app->match('/admin/comment/{id}/edit', function($id, Request $request) use ($app) {
    $comment = $app['Dao.comment']->find($id);
    $commentForm = $app['form.factory']->create(new CommentType(), $comment);
    $commentForm->handleRequest($request);
    if ($commentForm->isSubmitted() && $commentForm->isValid()) {
        $app['Dao.comment']->save($comment);
        $app['session']->getFlashBag()->add('success', 'The comment was succesfully updated.');
    }
    return $app['twig']->render('comment_form.html.twig', array(
        'title' => 'Edit comment',
        'commentForm' => $commentForm->createView()));
});

// Remove a comment
$app->get('/admin/comment/{id}/delete', function($id, Request $request) use ($app) {
    $app['Dao.comment']->delete($id);
    $app['session']->getFlashBag()->add('success', 'The comment was succesfully removed.');
    return $app->redirect($app['url_generator']->generate('admin'));
})->assert('id', '\d+');

// Add a user
$app->match('/admin/user/add', function(Request $request) use ($app) {
    $user = new User();
    $userForm = $app['form.factory']->create(new UserType(), $user);
    $userForm->handleRequest($request);
    if ($userForm->isSubmitted() && $userForm->isValid()) {
        // generate a random salt value
        $salt = substr(md5(time()), 0, 23);
        $user->setSalt($salt);
        $plainPassword = $user->getPassword();
        // find the default encoder
        $encoder = $app['security.encoder.digest'];
        // compute the encoded password
        $password = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($password);
        $app['Dao.user']->save($user);
        $app['session']->getFlashBag()->add('success', 'The user was successfully created.');
    }
    return $app['twig']->render('user_form.html.twig', array(
        'title' => 'New user',
        'userForm' => $userForm->createView()));
});

// Edit an existing user
$app->match('/admin/user/{id}/edit', function($id, Request $request) use ($app) {
    $user = $app['Dao.user']->findUserById($id);
    $userForm = $app['form.factory']->create(new UserType(), $user);
    $userForm->handleRequest($request);
    if ($userForm->isSubmitted() && $userForm->isValid()) {
        $plainPassword = $user->getPassword();
        // find the encoder for the user
        $encoder = $app['security.encoder_factory']->getEncoder($user);
        // compute the encoded password
        $password = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($password);
        $app['Dao.user']->save($user);
        $app['session']->getFlashBag()->add('success', 'The user was succesfully updated.');
    }
    return $app['twig']->render('user_form.html.twig', array(
        'title' => 'Edit user',
        'userForm' => $userForm->createView()));
})->assert('id', '\d+');

// Remove a user
$app->get('/admin/user/{id}/delete', function($id, Request $request) use ($app) {
    // Delete all associated comments
    $app['Dao.comment']->deleteAllByUser($id);
    // Delete the user
    $app['Dao.user']->delete($id);
    $app['session']->getFlashBag()->add('success', 'The user was succesfully removed.');
    return $app->redirect($app['url_generator']->generate('admin'));
})->assert('id', '\d+');
