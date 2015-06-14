<?php
/* @var $this yii\web\View */
$this->title = 'Support App';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Добро пожаловать!</h1>

        <p class="lead">Система технической поддержки пользователей</p>

        <p><a class="btn btn-lg btn-success" href="<?=Yii::$app->urlManager->createUrl(['site/about'])?>">Познакомиться</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Быстро</h2>

                <p>Всегда в онлайне, не нужно скачивать никаких приложений.</p>

                <p><a class="btn btn-default" href="<?=Yii::$app->urlManager->createUrl(['site/about', '#' => 'block1'])?>">Подробнее</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Просто</h2>

                <p>Все предельно ясно и понятно. Вы заходите на сайт и оставляете заявку.</p>

                <p><a class="btn btn-default" href="<?=Yii::$app->urlManager->createUrl(['site/about', '#' => 'block2'])?>">Подробнее</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Удобно</h2>

                <p>Ничего лишнего. Все под рукой. Удобная панель управления для пользователей.</p>

                <p><a class="btn btn-default" href="<?=Yii::$app->urlManager->createUrl(['site/about', '#' => 'block3'])?>">Подробнее</a></p>
            </div>
        </div>

    </div>
</div>
