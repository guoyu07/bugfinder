<?php

// $Id: mock_objects_test.php 1900 2009-07-29 11:44:37Z lastcraft $

require_once(dirname(__FILE__) . '/../autorun.php');

require_once(dirname(__FILE__) . '/../expectation.php');

require_once(dirname(__FILE__) . '/../mock_objects.php');



class TestOfPHP5StaticMethodMocking extends UnitTestCase {

    function testCanCreateAMockObjectWithStaticMethodsWithoutError() {

        eval('

            class SimpleObjectContainingStaticMethod {

                static function someStatic() { }

            }

        ');

        Mock::generate('SimpleObjectContainingStaticMethod');

    }

}



?>
