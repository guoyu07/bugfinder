<?php

class Example12_2 {
  static function get_var() {
    return $_REQUEST['var1'];
  }
  static function include_code($var) {
    $var = "string". $var;
    $var2 = "string". $var;
    include($var2);
  }
}

class Example12_1 extends Example12_2 {
  static function get_var1() {
    return $_REQUEST['var1'];
  }
  static function get_var2() {
    return $_GET['var2'];
  }
  static function get_var3() {
    return parent::get_var();
  }
  static function include_code1($var) {
    $var = "string". $var;
    $var2 = "string". $var;
    include($var2);
  }
  static function include_code2($var) {
    $var = "string". $var;
    $var2 = "string". $var;
    self::include_code1($var);
  }
  static function include_code3($var) {
    $var = "string". $var;
    $var2 = "string". $var;
    parent::include_code($var);
  }
}

 ?>
