DONCREDASPHP
============

My personal home page
---------------------

Visit [doncredas](http://www.doncredas.com)  
* based on Silex (The PHP micro-framework based on the Symfony2 Components) 
* Uses Doctrine DBAL for accessing the database.
* Twig for its templates.
* And the UI is based on Bootstrap 3.  

The API
-------

The application provides a REST API for managing articles.  

GET api/articles - Provides a listing of articles.  
GET api/article/{article_id} - Retrieves the single article.  
POST api/article - Creates a new article.  
DELETE api/article/{article_id} delete a single article.  

The output format is JSON.

