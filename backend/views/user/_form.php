<?php

use common\models\Languages;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var common\models\Theme $model */
/** @var yii\widgets\ActiveForm $form */
$languages = Languages::find()->asArray()->all();
$temp = [];
foreach($languages as $language){
    $temp[$language['id']] = $language['name'];
}
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
    <?= $form->field($users_languages, 'language_id')->dropDownList($temp, ['required' => true, 'prompt' => [
        'text' => 'Выберите язык сдачи тестов',
        'options' => ['disabled' => true, 'Selected' => true],
        'value' => '-1',
    ]])->label('Язык:') ?>
    <br><br>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>
</div>