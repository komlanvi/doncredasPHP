<?php
/**
 * Created by PhpStorm.
 * User: doncredas
 * Date: 28/03/15
 * Time: 1.31
 */

namespace Blog\Dao;

use Blog\Domain\Article;
use Doctrine\DBAL\Connection;


class ArticleDAO {

    /**
     * Database connection
     * @var \Doctrine\DBAL\Connection
     */
    private $db;

    /**
     * Constructor
     *
     * @param \Doctrine\DBAL\Connection The database connection object
     */
    public function __construct(Connection $db) {
        $this->db = $db;
    }

    /**
     * Return a list of all articles, sorted by date (most recent first).
     *
     * @return array A list of all articles.
     */
    public function findAll() {
        $sql = "SELECT * FROM article ORDER BY id DESC";
        $result = $this->db->fetchAll($sql);

        // Convert query result to an array of Domain objects
        $articles = array();
        foreach ($result as $row) {
            $articleId = $row['id'];
            $articles[$articleId] = $this->buildArticle($row);
        }
        return $articles;
    }

    /**
     * Creates an Article object based on a DB row.
     *
     * @param array $row The DB row containing Article data.
     * @return \Blog\Domain\Article
     */
    private function buildArticle(array $row) {
        $article = new Article();
        $article->setId($row['id']);
        $article->setTitre($row['titre']);
        $article->setContenu($row['contenu']);
        return $article;
    }
}