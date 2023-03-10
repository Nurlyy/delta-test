<?php $count_questions = [];
$count_answers = [];
$right_answers = [];
$unfinished_themes = 0;
$finished_themes = 0;
foreach ($themes as $theme) {
    $count_questions[$theme['id']] = 0;
    $count_answers[$theme['id']] = 0;
    foreach ($questions as $question) {
        if ($question['theme_id'] == $theme['id']) {
            $count_questions[$theme['id']] = isset($count_questions[$theme['id']]) ? $count_questions[$theme['id']] + 1 : 1;
            foreach ($answers as $answer) {
                if ($answer['question_id'] == $question['id']) {
                    // var_dump($answer['is_right']);exit;
                    if($answer['is_right'] == 1 ){$right_answers[$theme['id']] = isset($right_answers[$theme['id']]) ? $right_answers[$theme['id']] + 1 : 1;}
                    $count_answers[$theme['id']] = isset($count_answers[$theme['id']]) ? $count_answers[$theme['id']] + 1 : 1;
                    continue 2;
                }
            }
        }
    }
    // var_dump($count_answers);
    // var_dump($count_questions);
    // exit;
    if ($count_answers[$theme['id']] >= 0 && $count_answers[$theme['id']] < $count_questions[$theme['id']]) {
        $unfinished_themes++;
    }
    if ($count_answers[$theme['id']] == $count_questions[$theme['id']]) {
        $finished_themes++;
    }
    // var_dump($right_answers);exit;
}

?>
<div class="site-index">

    <div class="row col-12">
        <h1 class="title-text" style="margin-top:10px;margin-bottom:25px; font-size:60px;" class="text-center">Delta <span>Testing</span></h1>
        <div class="panel panel-default">
            <div class="panel-body">
                <?php
                if ($unfinished_themes > 0) {
                ?>
                    <div class="card" style="width: 90%; margin-left: auto; margin-right:auto;">

                        <div class="card-header">
                            <h4>Список тем для теста:</h4>
                        </div>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($themes as $theme) {
                                if ($count_answers[$theme['id']] != $count_questions[$theme['id']]) {
                            ?>
                                    <li class="list-group-item">
                                        <div><?= $theme->name ?>
                                            <button style="float:right; position:relative;" class="button" disabled>
                                                <a style="text-decoration:none; color:white;" href="<?php if ($count_answers[$theme['id']] < $count_questions[$theme['id']]) {
                                                                                                        echo '/site/test-page?theme_id=' . $theme->id . '&q_count=' . $count_answers[$theme['id']];
                                                                                                    } ?>">
                                                    <?php if ($count_answers[$theme['id']] == 0) {
                                                        echo 'Начать';
                                                    } else if ($count_answers[$theme['id']] > 0 && $count_answers[$theme['id']] < $count_questions[$theme['id']]) {
                                                        echo 'Продолжить';
                                                    } ?>
                                                </a>
                                            </button>
                                            <div style="float:right; position:relative; margin-right:30px;" class="btn btn-warning">
                                                <?= $count_answers[$theme['id']] ?>/<?= $count_questions[$theme['id']] ?>
                                            </div>
                                        </div>
                                    </li>
                            <?php }
                            } ?>
                        </ul>
                    </div>
                <?php }
                if ($finished_themes > 0) {
                ?>
                    <div class="card" style="width: 90%; margin-left: auto; margin-right:auto; <?= ($unfinished_themes > 0)?' margin-top:70px;':'' ?>">
                        <div class="card-header">
                            <h4>Список пройденных тестов:</h4>
                        </div>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($themes as $theme) {
                                if ($count_answers[$theme['id']] == $count_questions[$theme['id']]) {
                            ?>
                                    <li class="list-group-item">
                                        <div><?= $theme->name ?>
                                            <button style="float:right; position:relative;" class="button" disabled>
                                                <a style="text-decoration:none; color:white;" href="<?php if ($count_answers[$theme['id']] == $count_questions[$theme['id']]) {
                                                                                                        echo '/site/view-results?theme_id=' . $theme['id'];
                                                                                                    } ?>">
                                                    <?php if ($count_answers[$theme['id']] == $count_questions[$theme['id']]) {
                                                        echo 'Посмотреть результаты';
                                                    } ?>
                                                </a>
                                            </button>
                                            <div style="float:right; position:relative; margin-right:30px;" class="btn btn-success">
                                                <?= $right_answers[$theme['id']] ?>/<?= $count_questions[$theme['id']] ?>
                                            </div>
                                            <div style="float:right; position:relative; margin-right:30px;" class="btn btn-warning">
                                                <?= $count_answers[$theme['id']] ?>/<?= $count_questions[$theme['id']] ?>
                                            </div>
                                        </div>
                                    </li>
                            <?php }
                            } ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </div>


    </div>

</div>