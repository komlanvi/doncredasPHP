<?php


$app->get('/', 'Blog\Controller\HomeController::indexAction')->bind('blog_home_page');

$app->get('/about', 'Blog\Controller\HomeController::aboutAction')->bind('about');

$app->get('/profile', 'Blog\Controller\HomeController::profileAction')->bind('profile');

$app->match('/article/{id}', 'Blog\Controller\HomeController::viewArticleAction')->assert('id', '\d+');

$app->get('/login', 'Blog\Controller\HomeController::loginAction')->bind('login');


// Admin home page
$app->get('/admin', 'Blog\Controller\AdminController::indexAction')->bind('admin');

// Add a new article
$app->match('/admin/article/add', 'Blog\Controller\AdminController::addArticleAction');

// Edit an existing article
$app->match('/admin/article/{id}/edit', 'Blog\Controller\AdminController::editArticleAction')->assert('id', '\d+');

// Remove an article
$app->get('/admin/article/{id}/delete', 'Blog\Controller\AdminController::deleteArticleAction');

// Edit an existing comment
$app->match('/admin/comment/{id}/edit', 'Blog\Controller\AdminController::editCommentAction');

// Remove a comment
$app->get('/admin/comment/{id}/delete', 'Blog\Controller\AdminController::deleteCommentAction')->assert('id', '\d+');

// Add a user
$app->match('/admin/user/add', 'Blog\Controller\AdminController::addUserAction');

// Edit an existing user
$app->match('/admin/user/{id}/edit', 'Blog\Controller\AdminController::editUserAction')->assert('id', '\d+');

// Remove a user
$app->get('/admin/user/{id}/delete', 'Blog\Controller\AdminController::deleteUserAction')->assert('id', '\d+');

/*
 * JSON API
 */

// API : get all articles
$app->get('/api/articles', 'Blog\Controller\ApiController::getAllArticlesAction');

// API : get an article
$app->get('/api/article/{id}', 'Blog\Controller\ApiController::getArticleAction');

// API : create a new article
$app->post('/api/article', 'Blog\Controller\ApiController::addArticleAction');

// API : delete an existing article
$app->delete('/api/article/{id}', 'Blog\Controller\ApiController::deleteArticleAction');