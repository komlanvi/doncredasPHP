<?php
/**
 * Created by PhpStorm.
 * User: doncredas
 * Date: 15/04/15
 * Time: 1.42
 */

namespace Blog\Controller;


use Blog\Domain\Comment;
use Blog\Form\Type\CommentType;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;


class HomeController
{

    /**
     * Home page controller
     *
     * @param Application $app The silex Application
     * @return mixed
     */
    public function indexAction(Application $app)
    {
        $articles = $app['Dao.article']->findAll();
        return $app['twig']->render('index.html.twig', array('articles' => $articles));
    }


    /**
     * @param integer $id Article id
     * @param Application $app
     * @return mixed
     */
    public function viewArticleAction($id, Application $app)
    {
        $article = $app['Dao.article']->findArticle($id);

        return $app['twig']->render('article.html.twig', array(
            'article' => $article
        ));
    }

    /**
     * User login controller
     *
     * @param Application $app
     * @param Request $request
     * @return mixed
     */
    public function loginAction(Application $app, Request $request)
    {
        return $app['twig']->render('login.html.twig', array(
            'error'         => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
        ));
    }

    /**
     * About page controller
     *
     * @param Application $app
     * @return mixed
     */
    public function aboutAction(Application $app)
    {
        return $app['twig']->render('about.html.twig');
    }

    /**
     * Profile controller
     *
     * @param Application $app
     * @return mixed
     */
    public function profileAction(Application $app)
    {
        return $app['twig']->render('error.html.twig', array('message' => 'Under construction...', 'code' => '007'));
    }

    public function portofoglioAction(Application $app)
    {
        return $app['twig']->render('portofoglio.html.twig');
    }
}