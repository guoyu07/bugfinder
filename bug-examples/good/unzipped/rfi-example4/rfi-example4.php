<?php
include 'rfi-example4-1.php';
include 'rfi-example4-2.php';
include 'rfi-example4-3.php';

$json = $_REQUEST['file'];
$file = json_decode($json, true);
$var = "string" . $file['file'];

stub1("param1", $var);
stub2("param1", "param2", $var);
stub3($var);

 ?>
