<?
$var1 = $_POST[1];

$wpdb->get_row("SELECT * FROM tbl WHERE name = '$var1'");
