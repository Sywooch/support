<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'О системе';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        Представляем Вашему вниманию онлайн систему технической поддержки пользователей.
        Вы заходите на сайт, оставляете заявку, а наши специалисты обрабатывают ее в оперативном режиме.
        <br><br>
        Представьте, насколько это?
    </p>

    <div id="block1" class="page-header">
    	<h2>Быстро</h2>
    	<p>
	        Cделать очень быстро, в ногу со временем. Вот наша тенденция на сегодня!
	    </p>
    </div>

    <div id="block2" class="page-header">
    	<h2>Просто</h2>
    	<p>
	        Ничего не усложняем. Интерфейс должен быть понятен каждому!
	    </p>
    </div>

    <div id="block3" class="page-header">
    	<h2>Удобно</h2>
    	<p>
	        Меньше кликов, больше пользы. Вот наш девиз!
	    </p>
    </div>
</div>
