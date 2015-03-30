<?php
/**
 * Created by PhpStorm.
 * User: doncredas
 * Date: 28/03/15
 * Time: 1.31
 */

namespace Blog\Dao;

use Blog\Domain\Article;


class ArticleDAO extends DAO{

    /**
     * Return a list of all articles, sorted by date (most recent first).
     *
     * @return array A list of all articles.
     */
    public function findAll() {
        $sql = "SELECT * FROM article ORDER BY id DESC";
        $result = $this->getDB()->fetchAll($sql);

        // Convert query result to an array of Domain objects
        $articles = array();
        foreach ($result as $row) {
            $articleId = $row['id'];
            $articles[$articleId] = $this->buildDomainObject($row);
        }
        return $articles;
    }

    /**
     * @param integer $articleId
     * @return ArticleDAO
     */
    public function findArticle($articleId) {
        $sql = "SELECT * FROM article WHERE id = ?";
        $row = $this->getDB()->fetchAssoc($sql, array($articleId));

        if ($row) {
            return $this->buildDomainObject($row);
        } else {
            return \Exception("No article matching id: " .$articleId);
        }
    }

    /**
     * Creates an Article object based on a DB row.
     *
     * @param array $row The DB row containing Article data.
     * @return \Blog\Domain\Article
     */
    protected function buildDomainObject(array $row) {
        $article = new Article();
        $article->setId($row['id']);
        $article->setTitre($row['titre']);
        $article->setContenu($row['contenu']);
        return $article;
    }
}