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

    <?if ($role == 'user'):?>
        <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
        <?= $form->field($model, 'category_id')->DropDownList(ArrayHelper::map($categories,'id','name')) ?>
        
        <div <?if($model->category_id != 1):?>style="display:none"<?endif?> class="order-detail">
            <?= $form->field($model, 'model')->textInput()->hint('Например, Samsung U28D590D') ?>
            <?= $form->field($model, 'serial_number')->textInput()->hint('Например, ADCD12356789') ?>
        </div>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
        <?= $form->field($model, 'sender_location')->textInput() ?>
        <?= $form->field($model, 'sender_name')->textInput() ?>
        <?= $form->field($model, 'sender_position')->textInput() ?>
        <?= $form->field($model, 'priority_id')->DropDownList(ArrayHelper::map($priorities,'id','name')) ?>
        <?= $form->field($model, 'date_deadline')->widget(DateTimePicker::classname(), [
            'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
            'removeButton' => false,
            'pickerButton' => ['icon' => 'time'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy hh:ii',
                'startDate' => (new DateTime())->add(new DateInterval('P1D'))->format('d.m.Y H:i')
            ]
        ]) ?>
    <?endif;?>

    <?if ($role == 'manager'):?>
        <?
        $attributes = [
            [
                'attribute' => 'user_sender',
                'format' => 'raw',
                'value' => Html::a($userSender->getFio(), Yii::$app->urlManager->createUrl([
                    'user/view',
                    'id' => $userSender->id
                ]))
            ],
            [
                'attribute' => 'user_answer',
                'format' => 'raw',
                'value' => Html::a($userAnswer->getFio(), Yii::$app->urlManager->createUrl([
                    'user/view',
                    'id' => $userAnswer->id
                ]))
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
        ?>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => $attributes
        ]) ?>
        <?= $form->field($model, 'time_hours')->textInput(['maxlength' => 4])->hint('Время в часах, отведенное на выполнение заявки специалистом') ?>
        <?= $form->field($model, 'complexity')->textInput(['maxlength' => 2])->hint('Количество баллов за выполнения заявки. Целое число. Минимум - 1, максимум - 10') ?>
        <?= $form->field($model, 'status_id')->DropDownList(ArrayHelper::map($status,'id','name')) ?>
    <?endif;?>

    <?if ($role == 'admin'):?>
        <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
        <?= $form->field($model, 'category_id')->DropDownList(ArrayHelper::map($categories,'id','name')) ?>
        
        <div <?if($model->category_id != 1):?>style="display:none"<?endif?> class="order-detail">
            <?= $form->field($model, 'model')->textInput()->hint('Например, Samsung U28D590D') ?>
            <?= $form->field($model, 'serial_number')->textInput()->hint('Например, ADCD12356789') ?>
        </div>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
        <?= $form->field($model, 'user_sender')->DropDownList(ArrayHelper::map($userSenders,'id', function($user) {
            $fio = trim($user->name.' '.$user->second_name.' '.$user->last_name);
            $fio = empty($fio) ? $user->login : $fio;
            return ('['.$user->id.'] '.$fio);
        })) ?>
        <?= $form->field($model, 'user_answer')->DropDownList(ArrayHelper::map($userAnswers,'id', function($user) {
            $fio = trim($user->name.' '.$user->second_name.' '.$user->last_name);
            $fio = empty($fio) ? $user->login : $fio;
            return ('['.$user->id.'] '.$fio);
        })) ?>
        <?= $form->field($model, 'sender_location')->textInput() ?>
        <?= $form->field($model, 'sender_name')->textInput() ?>
        <?= $form->field($model, 'sender_position')->textInput() ?>
        <?= $form->field($model, 'priority_id')->DropDownList(ArrayHelper::map($priorities,'id','name')) ?>
        <?= $form->field($model, 'date_deadline')->widget(DateTimePicker::classname(), [
            'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
            'removeButton' => false,
            'pickerButton' => ['icon' => 'time'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy hh:ii',
                'startDate' => (new DateTime())->add(new DateInterval('P1D'))->format('d.m.Y H:i')
            ]
        ]) ?>
        <?= $form->field($model, 'time_hours')->textInput(['maxlength' => 4])->hint('Время в часах, отведенное на выполнение заявки специалистом') ?>
        <?= $form->field($model, 'complexity')->textInput(['maxlength' => 2])->hint('Количество баллов за выполнения заявки. Целое число. Минимум - 1, максимум - 10') ?>
        <?= $form->field($model, 'status_id')->DropDownList(ArrayHelper::map($statuses,'id','name')) ?>
    <?endif;?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?
        $this->registerJs(
            '$("document").ready(function() {
                $("#order-category_id").change(function() {
                    var value = $(this).val();
                    if (value == 1) { // small huck
                        $(".order-detail").show();
                    } else {
                        $(".order-detail").hide();
                        $("#order-model").val("");
                        $("#order-serial_number").val("");
                    }
                });
            });'
        );
    ?>
</div>
