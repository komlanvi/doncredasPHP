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
     * @return array
     */
    public function findAll() {
        $sql = "SELECT * FROM comment ORDER BY id DESC";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['id'];
            $entities[$id] = $this->buildDomainObject($row);
        }
        return $entities;
    }

    /**
     * Returns a comment matching the supplied id.
     *
     * @param integer $id The comment id
     * @return Comment
     * @throws \Exception
     */
    public function find($id) {
        $sql = "select * from comment where id = ?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No comment matching id " . $id);
    }

    /**
     * Removes a comment from the database.
     *
     * @param integer $id The comment id
     */
    public function delete($id) {
        // Delete the comment
        $this->getDb()->delete('comment', array('id' => $id));
    }

    /**
     * @param $articleId
     *
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    public function deleteAllByArticle($articleId) {
        $this->getDb()->delete('comment', array('article_id' => $articleId));
    }

    /**
     * Removes all comments for a user
     *
     * @param integer $userId The id of the user
     */
    public function deleteAllByUser($userId) {
        $this->getDb()->delete('comment', array('user_id' => $userId));
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