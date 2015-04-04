<?php
/**
 * Created by PhpStorm.
 * User: doncredas
 * Date: 29/03/15
 * Time: 23.39
 */

namespace Blog\Dao;

use Blog\Domain\Comment;


class CommentDAO extends DAO{

    /**
     * @var \Blog\Dao\ArticleDAO
     */
    private $articleDAO;

    /**
     * @var \Blog\Dao\UserDAO
     */
    private $userDAO;

    /**
     * @param ArticleDAO $articleDAO
     */
    public function setArticleDAO(ArticleDAO $articleDAO) {
        $this->articleDAO = $articleDAO;
    }

    /**
     * @param UserDAO $userDAO
     */
    public function setUserDAO (UserDAO $userDAO) {
        $this->userDAO = $userDAO;
    }

    /**
     * @param Comment $comment
     */
    public function save(Comment $comment) {
        $commentData = array(
            'article_id' => $comment->getArticle()->getId(),
            'user_id' => $comment->getAuthor()->getId(),
            'content' => $comment->getContent()
        );

        if ($comment->getId()) {
            // The comment has already been saved : update it
            $this->getDb()->update('comment', $commentData, array('id' => $comment->getId()));
        } else {
            // The comment has never been saved : insert it
            $this->getDb()->insert('comment', $commentData);
            // Get the id of the newly created comment and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $comment->setId($id);
        }
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
        $sql = "SELECT * FROM comment WHERE article_id = ?";
        $results = $this->getDB()->fetchAll($sql, array($articleId));

        $comments = array();
        foreach($results as $row){
            $commentsId = $row["id"];
            $comments[$commentsId] = $this->buildDomainObject($row);
        }
        return $comments;
    }

    /**
     * @param array $row
     * @return array $comment
     */
    protected function buildDomainObject(array $row){
        $comment = new Comment();
        $comment->setId($row["id"]);
        $comment->setContent($row["content"]);


        if(array_key_exists("article_id", $row)) {
            $articleId = $row["article_id"];
            $article = $this->articleDAO->findArticle($articleId);
            $comment->setArticle($article);
        }

        if(array_key_exists("user_id", $row)) {
            $userId = $row["user_id"];
            $author = $this->userDAO->findUserById($userId);
            $comment->setAuthor($author);
        }

        return $comment;
    }

}