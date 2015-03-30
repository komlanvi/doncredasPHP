<?php
/**
 * Created by PhpStorm.
 * User: doncredas
 * Date: 29/03/15
 * Time: 23.33
 */

namespace Blog\Domain;


class Comment {

    /**
     * Comment id
     *
     * @var integer
     */
    private $id;

    /**
     * Comment author
     *
     * @var string
     */
    private $author;

    /**
     * Comment content
     *
     * @var string
     */
    private $content;

    /**
     * Associated article
     *
     * @var \Blog\Domain\Article
     */
    private $article;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return Article
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param Article $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param Article $article
     */
    public function setArticle($article)
    {
        $this->article = $article;
    }

}