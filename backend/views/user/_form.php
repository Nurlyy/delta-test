<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var common\models\Theme $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="theme-form">
<?php Pjax::begin(); ?>
    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <br>
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <br>
    <?= $form->field($model, 'password_hash')->textInput(['maxlength' => true, 'type' => 'password'])->label('Password') ?>
    <br>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>
</div>