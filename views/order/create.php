<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Order */
/* @var $category app\models\Category */
/* @var $priority app\models\Priority */

$this->title = 'Создание новой заявки';
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'category' => $category,
        'priority' => $priority
    ]) ?>

</div>
