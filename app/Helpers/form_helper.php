<?php
/*
helper('form');

<?=form_myhidden('hola')?> => <input type='hidden' name='myhidden' value='hola'>

*/

if (!function_exists('form_myhidden'))
{
    function form_myhidden($value) {
        return "<input type='hidden' name='myhidden' value='$value'>";
    }
}