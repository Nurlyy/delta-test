function createVariant(counter, is_new = false, id = null){
    if(is_new){
        variants[counter] = {count: counter, title: $(`#new_variant-${counter}`).val(), is_right: document.getElementById(`cb_new_variant-${counter}`).checked};
    }else{
        variants[counter] = {id: id, title: $(`#new_variant-${counter}`).val(), is_right: document.getElementById(`cb_new_variant-${counter}`).checked};
    }
    // console.log(variants);
}

function on_create(variants){
    if(variants.length === 0){
        $('.variants').append(`
            <div class='mb-3 field-variant required'>
                <label class='form-label' style='float:left;' for='new_variant-`+counter+`'>`+(Number(counter)+1)+`)</label>
                    <input style='float:left; margin-top:10px; margin-left:15px; margin-right:15px;' class='form-check-input' type='checkbox' onchange='createVariant(`+counter+`, true);' id='cb_new_variant-`+counter+`'><input type='text' id='new_variant-`+counter+`' onchange='createVariant(`+counter+`, true);' class='form-control' name='Variant[`+counter+`][title]' placeholder='Вариант' style='width:500px;margin-left:30px;' aria-required='true'>
                <div class='invalid-feedback'></div>
            </div>`);
        counter += 1;   
    }else{
        for (variant in variants) {
            // console.log(variants[variant].is_right==1);
            $('.variants').append(`
                <div class='mb-3 field-variant required'>
                    <label class='form-label' style='float:left;' for='new_variant-`+counter+`'>`+(Number(counter)+1)+`)</label>
                        <input style='float:left; margin-top:10px; margin-left:15px; margin-right:15px;' ${(variants[variant].is_right == 1)?'checked="true"':''} class='form-check-input' type='checkbox' onchange='createVariant(`+counter+`, false, ${variants[variant].id});' id='cb_new_variant-`+counter+`'><input type='text' id='new_variant-`+counter+`' value='`+variants[variant].title+`' onchange='createVariant(`+counter+`, false, ${variants[variant].id});' class='form-control' name='Variant[`+counter+`][title]' placeholder='Вариант' style='width:500px;margin-left:30px;' aria-required='true'>
                    <div class='invalid-feedback'></div>
                </div>`);
            counter += 1;  
        }
        // console.log('array not empty');    
    }
}

$('#add_variant').click(function(){
    $('.variants').append(`
        <div class='mb-3 field-variant required'>
            <label class='form-label' style='float:left;' for='new_variant-`+counter+`'>`+(Number(counter)+1)+`)</label>
                <input style='float:left; margin-top:10px; margin-left:15px; margin-right:15px;' class='form-check-input' type='checkbox' onchange='createVariant(`+counter+`, true);' id='cb_new_variant-`+counter+`'><input type='text' id='new_variant-`+counter+`' onchange='createVariant(`+counter+`, true);' class='form-control' name='Variant[`+counter+`][title]' placeholder='Вариант' style='width:500px;margin-left:30px;' aria-required='true'>
            <div class='invalid-feedback'></div>
        </div>`);
    counter += 1;   
});

