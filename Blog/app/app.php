<?php
/**
 * Created by PhpStorm.
 * User: doncredas
 * Date: 28/03/15
 * Time: 1.54
 */

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;
use Aptoma\Twig\Extension\MarkdownExtension;
use Aptoma\Twig\Extension\MarkdownEngine;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register service providers.
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
    'twig.options' => array("debug" => true),
));

$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());

$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secured' => array(
            'pattern' => '^/',
            'anonymous' => true,
            'logout' => true,
            'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
            'users' => $app->share(function () use ($app) {
                return new Blog\Dao\UserDAO($app['db']);
            }),
        ),
    ),
    'security.role_hierarchy' => array(
        'ROLE_ADMIN' => array('ROLE_USER'),
    ),
    'security.access_rules' => array(
        array('^/admin', 'ROLE_ADMIN'),
    ),
));

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../var/logs/blog.log',
    'monolog.name' => 'Blog',
    'monolog.level' => $app['monolog.level']
));
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
if (isset($app['debug']) && $app['debug']) {
    $app->register(new Silex\Provider\WebProfilerServiceProvider(), array(
        'profiler.cache_dir' => __DIR__.'/../var/cache/profiler'
    ));
}
$app->register(new Silex\Provider\HttpFragmentServiceProvider());


// Register services.

$app['twig'] = $app->share($app->extend('twig', function(Twig_Environment $twig) {
    $engine = new MarkdownEngine\MichelfMarkdownEngine();
    $twig->addExtension(new Twig_Extensions_Extension_Text());
    $twig->addExtension(new MarkdownExtension($engine));
    return $twig;
}));

$app['Dao.user'] = $app->share(function ($app) {
    return new Blog\Dao\UserDAO($app['db']);
});

$app['Dao.article'] = $app->share(function ($app) {
    $articleDAO = new Blog\Dao\ArticleDAO($app['db']);
    $articleDAO->setUserDAO($app['Dao.user']);
    return $articleDAO;

});

$app['Dao.comment'] = $app->share(function ($app) {
    $commentDAO = new Blog\Dao\CommentDAO($app['db']);
    $commentDAO->setArticleDAO($app['Dao.article']);
    $commentDAO->setUserDAO($app['Dao.user']);
    return $commentDAO;
});

// Register error handler
$app->error(function (Exception $e, $code) use ($app) {
    switch ($code) {
        case 403:
            $message = 'Access denied.';
            break;
        case 404:
            $message = 'The requested resource could not be found.';
            break;
        default:
            $message = "Something went wrong.";
    }
    return $app['twig']->render('error.html.twig', array('message' => $message, 'code' => $code));
});

$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});


