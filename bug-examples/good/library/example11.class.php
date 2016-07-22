<?php
class Example11_2 {
  function get_var() {
    return $_REQUEST['var'];
  }
  function include_code($var) {
    include($var);
  }
}
class Example11_1 extends Example11_2 {
  function get_var1() {
    return $_REQUEST['var1'];
  }
  function get_var2() {
    return $_GET['var2'];
  }
  function get_var3() {
    return $this->get_var();
  }
  function include_code1($var) {
    $var = "string". $var;
    $var2 = "string". $var;
    include($var2);
  }
  function include_code2($var) {
    $var = "string". $var;
    $var2 = "string". $var;
    $this->include_code($var);
  }
}

class_11();
