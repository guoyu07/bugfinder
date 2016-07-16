<?
$var1 = $_POST[1];
$wpdb->get_results("SELECT * FROM tbl WHERE name = '$var1'");
