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
     * @throws \Exception
     */
    public function findArticle($articleId) {
        $sql = "SELECT * FROM article WHERE id = ?";
        $row = $this->getDB()->fetchAssoc($sql, array($articleId));

        if ($row) {
            return $this->buildDomainObject($row);
        } else {
            return new \Exception("No article matching id: " .$articleId);
        }
    }

    /**
     * Saves an article into the database.
     *
     * @param \Blog\Domain\Article $article The article to save
     */
    public function save(Article $article) {
        $articleData = array(
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
        );

        if ($article->getId()) {
            // The article has already been saved : update it
            $this->getDb()->update('article', $articleData, array('id' => $article->getId()));
        } else {
            // The article has never been saved : insert it
            $this->getDb()->insert('article', $articleData);
            // Get the id of the newly created article and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $article->setId($id);
        }
    }

    /**
     * Removes an article from the database.
     *
     * @param integer $id The article id.
     */
    public function delete($id) {
        // Delete the article
        $this->getDb()->delete('article', array('id' => $id));
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
        $article->setTitle($row['title']);
        $article->setContent($row['content']);
        return $article;
    }
}