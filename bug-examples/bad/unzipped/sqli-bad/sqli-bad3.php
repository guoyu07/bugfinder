<?
mysql_query(mysql_real_escape_string("SELECT * FROM table WHERE user_id = $var"));
mysql_query(mysqli_real_escape_string("SELECT * FROM table WHERE user_id = $var"));
