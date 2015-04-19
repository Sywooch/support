<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?=$this->title?></h3>
            </div>
            <div class="panel-body">
                <? $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'fieldConfig' => [
                        'template' => "<div class=\"form-group\">{input}\n{error}</div>"
                    ]
                ])?>
                <fieldset>
                    <?= $form->field($model, 'login')->textInput(['placeholder' => $model->getAttributeLabel('login')]) ?>

                    <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

                    <?= $form->field($model, 'rememberMe', ['template' => "<div class=\"checkbox\"><label>{input} ".$model->getAttributeLabel('rememberMe')."</label></div>"])->checkbox([], false) ?>

                    <?= Html::submitButton('Войти', ['class' => 'btn btn-lg btn-success btn-block', 'name' => 'login-button']) ?>
                </fieldset>
                <? ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
