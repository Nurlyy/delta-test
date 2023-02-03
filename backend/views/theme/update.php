<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
use common\models\Question;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Theme $model */

$this->title = 'Изменить тему: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Темы', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Изменить '.$model->name;
?>
<div class="theme-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <br>
    <br>

    <h1>Вопросы</h1>

    <p>
        <?= Html::a('Создать вопрос', ['create-question', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title:ntext',
            
            [
                'class' => ActionColumn::class,
                'template' => '{update} {delete}',
                'urlCreator' => function ($action, Question $model, $key, $index, $column) {
                    return Url::toRoute([$action.'-question', 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>