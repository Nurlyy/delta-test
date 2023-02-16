<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
use common\models\Question;
use common\models\Theme;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Theme $model */

$this->title = 'Изменить тему: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Языки', 'url' => ['/languages']];
$this->params['breadcrumbs'][] = ['label' => $language->name, 'url' => ['/languages/view/'.$language->id]];
$this->params['breadcrumbs'][] = ['label' => 'Темы', 'url' => ['/languages/'.$language->id.'/theme']];
$this->params['breadcrumbs'][] = 'Изменить '.$model->name;
?>
<div class="theme-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <br>
    <br>


</div>