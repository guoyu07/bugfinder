<?
$var = "...." . $_POST['var'] . "string";
$var2 = "string". $var;
mysql_query(mysql_real_escape_string("SELECT * FROM table WHERE user_id = $var2"));
mysql_query(mysql_real_escape_string("SELECT * FROM table WHERE user_name = '$var2'"));
