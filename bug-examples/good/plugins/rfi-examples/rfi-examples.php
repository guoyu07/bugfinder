<?php

/*
 * LFI example #1:
 * incoming variable from request, with swapping values between variables, and final lfi concatenation.
 */
$some_var = $_REQUEST['xzi'];

$var1 = $some_var;
$var2 = $var1;
$var3 = "string" . $var2; // lfi
$final_var = "pepepe". $var3; // lfi

include_once($final_var);

/**
 * LFI example #2:
 * directly require code from incoming post var
 */
require_once ( dirname(__FILE__) . "/path/" . $_POST['path']);

/**
 * LFI example #3:
 * get variable with WP function, apply functions to values, swap and concatenation.
 * @var [type]
 */
$var = get_query_var('param', null);

$var2 = trim($var);
$var3 = "string" . $var2;
$var4 = substr($var3, 0, 1024);

require_once(dirname(__FILE__) . "/path/" . trim($var4));

/**
 * RFI example #4:
 * directly include incoming request var without concatenation.
 */
include($_POST['bd']);

/**
 * RFI example #5:
 * directly include value from WordPress.
 */
include(get_query_var('var'));

/**
 * LFI example #6:
 * directly include with WordPress way of getting request variable.
 */
include("string".  get_query_var('var'));

/**
 * LFI/RFI example #7:
 * directly include from function and call in main.
 */
function example_7_1 () {
  include("string".  $_REQUEST['var']);
}

function example_7_2 () {
  include($_REQUEST['var']);
}

function example_7_3 () {
  include("string".  get_query_var('var'));
}

function example_7_4 () {
  include(get_query_var('var'));
}

example_7_1();
example_7_2();
example_7_3();
example_7_4();

/**
 * LFI/RFI example #8:
 * get variable from function, include in main.
 */
function example_8_1 () {
  $var = get_query_var('var');
  return $var;
}

function example_8_2 () {
  $var = get_query_var('var');
  return "string" . $var;
}

function example_8_3 () {
  $var = $_REQUEST['var'];
  return $var;
}

function example_8_4 () {
  $var = $_REQUEST['var'];
  return "string" . $var;
}

include(example_8_1());
require(example_8_2());
include_once(example_8_3());
require_once(example_8_4());

$var1 = example_8_1();
$var2 = "string". example_8_2();
$var3 = trim(example_8_3());
$var4 = trim("path" . example_8_4());

include($var1);
require($var2);
include_once($var3);
require_once($var4);

/**
 * LFI/RFI example #9:
 * get variable from main, include in function.
 */
$var1 = get_query_var('var1');
$var2 = "string". get_query_var('var2');
$var3 = trim(get_query_var('var3'));
$var4 = trim("path" . get_query_var('var4'));
$var5 = $_POST['var1'];
$var6 = "string". $_GET['var2'];
$var7 = trim($_REQUEST['var3']);
$var8 = trim("path" . $_REQUEST['var4']);

function example_9_1 ($var) {
  require($var);
}

function example_9_2 ($var) {
  $var2 = $var;
  include($var2);
}

function example_9_3 ($var) {
  $var2 = "string" . $var;
  include_once($var2);
}

function example_9_4 ($var) {
  $var2 = "string" . trim($var);
  require_once($var2);
}

example_9_1($var1);
example_9_2($var2);
example_9_3($var3);
example_9_4($var4);
example_9_1($var5);
example_9_2($var6);
example_9_3($var7);
example_9_4($var8);

/**
 * LFI/RFI example #10:
 * get variable from function, include from function, referenced code in main.
 */
function example_10_1 () {
  return $_REQUEST['var'];
}

function example_10_2 () {
  return $_GET['var'];
}

function example_10_3 () {
  return $_POST['var'];
}

function example_10_4 () {
  $var = $_REQUEST['var'];
  return $var;
}

function example_10_5 () {
  $var = $_GET['var'];
  $var2 = $var;
  $var3 = "string ". $var2;
  return $var3;
}

function example_10_6 () {
  $var = get_query_var('var');
  return $var;
}

function example_10_7 () {
  $var = get_query_var('var');
  $var2 = $var;
  return $var2;
}

function example_10_8 () {
  $var = get_query_var('var');
  $var2 = $var;
  $var3 = "string". $var2;
  return $var3;
}

function example_10_9 ($var) {
  include($var);
}

function example_10_10 ($var) {
  $var2 = $var;
  $var3 = "string" . $var2;
  require($var3);
}

example_10_9(example_10_1());
example_10_9(example_10_2());
example_10_9(example_10_3());
example_10_9(example_10_4());
example_10_9(example_10_5());
example_10_9(example_10_6());
example_10_9(example_10_7());
example_10_9(example_10_8());

example_10_10(example_10_1());
example_10_10(example_10_2());
example_10_10(example_10_3());
example_10_10(example_10_4());
example_10_10(example_10_5());
example_10_10(example_10_6());
example_10_10(example_10_7());
example_10_10(example_10_8());

$var1 = example_10_1();
$var2 = example_10_2();
$var3 = example_10_3();
$var4 = example_10_4();
$var5 = example_10_5();
$var6 = example_10_6();
$var7 = example_10_7();
$var8 = example_10_8();

$var1 = "string" . $var1;

example_10_9($var1);
example_10_9($var2);
example_10_9($var3);
example_10_9($var4);
example_10_9($var5);
example_10_9($var6);
example_10_9($var7);
example_10_9($var8);

example_10_10($var1);
example_10_10($var2);
example_10_10($var3);
example_10_10($var4);
example_10_10($var5);
example_10_10($var6);
example_10_10($var7);
example_10_10($var8);

/**
 * RFI/LFI example 11:
 * from a class get variable and include, call from main and from function in main.
 */
function example11_1() {
  $class = new Example11();
  $class->include_code($class->get_var());
  $class->include_code1($class->get_var1());
  $class->include_code1($class->get_var2());
  $class->include_code1($class->get_var3());
  $class->include_code2($class->get_var1());
  $class->include_code2($class->get_var2());
  $class->include_code2($class->get_var3());
  $var = $class->get_var();
  $var1 = $class->get_var1();
  $var2 = $class->get_var2();
  $var3 = $class->get_var3();
  $class->include_code($var);
  $class->include_code1($var1);
  $class->include_code1($var2);
  $class->include_code1($var3);
  $class->include_code2($var1);
  $class->include_code2($var2);
  $class->include_code2($var3);
}

example_11_1();

$class = new Example11();
$class->include_code($class->get_var());
$class->include_code1($class->get_var1());
$class->include_code1($class->get_var2());
$class->include_code1($class->get_var3());
$class->include_code2($class->get_var1());
$class->include_code2($class->get_var2());
$class->include_code2($class->get_var3());
$var = $class->get_var();
$var1 = $class->get_var1();
$var2 = $class->get_var2();
$var3 = $class->get_var3();
$class->include_code($var);
$class->include_code1($var1);
$class->include_code1($var2);
$class->include_code1($var3);
$class->include_code2($var1);
$class->include_code2($var2);
$class->include_code2($var3);

/**
 * RFI/LFI example 12:
 * from an static class get variable and include, call from main and from function in main.
 */
function example12_1() {
  Example12_1::include_code(Example12_1::get_var());
  Example12_1::include_code1(Example12_1::get_var1());
  Example12_1::include_code1(Example12_1::get_var2());
  Example12_1::include_code1(Example12_1::get_var3());
  Example12_1::include_code2(Example12_1::get_var1());
  Example12_1::include_code2(Example12_1::get_var2());
  Example12_1::include_code2(Example12_1::get_var3());
  $var = Example12_1::get_var();
  $var1 = Example12_1::get_var1();
  $var2 = Example12_1::get_var2();
  $var3 = Example12_1::get_var3();
  Example12_1::include_code($var);
  Example12_1::include_code1($var1);
  Example12_1::include_code1($var2);
  Example12_1::include_code1($var3);
  Example12_1::include_code2($var1);
  Example12_1::include_code2($var2);
  Example12_1::include_code2($var3);
}

example_12_1();

Example12_1::include_code(Example12_1::get_var());
Example12_1::include_code1(Example12_1::get_var1());
Example12_1::include_code1(Example12_1::get_var2());
Example12_1::include_code1(Example12_1::get_var3());
Example12_1::include_code2(Example12_1::get_var1());
Example12_1::include_code2(Example12_1::get_var2());
Example12_1::include_code2(Example12_1::get_var3());
$var = Example12_1::get_var();
$var1 = Example12_1::get_var1();
$var2 = Example12_1::get_var2();
$var3 = Example12_1::get_var3();
Example12_1::include_code($var);
Example12_1::include_code1($var1);
Example12_1::include_code1($var2);
Example12_1::include_code1($var3);
Example12_1::include_code2($var1);
Example12_1::include_code2($var2);
Example12_1::include_code2($var3);
