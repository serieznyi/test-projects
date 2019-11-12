<?php
/* @var $content string */
/* @var $language string */
/* @var $charset string */
/* @var $title string */
/* @var $username string */

?>
<!DOCTYPE html>
<html lang="<?= $language ?>">
<head>
    <meta charset="<?= $charset ?>">
    <meta name="viewport" content="user-scalable=yes">
    <title><?= $title ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="/">PhoneBook</a>
                <ul class="navbar-nav mr-auto">
                    <?if (App\Web\Util\AuthUtil::isAuthorized()) : ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="/logout">Logout (<?= $username ?>)</a>
                    </li>
                    <? endif ?>
                </ul>
            </nav>
        </header>
        <div class="row">
            <div class="col-lg">
                <h1><?=$title?></h1>
                <?= $content ?>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>
    <script src="/js/main.js"></script>
</body>
</html>
