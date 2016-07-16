<?
$var = "...." . $_POST['var'];
$var2 = "string". $var;
mysql_query("SELECT * FROM table WHERE user_id = $var2");
mysql_query("SELECT * FROM table WHERE user_name = '$var2'");
