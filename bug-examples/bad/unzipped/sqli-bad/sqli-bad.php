<?
$sql = "SELECT * FROM table WHERE user_id = $var";
$sql = mysql_real_escape_string($sql);
mysql_query($sql);
