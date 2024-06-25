<?php
function currency_format($number, $suffix = 'đ'){
    return number_format($number).$suffix;
}

function show_status_page($gender) {
    $list_gender = array(
        'draft' => 'draft',
        'published' => 'published',
        'pending' => 'pending',
        'archived' => 'archived'
    );  
    
    if(array_key_exists($gender, $list_gender)) {
        return $list_gender[$gender];
    }
}