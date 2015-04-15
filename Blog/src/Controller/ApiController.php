<?php
/**
 * Created by PhpStorm.
 * User: doncredas
 * Date: 15/04/15
 * Time: 1.51
 */

namespace Blog\Controller;


use Silex\Application;
use Symfony\Component\BrowserKit\Request;
use Blog\Domain\Article;


class ApiController
{

    /**
     * Get all the articles controller
     *
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAllArticlesAction(Application $app) {
        $articles = $app['Dao.article']->findAll();
        // Convert an array of objects ($articles) into an array of associative arrays ($responseData)
        $responseData = array();
        foreach ($articles as $article) {
            $responseData[] = array(
                'id' => $article->getId(),
                'title' => $article->getTitle(),
                'content' => $article->getContent()
            );
        }
        // Create and return a JSON response
        return $app->json($responseData);
    }

    /**
     * Get an article controller
     *
     * @param integer $id
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getArticleAction($id, Application $app) {
        $article = $app['Dao.article']->findArticle($id);
        // Convert an object ($article) into an associative array ($responseData)
        $responseData = array(
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'content' => $article->getContent()
        );
        // Create and return a JSON response
        return $app->json($responseData);
    }

    /**
     * Add a new article controller
     *
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addArticleController(Application $app, Request $request) {
        // Check request parameters
        if (!$request->request->has('title')) {
            return $app->json('Missing required parameter: title', 400);
        }
        if (!$request->request->has('content')) {
            return $app->json('Missing required parameter: content', 400);
        }
        // Build and save the new article
        $article = new Article();
        $article->setTitle($request->request->get('title'));
        $article->setContent($request->request->get('content'));
        $app['Dao.article']->save($article);
        // Convert an object ($article) into an associative array ($responseData)
        $responseData = array(
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'content' => $article->getContent()
        );
        return $app->json($responseData, 201);  // 201 = Created
    }

    /**
     * Delete an article action
     *
     * @param $id
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteArticleAction($id, Application $app) {
        // Delete all associated comments
        $app['Dao.comment']->deleteAllByArticle($id);
        // Delete the article
        $app['Dao.article']->delete($id);
        return $app->json('No Content', 204);  // 204 = No content
    }


}