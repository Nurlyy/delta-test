<?php
$is_right_count = 0;
foreach($variants as $variant){
    if($variant->is_right == 1){
        $is_right_count += 1;
    }
}

?>

<div class="row col-12">
    <p class='text-center text-bold' style="font-size: 30px; font-style: bold;">Вопрос <?= $question_count + 1 ?>:</p>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="card">
                <div class="card-header">
                    <h4><?= $question->title ?></h4>
                    <?= ($question->code_text != '')?'<pre class="code_text">'. $question->code_text .'</pre>':'' ?>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body" style=" padding:10px;">
                        <?php foreach ($variants as $variant) { ?>
                            <label>
                                <input <?= $is_right_count > 1 ? "class='form-check-input' type='checkbox'" : 'type="radio"' ?> onclick="document.getElementById('submitbutton').disabled=false;" id="variant_id" name="variant_id" value="<?= $variant->id ?>" required>
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

    <button disabled id="submitbutton" style="position:relative; text-align:center; width:20%; margin-left:auto; margin-right:auto; margin-top:30px;" class="btn btn-<?= ($issecond == 'true') ? 'primary' : 'success' ?>"><?= ($issecond == 'true') ? 'Следующий' : 'Завершить тест' ?></button>

</div>

<?php $this->registerJs("
    issecond = {$issecond};
    $('#submitbutton').click(function() {
        chosen = {};
        checked = $('#variant_id:checked');

        var checkedValues = $('#variant_id:checked').map(function() {
            return this.value;
        }).get();

        // console.log(checkedValues);

        $.ajax({
            url: '/site/answer',
            type: 'POST',
            data: {
                variants_id: checkedValues,
                question_id: {$question->id},
                '" . Yii::$app->request->csrfParam . "': '" . Yii::$app->request->csrfToken . "'
            },
            success: function(data) {
                if (data == '1') {
                    console.log(data);
                    if(issecond){
                        window.location.href = '/site/test-page?theme_id=" . $theme->id . "';
                    } else {
                        window.location.href = '/site/index';
                    }
                } else {
                    console.log(data);
                }
            }
        });
    });

    function disableBack() { window.history.forward(); }
    setTimeout(disableBack(), 0);
    window.onunload = function () { null };
    
") ?>