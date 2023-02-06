<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>


    <div class="site-index">

        <div class="row col-12">
            <h1 style="margin-top:10px;margin-bottom:25px;" class="text-center">Delta Testing</h1>
            <div class="card" style="width: 90%; margin-left: auto; margin-right:auto;">
                <div class="card-header">
                    <h4>Список тем для теста:</h4>
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($themes as $theme) {
                        $count_questions = 0;
                        $count_answers = 0;
                        foreach ($questions as $question) {
                            if ($question['theme_id'] == $theme['id']) {
                                $count_questions++;
                                foreach ($answers as $answer) {
                                    if ($answer['question_id'] == $question['id'])
                                        $count_answers++;
                                }
                            }
                        }
                    ?>
                        <li class="list-group-item">
                            <div><?= $theme->name ?>
                                <button style="float:right; position:relative;" class="button" disabled>
                                <a style="text-decoration:none; color:white;" 
                                href="<?php if($count_answers < $count_questions){
                                    echo '/site/test-page?theme_id='.$theme->id.'&q_count='.$count_answers;
                                } else if($count_answers == $count_questions){
                                    echo '/site/view-results?theme_id='.$theme->id;
                                } ?>">
                                <?php if($count_answers == 0){
                                    echo 'Начать';
                                } else if($count_answers > 0 && $count_answers < $count_questions){
                                    echo 'Продолжить';
                                } else if($count_answers == $count_questions){
                                    echo 'Посмотреть результаты';
                                } ?>
                                </a>
                                </button>
                                <div style="float:right; position:relative; margin-right:30px;" class="btn btn-warning">
                                    <?= $count_answers ?>/<?= $count_questions ?>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>

        </div>

    </div>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage();
