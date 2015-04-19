<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?//= $form->field($model, 'user_sender')->textInput() ?>

    <?//= $form->field($model, 'user_answer')->textInput() ?>

    <?//= $form->field($model, 'date_create')->textInput() ?>

    <?//= $form->field($model, 'date_finish')->textInput() ?>

    <?//= $form->field($model, 'date_update')->textInput() ?>

    <?//= $form->field($model, 'date_start')->textInput() ?>

    <?//= $form->field($model, 'status_id')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'category_id')->DropDownList(ArrayHelper::map($category,'id','name')) ?>
    
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    
    <?= $form->field($model, 'priority_id')->DropDownList(ArrayHelper::map($priority,'id','name')) ?>

    <?= $form->field($model, 'date_deadline')->widget(DatePicker::classname(), [
        'language' => 'ru',
        'dateFormat' => 'yyyy-MM-dd',
    ]) ?>

    <?//= $form->field($model, 'time_hours')->textInput() ?>

    <?//= $form->field($model, 'complexity')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
