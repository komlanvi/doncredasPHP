<?php
/**
 * Created by PhpStorm.
 * User: doncredas
 * Date: 28/03/15
 * Time: 1.28
 */

namespace Blog\Domain;


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

}