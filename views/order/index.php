<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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
            'user_answer',
            'priority_id',
            'date_create',
            'date_finish',
            //'date_update',
            'date_deadline',
            //'date_start',
            'status_id',
            'name',
            //'description:ntext',
            'category_id',
            'time_hours:datetime',
            'complexity',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
