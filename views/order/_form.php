<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
//use yii\jui\DatePicker;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=$form->errorSummary($model)?>

    <?if ($create):?>
        <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
        <?= $form->field($model, 'category_id')->DropDownList(ArrayHelper::map($category,'id','name')) ?>
        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
        <?= $form->field($model, 'sender_location')->textInput() ?>
        <?= $form->field($model, 'sender_name')->textInput() ?>
        <?= $form->field($model, 'sender_position')->textInput() ?>
        <?= $form->field($model, 'priority_id')->DropDownList(ArrayHelper::map($priority,'id','name')) ?>
        <?= $form->field($model, 'date_deadline')->widget(DateTimePicker::classname(), [
            'type' => DateTimePicker::TYPE_INPUT,
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy hh:ii',
                'startDate' => (new DateTime())->add(new DateInterval('P1D'))->format('Y-m-d H:i')
            ]
        ]) ?>
    <?else:?>
        <?
        $userName = trim($user->name.' '.$user->last_name);
        $userName = empty($userName) ? $user->login : $userName;
        $userUrl = Yii::$app->urlManager->createUrl([
            'user/view',
            'id' => $user->id
        ]);

        $attributes = [
            [
                'attribute' => 'user_sender',
                'format' => 'raw',
                'value' => Html::a($userName, $userUrl)
            ],
            [
                'attribute' => 'priority_id',
                'format' => 'raw',
                'value' => $priority->name
            ],
            [
                'attribute' => 'date_create',
                'format' => 'raw',
                'value' => (new DateTime($model->date_create))->format('d-m-Y H:i')
            ],
            [
                'attribute' => 'date_deadline',
                'format' => 'raw',
                'value' => (new DateTime($model->date_deadline))->format('d-m-Y H:i')
            ],
            'name',
            'description:ntext',
            [
                'attribute' => 'category_id',
                'format' => 'raw',
                'value' => $category->name
            ],
        ];

        if ($category->id == 1) {
            $attributes[] = 'model';
            $attributes[] = 'serial_number';
        }

        $attributes = array_merge($attributes, [
            'sender_location',
            'sender_name',
            'sender_position'
        ]);
        ?>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => $attributes
        ]) ?>
        <?/*= $form->field($model, 'date_finish')->widget(DateTimePicker::classname(), [
            'type' => DateTimePicker::TYPE_INPUT,
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy hh:ii',
                'startDate' => (new DateTime())->add(new DateInterval('PT2H'))->format('Y-m-d H:i')
            ]
        ])->hint('Планируемая дата завершения заявки')*/ ?>
        <?= $form->field($model, 'time_hours')->textInput(['maxlength' => 4])->hint('Время в часах, отведенное на выполнение заявки специалистом') ?>
        <?= $form->field($model, 'complexity')->textInput(['maxlength' => 2])->hint('Количество баллов за выполнения заявки. Целое число. Минимум - 1, максимум - 10') ?>
        <?= $form->field($model, 'status_id')->DropDownList(ArrayHelper::map($status,'id','name')) ?>
    <?endif?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
