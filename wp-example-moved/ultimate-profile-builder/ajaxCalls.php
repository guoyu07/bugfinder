<?php

     include 'UPB_config.php';

     $f_name= $_REQUEST['function'];

     switch($f_name)

     {

        case 'validateUser': echo validateUser($_REQUEST['name']); break;

        case 'validateEmail': echo validateEmail($_REQUEST['email']); 

     }

/*Cross checks if the username already exists during registration process*/ 

     function validateUser($name)

     {

        return username_exists( $name )>0?"true":"false"; 

     }

/*Cross checks if the email already exists during registration process*/

     function validateEmail($email)

     {  

        return email_exists( $email )>0?"true":"false"; 

     }

     die;

?>