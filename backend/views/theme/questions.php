<?php

use common\models\Theme;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Темы';
$this->params['breadcrumbs'][] = ['label' => 'Языки', 'url' => ['/languages']];
$this->params['breadcrumbs'][] = ['label' => $language->name, 'url' => ['/languages/view/'.$language->id]];
$this->params['breadcrumbs'][] = ['label' => 'Темы', 'url' => ['/languages/'.$language->id.'/theme']];
$this->params['breadcrumbs'][] = ['label' => $theme->name, 'url' => ['/languages/'.$language->id.'/theme/'.$theme->id]];
$this->params['breadcrumbs'][] = 'Вопросы';

$question_count = 0;
?>
<div class="theme-index">

    

    <div class="col-12 row">
        <div class="card">

            <div class="text-center mt-4 mb-4">
                <h1>Вопросы темы <?= Html::encode($theme->name) ?></h1>
            </div>
            <div class="card-body">
                <div class="card mb-3">
                    <div class="card-body create-language text-center">
                        <h3>
                        <?= Html::a('<strong>Создать новый вопрос</strong>', ['/languages/'.$language->id."/theme/".$theme->id.'/create-question'], ['class' => 'btn']) ?>
                        </h3>
                    </div>
                </div>

                <?php foreach ($questions as $question) {
                    $question_count += 1; ?>
                    <div class="card language-item mb-3">
                        <div class="card-header" style="position:relative;">
                            <?php $t=substr($question['title'], 3); echo $question_count.") ".$t ?>
                            <div style="float:right; position:relative;">
                                <div class="btn btn-warning " style="float:right; position:relative;margin-right:50px;"><a class="btn-a" href="/backend/languages/<?= $language->id ?>/theme/<?= $theme->id ?>/update-question/<?= $question->id ?>">Изменить</a></div>
                                <div class="btn btn-danger " style="float:right; position:relative;margin-right:50px;"><a class="btn-a" href="/backend/languages/<?= $language->id ?>/theme/<?= $theme->id ?>/delete-question/<?= $question->id ?>">Удалить</a></div>
                            </div>
                            
                        </div>

                    </div>
                <?php } ?>
            </div>
        </div>

    </div>

</div>
