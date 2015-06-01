<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Group;

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

    <?
        $isUser = (Yii::$app->user->identity->group_id == Group::find()->where(['code' => 'user'])->one()->id);
        $attributes = [
            'id',
            [
                'attribute' => 'user_sender',
                'format' => 'raw',
                'value' => Html::a(
                    $user->getFio(),
                    Yii::$app->urlManager->createUrl([
                        '/user/view',
                        'id' => $user->id
                    ])
                )
            ]
        ];

        if (!empty($manager)) {
            $attributes[] = [
                'attribute' => 'user_answer',
                'format' => 'raw',
                'value' => $isUser ? $manager->getFio() : Html::a(
                    $manager->getFio(),
                    Yii::$app->urlManager->createUrl([
                        '/user/view',
                        'id' => $manager->id
                    ])
                )
            ];
        }

        $attributes = array_merge($attributes, [
            [
                'attribute' => 'priority_id',
                'format' => 'raw',
                'value' => $priority->name
            ],
            'date_create'
        ]);

        if (!empty($model->date_finish)) {
            $attributes[] = 'date_finish';
        }

        $attributes = array_merge($attributes, [
            'date_update',
            'date_deadline'
        ]);

        if (!empty($model->date_start)) {
            $attributes[] = 'date_start';
        }

        $attributes = array_merge($attributes, [
            [
                'attribute' => 'status_id',
                'format' => 'raw',
                'value' => $status->name
            ],
            'name',
            'description:ntext',
            [
                'attribute' => 'category_id',
                'format' => 'raw',
                'value' => $category->name
            ]
        ]);

        if (!empty($model->model)) {
            $attributes[] = 'model';
        }

        if (!empty($model->serial_number)) {
            $attributes[] = 'serial_number';
        }

        $attributes = array_merge($attributes, [
            'sender_location',
            'sender_name',
            'sender_position'
        ]);

        if (
            !$isUser
            &&
            !empty($model->time_hours)
            &&
            !empty($model->complexity)
        ) {
            $attributes = array_merge($attributes, [
                'time_hours',
                'complexity'
            ]);
        }
    ?>

    <?= DetailView::widget([
        'model'      => $model,
        'attributes' => $attributes
    ]) ?>

</div>
