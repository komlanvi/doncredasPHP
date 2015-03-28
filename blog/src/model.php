<?php
//return all articles
function getArticles(){
    $db = new PDO('mysql:host=localhost;dbname=myphp;charset=utf8', 'doncredas', 'secret');
    $articles = $db->query('SELECT * FROM article ORDER BY id DESC');
    return $articles;
}