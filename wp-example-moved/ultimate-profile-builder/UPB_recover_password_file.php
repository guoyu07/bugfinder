<?php

/*Controls password recovery process on front end*/ 

	 $path =  plugin_dir_url(__FILE__);  // define path to link and scripts

     $pageURL = get_permalink();

     $sign = strpos($pageURL,'?')?'&':'?';

     extract($_REQUEST);

	if(isset($login1))

	{

		include 'UPB_register_file.php';

	}

	else if(isset($login2))

	{

		include 'UPB_login_file.php';

	}

	else if(isset($login4))

	{

		include 'UPB_view_profile_file.php';

	}

	else if(isset($login5))

	{

		include 'UPB_edit_profile_file.php';

	}

	else

	{

 		include 'UPB_theme.php'; 

		

		if(isset($_POST['user_login']))

		$user_login = $_POST['user_login'];

		if(isset($user_login))//Displays error when username or email entered does not exists

		{

			$lostErr= "Username or E-mail does not exist in our system";

			$lostErrC = 'lostErr';

		}

		else

		{

			$lostErrC = 'noErr';

		}

		

		global $wpdb;

		

		$wp_usermeta=$wpdb->prefix."usermeta";

		$wp_users=$wpdb->prefix."users";

		if(isset($user_login) && username_exists( $user_login )) //Resets password if the username exists

		{

	    	$retrieved_nonce = $_REQUEST['_wpnonce'];

			if (!wp_verify_nonce($retrieved_nonce, 'upb_password_reset_form' ) ) die( 'Failed security check' );



			$userstatus = 1;

			$user_id = username_exists( $user_login );

			$user_info = get_userdata($user_id);

			$user_email = $user_info->user_email;

			$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );

			wp_set_password( $random_password, $user_id );

			$subject = get_bloginfo('name');

			$subject .= " - Lost Password";

			$message = "This is your new password : " . $random_password;

			

			wp_mail( $user_email, $subject, $message );//Emails the new password

?>

<style type="text/css">

#recoverErr {

	display: none;

	width: 300px;

}

</style>

<!--HTML for displaying success message after the password is emailed-->

   <div id="UPB-Standard-Form">

   <div id="UPB-Standard-Form-entry-content">

     <div class="UPB-Standard-Form-main-upb-form">

      <div class="already-logged">

        <div class="You-are-already-logged-in">

        <h3>Password has been sent to your registered email. </h3>

        </div>

     <div class="UPB-Button-input"> 

       

       <a class="UPB-Button" href="<?php echo site_url(); ?>">Go back to Home-Page</a> 

       <a class="UPB-Button" href="<?php echo $pageURL; ?><?php echo $sign; ?>login2=1" title="Login">Go back to Login</a>

     

     <div class="clear"></div>

     </div>

    

    </div><!---------UPB-Standard-Form-main-upb-form---------->

   <div class="clear"></div>

   </div><!--------UPB-Standard-Form-entry-content-------->

   

   <div class="clear"></div>

  </div><!-------UPB-Standard-Form-------->

<?php

		}

		else if (isset($user_login) && email_exists( $user_login )) //Resets password if the email exists

		{

			$retrieved_nonce = $_REQUEST['_wpnonce'];

		    if (!wp_verify_nonce($retrieved_nonce, 'upb_password_reset_form' ) ) die( 'Failed security check' );



			$userstatus = 1;

			$user_id = email_exists( $user_login );

			$user_email = $user_login;

			$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );

			wp_set_password( $random_password, $user_id );

			$subject = get_bloginfo('name');

			$subject .= " - Lost Password";

			$message = "This is your new password : " . $random_password;

			wp_mail($user_email, $subject, $message);

?>

 <div id="UPB-Standard-Form">

   <div id="UPB-Standard-Form-entry-content">

     <div class="UPB-Standard-Form-main-upb-form">

      <div class="already-logged">

        <div class="You-are-already-logged-in">

        <h3>Password has been sent to your registered email. </h3>

        </div>

     <div class="UPB-Button-input"> 

       

       <a class="UPB-Button" href="<?php echo site_url(); ?>">Go back to Home-Page</a> 

       <a class="UPB-Button" href="<?php echo $pageURL; ?><?php echo $sign; ?>login2=1" title="Login">Go back to Login</a>

     

     <div class="clear"></div>

     </div>

    

    </div><!---------UPB-Standard-Form-main-upb-form---------->

   <div class="clear"></div>

   </div><!--------UPB-Standard-Form-entry-content-------->

   

   <div class="clear"></div>

  </div><!-------UPB-Standard-Form-------->

<?php

		}

		else

		{

			$userstatus = 0;

?>

<style type="text/css">

				.noErr{

					display:none;

					width:300px;

					margin: -20px 0 23px 223px !important;					

				}

</style>

<?php

		}

?>

<script language="javascript" type="text/javascript">

function validate123()//Validation for fields in password recovery form

{

	var a = document.getElementById("user_login").value;

	

	if(a == "" || a == NULL)

	{

		document.getElementById('recoverErr').innerHTML='Username or E-mail is required';

		document.getElementById('recoverErr').style.display = 'block';

		document.getElementById("user_login").focus();

		return false;

	}

	else

	{

		return true;

	}

}

</script>

<?php if($userstatus==0) : ?>

<!--HTML for displaying password reset form-->

<div id="UPB-Standard-Form">

  <div id="top-entry-header">Forgot Password?</div>

  <form method="post" class="UPB-login-form" action="" id="lostpasswordform" name="lostpasswordform" onsubmit="javascript:return validate123();">

  <div id="UPB-Standard-Form-entry-content" >

     <div class="UPB-Standard-Form-main-upb-form">

       <div class="login-form">

        <div class="formtable">

         <label> Username / E-mail: </label>

         <div class="input-box">

            <input type="text" value="" class="input" id="user_login" name="user_login">

         <div class="reg_frontErr <?php echo $lostErrC; ?>" id="recoverErr"><?php echo $lostErr; ?></div>

          </div>

        </div>

        <div class="form-text">Please enter your registered username or email, and we will resend your password.</div>

      </div>

      

      </div>

      

      

    <div class="clear"></div>

    </div>

  <div id="UPB-Button-area">

     <div class="UPB-Button-input forgot-passwordd">

     <?php wp_nonce_field('upb_password_reset_form'); ?>

     <input type="submit" value="Submit" class="UPB-Button" id="PRSubmit" name="PRSubmit">

    </div>

    </div>

    

  <div class="clear"></div>  

  </form>

 <div class="clear"></div> 

</div>

<?php endif; ?>

<?php

	}

?>