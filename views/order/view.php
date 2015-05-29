<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверена, что хотите удалить заявку?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_sender',
            'user_answer',
            'priority_id',
            'date_create',
            'date_finish',
            'date_update',
            'date_deadline',
            'date_start',
            'status_id',
            'name',
            'description:ntext',
            'category_id',
            'model',
            'serial_number',
            'sender_location',
            'sender_name',
            'sender_position',
            'time_hours',
            'complexity',
        ],
    ]) ?>

</div>
