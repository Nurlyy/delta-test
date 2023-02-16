<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Question $model */

$this->title = 'Изменить вопрос';
$this->params['breadcrumbs'][] = ['label' => 'Языки', 'url' => ['/languages']];
$this->params['breadcrumbs'][] = ['label' => $language->name, 'url' => ['/languages/view/'.$language->id]];
$this->params['breadcrumbs'][] = ['label' => 'Темы', 'url' => ['/languages/'.$language->id.'/theme']];
$this->params['breadcrumbs'][] = ['label' => $theme->name, 'url' => ['/languages/'.$language->id.'/theme/'.$theme->id]];
$this->params['breadcrumbs'][] = ['label' => 'Вопросы', 'url' => ['/languages/'.$language->id.'/theme/'.$theme->id.'/questions']];

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="question-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_question_form', [
        'question' => $question,
        'theme' => $theme,
        'variants' => $variants,
        // 'variant1' => $variant1,
        // 'variant2' => $variant2,
        // 'variant3' => $variant3,
        // 'variant4' => $variant4,
        // 'right_answer' => $right_answer,
    ]) ?>

</div>