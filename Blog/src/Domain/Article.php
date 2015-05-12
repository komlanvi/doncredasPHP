<?php
/**
 * Created by PhpStorm.
 * User: doncredas
 * Date: 28/03/15
 * Time: 1.28
 */

namespace Blog\Domain;


use Author;
use DateTime;

class Article {

    /**
     * Article id
     * @var string
     */
    private $id;

    /**
     * Article title
     * @var string
     */
    private $title;

    /**
     * Article content
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $addedTime;

    /**
     * @var User
     */
    private $author;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return String
     */
    public function getAddedTime()
    {
        return $this->addedTime;
    }

    /**
     * @param String $addedTime
     */
    public function setAddedTime($addedTime)
    {
        $this->addedTime = $addedTime;
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param User $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }



}