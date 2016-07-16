<?
$var = $_REQUEST['var'];

$sql = "SELECT * FROM table WHERE user_id = $var";

mysql_query($sql);
