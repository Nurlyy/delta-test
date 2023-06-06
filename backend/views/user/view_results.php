<?php
$question_count = 0;
$right_variants_of_questions = [];
$answers_per_question = [];
$global_counter = 0;
foreach ($questions as $question) {
    foreach ($variants[$question['id']] as $variant) {
        if ($variant['is_right'] == 1) {
            $right_variants_of_questions[$question['id']] = isset($right_variants_of_questions[$question['id']]) ? $right_variants_of_questions[$question['id']] + 1 : 1;
        }
    }
    foreach ($answers[$question['id']] as $answer) {
        $answers_per_question[$question['id']] = isset($answers_per_question[$question['id']]) ? $answers_per_question[$question['id']] + 1 : 1;
    }
}

// var_dump($answers_per_question);exit;
?>

<div class="row col-12">
    <h2 class="text-center">Ваши результаты</h2>
    <br><br><br>
    <?php $counter = 1;
    foreach ($questions as $question) {
        $question_count += 1;
        $temp_variants = [];
        $is_right_count = 0;
        $is_chosen_count = 0;
        $is_chosen_right = false;
        $chosen_right_variants = [];
        $temp_chosen_variants = [];

        foreach ($variants[$question['id']] as $variant) {
            $is_right_count += $variant['is_right'] ? 1 : 0;
            $variant['is_chosen'] = false;
            foreach ($answers[$question['id']] as $answer) {
                if ($answer['variant_id'] == $variant['id']) {
                    $variant['is_chosen'] = true;
                    array_push($temp_chosen_variants, $variant['id']);
                    if ($variant['is_right'] == 1) {
                        array_push($chosen_right_variants, $variant['id']);
                    }
                }
            }
            array_push($temp_variants, $variant);
        }

        if($temp_chosen_variants === $chosen_right_variants && count($chosen_right_variants) == $is_right_count){
            $is_chosen_right = true;
            $global_counter += 1;
        }
        // var_dump($is_chosen_right);
        // echo '<br>';
    // } exit;
    ?>
        <div class="card-body" style="margin-bottom:50px;">
            <div class="card" style="width: 90%; margin-left: auto; margin-right:auto;">
                <div class="card-header <?= ($is_chosen_right) ? 'true' : 'false' ?>">
                    <h4><?php $t = substr($question['title'], 3);
                        echo $question_count . ") " . $t ?></h4>
                    <?= ($question['code_text'] != '') ? '<pre class="code_text">' . $question['code_text'] . '</pre>' : '' ?>
                </div>
                <div class="card-body">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php
                            foreach ($temp_variants as $variant) { ?>
                                <label style="display:flex;flex-direction:row;align-items:center;">
                                    <input <?= $is_right_count > 1 ? "class='form-check-input' type='checkbox'" : 'type="radio"' ?> id="<?= $variant['id'] ?>" name="variant_id_<?= $question['id'] ?>" value="<?= $variant['id'] ?>" <?= ($variant['is_chosen']) ? 'checked' : '' ?> disabled>
                                    <span style="margin-left:10px;" class="wrappable text-<?= ($variant['is_chosen'] && $variant['is_right']) ? 'success' : (($variant['is_chosen'] && !$variant['is_right']) ? 'danger' : ((!$variant['is_chosen'] && $variant['is_right']) ? 'success' : '')) ?>"><pre style="margin-bottom:0;"><?= $variant['title'] ?></pre></span>
                                </label>
                                <br>
                            <?php }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
        $counter++;
                            }
    

    $result = round($global_counter / $question_count * 100, 1);
    ?>
    <div class="col-12">
        <h2>Общий результат: <div class="btn btn-<?= ($result < 50) ? 'warning' : 'primary' ?>"><?= $result ?>%</div>
        </h2>
    </div>

</div>