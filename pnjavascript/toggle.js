function CheckAll(formtype) {
    $$('.' + formtype + '_checkbox').each(function(el) { el.checked = $('all' + formtype).checked;});
}

function CheckCheckAll(formtype) {
    var totalon = 0;
    $$('.' + formtype + '_checkbox').each(function(el) { if (el.checked) { totalon++; } });
    $('all' + formtype).checked = ($$('.' + formtype + '_checkbox').length==totalon);
}