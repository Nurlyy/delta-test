<?php

?>

<div class="row col-12">
    <h2 class="text-center">Результаты пользователя "<?= $user->username ?>"</h2>
    <br><br><br><br>
    <?php $counter = 1;
    foreach ($questions as $question) {

    ?>
        <div class="card-body" style="margin-bottom:50px;">

            <div class="card" style="width: 90%; margin-left: auto; margin-right:auto;">
                <div class="card-header">
                    <h4><?= $question['title'] ?></h4>
                </div>
                <div class="card-body">
                    <div class="panel panel-default">
                        <div class="panel-body">

                            <?php foreach ($answers as $answer) {
                                if ($answer['question_id'] == $question['id']) {
                                    foreach ($variants as $variant) {
                                        foreach ($variant as $v) {
                                            if ($v['question_id'] == $question['id']) {
                                                foreach ($right_answers as $right_answer) {
                                                    if ($right_answer['question_id'] == $question['id']) {
                                                        if ($v['id'] == $answer['variant_id'] && $v['id'] == $right_answer['variant_id']) { ?>
                                                            <input type="radio" id="variant_id" name="variant_id_<?= $question['id'] ?>" value="<?= $v['id'] ?>" checked disabled>
                                                            <label for="child" class="text-success"><strong><?= $v['title'] ?></strong></label><br>
                                                        <?php } else if ($v['id'] == $answer['variant_id'] && $v['id'] != $right_answer['variant_id']) { ?>
                                                            <input type="radio" id="variant_id" name="variant_id_<?= $question['id'] ?>" value="<?= $v['id'] ?>" checked disabled>
                                                            <label for="child" class="text-danger"><strong><?= $v['title'] ?></strong></label><br>
                                                        <?php } else if ($v['id'] == $right_answer['variant_id']) { ?>
                                                            <input type="radio" id="variant_id" name="variant_id_<?= $question['id'] ?>" value="<?= $v['id'] ?>" checked disabled>
                                                            <label for="child" class="text-success"><strong><?= $v['title'] ?></strong></label><br>
                                                        <?php } else { ?>
                                                            <input type="radio" id="variant_id" name="variant_id_<?= $question['id'] ?>" value="<?= $v['id'] ?>" disabled>
                                                            <label for="child"><?= $v['title'] ?></label><br>
                                <?php }
                                                        continue 2;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                ?>

                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
        $counter++;
    }
    $count_questions = 0;
    $count_answers = 0;
    $right_answers = 0;
    foreach ($questions as $question) {
        if ($question['theme_id'] == $theme['id']) {
            $count_questions++;
            foreach ($answers as $answer) {
                if ($answer['question_id'] == $question['id']) {
                    if ($answer['is_right'] == 1) {
                        $right_answers++;
                    }
                    $count_answers++;
                }
            }
        }
    }

    if ($count_answers > 0) {
        $result = round($right_answers / $count_answers * 100, 1);
    } else {
        $result = 0;
    }
    ?>

    <div class="col-12">
        <h2>Общий результат: <div class="btn btn-<?= ($result < 50) ? 'warning' : 'primary' ?>"><?= $result ?>%</div>
        </h2>
    </div>


</div>