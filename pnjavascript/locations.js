function livesearch(field, chars)
{
    new Ajax.Autocompleter(field, field+'_choices', document.location.pnbaseURL + 'ajax.php?module=locations&func=get&field='+field,
    {paramName: 'fragment',
        minChars: chars,
        indicator: 'ajax_indicator',
        frequency: 0.1
    }
    );
}