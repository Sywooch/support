<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        Оопс, на стороне сервера произошла ошибка. Мы уже знаем об этом :)
    </p>
    <p>
        Пожайлуста на волнуйтесь, в ближайшее время проблема будет устранена.
    </p>

</div>
