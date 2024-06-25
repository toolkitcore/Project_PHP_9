<?php

function add_pages($data) {
    return db_insert('tbl_pages', $data);    
}

function get_list_users() {
    $result = db_fetch_array("SELECT * FROM `tbl_users`");
    return $result;
}

function get_user_by_id($id) {
    $item = db_fetch_row("SELECT * FROM `tbl_users` WHERE `user_id` = {$id}");
    return $item;
}

function pages_exists($page_title, $page_slug) {
    $check_pages = db_num_rows("SELECT * FROM `tbl_pages` WHERE `page_title` = '{$page_title}' OR `page_slug` = '{$page_slug}'");
    if($check_pages > 0) {
        return true;
    }
    return false;
}


function get_user_by_username($username) {
    $item = db_fetch_row("SELECT * FROM `tbl_users` WHERE `username` = '{$username}'");
    if(!empty($item)) {
        return $item;
    }
}