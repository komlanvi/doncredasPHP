<?php
/**
 * Created by PhpStorm.
 * User: doncredas
 * Date: 15/04/15
 * Time: 1.51
 */

namespace Blog\Controller;


use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Blog\Domain\Article;
use Blog\Domain\User;
use Blog\Form\Type\ArticleType;
use Blog\Form\Type\UserType;
use Blog\Form\Type\CommentType;


class AdminController {

    /**
     * Admin home controller
     *
     * @param Application $app
     * @return mixed
     */
    public function indexAction (Application $app){
        $articles = $app['Dao.article']->findAll();
        $comments = $app['Dao.comment']->findAll();
        $users = $app['Dao.user']->findAll();
        return $app['twig']->render('admin.html.twig', array(
            'articles' => $articles,
            'comments' => $comments,
            'users' => $users));
    }

    /**
     * Add a new article controller
     *
     * @param Application $app
     * @param Request $request
     * @return mixed
     */
    public function addArticleAction (Application $app, Request $request) {
        $article = new Article();
        $articleForm = $app['form.factory']->create(new ArticleType(), $article);
        $articleForm->handleRequest($request);
        $token = $app['security']->getToken();
        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $now = new \DateTime();
            $article->setAddedTime($now->format('Y-m-d H:i:s'));
            if (null !== $token)
            {
                $article->setAuthor($token->getUser());
            }
            $app['Dao.article']->save($article);
            $app['session']->getFlashBag()->add('success', 'The article was successfully created.');
            return $app->redirect($app['url_generator']->generate('admin'));
        }
        return $app['twig']->render('article_form.html.twig', array(
            'title' => 'New article',
            'articleForm' => $articleForm->createView()));
    }

    /**
     * Edit article controller
     *
     * @param integer $id
     * @param Application $app
     * @param Request $request
     * @return mixed
     */
    public function editArticleAction ($id, Application $app, Request $request){
        $article = $app['Dao.article']->findArticle($id);
        $articleForm = $app['form.factory']->create(new ArticleType(), $article);
        $articleForm->handleRequest($request);
        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $app['Dao.article']->save($article);
            $app['session']->getFlashBag()->add('success', 'The article was successfully updated.');
            //return $app->redirect($app['url_generator']->generate('admin'));
        }
        return $app['twig']->render('article_form.html.twig', array(
            'title' => 'Edit article',
            'articleForm' => $articleForm->createView()));
    }


    /**
     * Delete a comment controller
     *
     * @param integer $id
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteArticleAction ($id, Application $app) {
        // Delete all associated comments
        $app['Dao.comment']->deleteAllByArticle($id);
        // Delete the article
        $app['Dao.article']->delete($id);
        $app['session']->getFlashBag()->add('success', 'The article was successfully removed.');
        return $app->redirect($app['url_generator']->generate('admin'));
    }

    /**
     * Edit a comment controller
     *
     * @param $id
     * @param Application $app
     * @param Request $request
     * @return mixed
     */
    public function editCommentAction ($id, Application $app, Request $request) {
        $comment = $app['Dao.comment']->find($id);
        $commentForm = $app['form.factory']->create(new CommentType(), $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $app['Dao.comment']->save($comment);
            $app['session']->getFlashBag()->add('success', 'The comment was successfully updated.');
            return $app->redirect($app['url_generator']->generate('admin'));
        }
        return $app['twig']->render('comment_form.html.twig', array(
            'title' => 'Edit comment',
            'commentForm' => $commentForm->createView()));
    }

    /**
     * @param integer $id
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteCommentAction ($id, Application $app) {
        $app['Dao.comment']->delete($id);
        $app['session']->getFlashBag()->add('success', 'The comment was successfully removed.');
        return $app->redirect($app['url_generator']->generate('admin'));
    }

    /**
     * Add new user controller
     *
     * @param Application $app
     * @param Request $request
     * @return mixed
     */
    public function addUserAction (Application $app, Request $request) {
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
            return $app->redirect($app['url_generator']->generate('admin'));
        }
        return $app['twig']->render('user_form.html.twig', array(
            'title' => 'New user',
            'userForm' => $userForm->createView()));
    }


    /**
     * Edit the user controller
     *
     * @param integer $id
     * @param Application $app
     * @param Request $request
     * @return mixed
     */
    public function editUserAction ($id, Application $app, Request $request) {
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
            $app['session']->getFlashBag()->add('success', 'The user was successfully updated.');
            return $app->redirect($app['url_generator']->generate('admin'));
        }
        return $app['twig']->render('user_form.html.twig', array(
            'title' => 'Edit user',
            'userForm' => $userForm->createView()));
    }

    /**
     * Delete user controller
     *
     * @param integer $id
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteUserAction($id, Application $app) {
        // Delete all associated comments
        $app['Dao.comment']->deleteAllByUser($id);
        // Delete the user
        $app['Dao.user']->delete($id);
        $app['session']->getFlashBag()->add('success', 'The user was successfully removed.');
        return $app->redirect($app['url_generator']->generate('admin'));
    }

}