<?php
$is_right_count = 0;
foreach ($variants as $variant) {
    if ($variant->is_right == 1) {
        $is_right_count += 1;
    }
}

// var_dump($answers);exit;

?>

<div class="row col-12">
    <p class='text-center text-bold' style="font-size: 30px; font-style: bold;">Вопрос <?= $question_count ?>:</p>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="card">
                <div class="card-header">
                    <h4><?php $t=substr($question->title, 3); echo $question_count.") ".$t ?></h4>
                    <?= ($question->code_text != '') ? '<pre class="code_text">' . $question->code_text . '</pre>' : '' ?>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body" style=" padding:10px;">
                        <?php foreach ($variants as $variant) { ?>
                            <label>
                                <input <?= isset($variant_answers[$variant->id]) ? "checked" : "" ?> <?= $is_right_count > 1 ? "class='form-check-input' type='checkbox'" : 'type="radio"' ?> onclick="document.getElementById('submitbutton').disabled=false;" id="variant_id" name="variant_id" value="<?= $variant->id ?>" required>
                                <span class="wrappable"><?= $variant->title ?></span>
                            </label>
                            <br>
                            <!-- <label for="child"></label> -->
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button disabled id="submitbutton" style="position:relative; text-align:center; width:20%; margin-left:auto; margin-right:auto; margin-top:30px;" class="btn btn-success">Ответить</button>

    <div class="col-10 row justify-content-center" style="margin-left:auto; margin-right:auto; margin-top:50px;">
        <div class="row text-center justify-content-center">
            <h1>Все вопросы</h1>
        </div>
        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr; width:1200px !important; max-width:1200px !important;">
            <?php
             $counter = 1;
             foreach ($questions as $q) { ?>
                <div class="card card-body" style="<?= isset($answers[$q['id']]) ? 'background-color:#05eeff;':'' ?>width:59px; padding:1px; height:fit-content; max-width:59px; text-align:center;padding-top:5px;padding-bottom:5px; margin-top:2px; <?= $counter == $question_count ? "background-color:#03fca9;" : '' ?>">
                    <a style="text-decoration:none; color:black;" href="/site/test-page?theme_id=<?= $theme->id ?>&q_count=<?= $counter ?>"><?= $counter ?></a>
                </div>
            <?php $counter++; } ?>
        </div>
    </div>

</div>

<?php $this->registerJs("
    $('#submitbutton').click(function() {
        chosen = {};
        checked = $('#variant_id:checked');

        var checkedValues = $('#variant_id:checked').map(function() {
            return this.value;
        }).get();

        // console.log(checkedValues);

        if(checkedValues.length === 0){
            document.getElementById('submitbutton').disabled=true;
            return;
        }

        $.ajax({
            url: '/site/answer',
            type: 'POST',
            data: {
                variants_id: checkedValues,
                question_id: {$question->id},
                '" . Yii::$app->request->csrfParam . "': '" . Yii::$app->request->csrfToken . "'
            },
            success: function(data) {
                // console.log(data);
                if (data == '1') {
                    console.log(data);
                    
                        window.location.href = '/site/test-page?theme_id=" . $theme->id . "&q_count=".$question_count+1 ."';
                    
                } else {
                    console.log(data);
                }
            }
        });
    });

    
") ?>