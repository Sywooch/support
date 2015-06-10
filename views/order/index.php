<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Category;
use app\models\Priority;
use app\models\Status;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?//echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить заявку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            //'user_sender',
            [
                'attribute' => 'userAnswer',
                'value' => 'userAnswer.last_name'
            ],
            [
                'attribute' => 'priority',
                'value' => 'priority.name'
            ],
            [
                'attribute' => 'date_create',
                'format' => ['date', 'php:d.m.Y H:i']
            ],
            [
                'attribute' => 'date_finish',
                'format' => ['date', 'php:d.m.Y H:i']
            ],
            //'date_update',
            [
                'attribute' => 'date_deadline',
                'format' => ['date', 'php:d.m.Y H:i']
            ],
            //'date_start',
            [
                'attribute' => 'status',
                'value' => 'status.name'
            ],
            'name',
            //'description:ntext',
            [
                'attribute' => 'category',
                'value' => 'category.name'
            ],
            //'model',
            //'serial_number',
            //'sender_location',
            //'sender_name',
            //'sender_position',
            'time_hours',
            'complexity',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
