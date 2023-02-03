<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Question $question */
/** @var yii\widgets\ActiveForm $form */

if (isset($variant1->id) && isset($variant2->id) && isset($variant3->id) && isset($variant4->id)) {
    $variants = [];
    $variants = [$variant1->id => '1', $variant2->id => '2', $variant3->id => '3', $variant4->id => '4'];
} else {
    $variants = [1 => "1", 2 => "2", 3 => "3", 4 => "4"];
}

?>


<div class="question-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($question, 'title')->textarea(['rows' => 6]) ?>
    <br>
    <?= $form->field($question, 'theme_id')->dropDownList([$theme['id'] => $theme['name']]) ?>
    <br>
    <h3>Варианты ответа</h3>
    <?= $form->field($variant1, '[1]title')->textInput(['id' => 'variant1', 'placeholder' => 'Первый вариант', 'style' => 'width:500px;margin-left:30px;'])->label('1)', ['style' => 'float:left;']) ?>
    <?= $form->field($variant2, '[2]title')->textInput(['id' => 'variant2', 'placeholder' => 'Второй вариант', 'style' => 'width:500px;margin-left:30px;'])->label('2)', ['style' => 'float:left;']) ?>
    <?= $form->field($variant3, '[3]title')->textInput(['id' => 'variant3', 'placeholder' => 'Третьий вариант', 'style' => 'width:500px;margin-left:30px;'])->label('3)', ['style' => 'float:left;']) ?>
    <?= $form->field($variant4, '[4]title')->textInput(['id' => 'variant4', 'placeholder' => 'Четвертый вариант', 'style' => 'width:500px;margin-left:30px;'])->label('4)', ['style' => 'float:left;']) ?>
    <?= $form->field($right_answer, 'variant_id')->dropDownList($variants, ['required' => true, 'prompt' => [
        'text' => 'Выберите правильный вариант',
        'options' => ['disabled' => true, 'Selected' => true],
        'value' => '-1',
    ]])->label('Правильный ответ:') ?>


    <br>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>