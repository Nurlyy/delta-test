<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Question $model */

$this->title = 'Создать вопрос для темы "' . $theme->name . '"';
$this->params['breadcrumbs'][] = ['label' => 'Themes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Update', 'url' => ['update', 'id' => $theme->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="question-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_question_form', [
        'question' => $question,
        'theme' => $theme,
        'variant1' => $variant1,
        'variant2' => $variant2,
        'variant3' => $variant3,
        'variant4' => $variant4,
        'right_answer' => $right_answer,
    ]) ?>

</div>