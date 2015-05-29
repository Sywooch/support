<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
/* @var $groups Array app\models\Group */
/* @var $group app\models\Group */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'login')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255, 'value' => '']) ?>

    <? if ($model->isNewRecord || (!$model->isNewRecord && !empty($group) && $group->code == 'admin')):?>
        <?= $form->field($model, 'group_id')->DropDownList(ArrayHelper::map($groups,'id','name')) ?>
    <?endif?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'second_name')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'position')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'workplace')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
