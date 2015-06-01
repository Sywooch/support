<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = 'Редактирование заявки: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="order-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' 		=> $model,
        'category' 		=> $category,
        'priority' 		=> $priority,
        'status' 		=> $status,
        'categories' 	=> $categories,
        'priorities' 	=> $priorities,
        'statuses' 		=> $statuses,
        'userSenders' 	=> $userSenders,
        'userAnswers' 	=> $userAnswers,
        'userSender' 	=> $userSender,
        'userAnswer' 	=> $userAnswer,
        'role' 			=> $role,
        'create' 		=> false
    ]) ?>

</div>
