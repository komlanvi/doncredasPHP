<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../web/blogstyle.css" rel="stylesheet">
    <title>Doncredas - Blog</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
<header>
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Blog</a>
            </div>

            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="https://it.linkedin.com/in/doncredas/" target="_blank"><i class="fa fa-linkedin-square"></i> LinkedIn</a></li>
                    <li><a href="https://www.facebook.com/doncredas" target="_blank"><i class="fa fa-facebook-square"></i> Facebook</a></li>
                    <li><a href="https://twitter.com/doncredas" target="_blank"><i class="fa fa-twitter-square"></i> Twitter</a></li>
                    <li><a href="#" target="_blank"><i class="fa fa-google-plus-square"></i> Google+</a></li>
                    <li><a href="https://github.com/doncredas" target="_blank"><i class="fa fa-github-square"></i> GitHub</a></li>
                </ul>
            </div>
        </div>

    </div>
</header>
<div class="jumbotron" style="padding-bottom: 0px; text-align: center;">
    <div class="container">
        <header>
            <h1>Doncredas - Blog</h1>
        </header>
    </div>
</div>
<div class="container">
    <article>
        <?php foreach($articles as $article): ?>
            <h2> <?= $article['titre']; ?> </h2>
            <p> <?= $article['contenu']; ?> </p>
        <?php endforeach; ?>
    </article>
    <hr>
    <footer>
        © doncredas 2015 <span class="pull-right"><a href="https://github.com/doncredas/doncredasPHP" target="_blank">Code source</a></span>
    </footer>
</div>
</body>
</html>