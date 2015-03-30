<?php
/**
 * Created by PhpStorm.
 * User: doncredas
 * Date: 29/03/15
 * Time: 23.39
 */

namespace Blog\Dao;

use Blog\Domain;


class CommentDAO extends DAO{

    /**
     * @var \Blog\Dao\ArticleDAO
     */
    private $articleDAO;

    /**
     * @param \Blog\Dao\ArticleDAO $articleDAO
     */
    public function setArticleDAO(ArticleDAO $articleDAO) {
        $this->articleDAO = $articleDAO;
    }

    /**
     * return an array of all comments associated to the
     * article with id $articleId
     *
     * @param integer $articleId
     *
     * @return array comments
     */
    public function findAllByArticle($articleId) {
        $sql = "SELECT * FROM comment";
        $results = $this->getDB()->fetchAll($sql);

        $comments = array();
        foreach($results as $row){
            $commentsId = $row["id"];
            $comments[$commentsId] = $this->buildDomainObject($row);
        }
        return $comments;
    }

    /**
     * @param array $row
     * @return array $comments
     */
    protected function buildDomainObject(array $row){
        $comment = new \Blog\Domain\Comment();
        $comment->setId($row["id"]);
        $comment->setAuthor($row["author"]);
        $comment->setContent($row["content"]);

        if(array_key_exists("article_id", $row)){
            $articleId = $row["article_id"];
            $article = $this->articleDAO->findArticle($articleId);
            $comment->setArticle($article);
        }
        return $comment;
    }

}