<?php

/*Controls login form behavior in front end*/

	$path =  plugin_dir_url(__FILE__);  // define path to link and scripts

	$pageURL = get_permalink();

	$sign = strpos($pageURL,'?')?'&':'?';

	extract($_REQUEST);

?>

<style type="text/css">

                #loginErr

                {

                    display:none;

                }

</style>

<?php

if(isset($_POST['submit'])) $submit = $_POST['submit'];

if(isset($_POST['submit']))//Controls behaviour of login button

{

  		$retrieved_nonce = $_REQUEST['_wpnonce'];

		if (!wp_verify_nonce($retrieved_nonce, 'upb_login_form' ) ) die( 'Failed security check' );



		if(isset($_POST['user_login']))

		$user_login = $_POST['user_login'];

  

		if(isset($_POST['user_pass']))

		$user_pass = $_POST['user_pass'];

  

		if(isset($_POST['rememberme']))

		$rememberme = $_POST['rememberme'];

  

		$creds = array();

  

		if(isset($user_login))

		$creds['user_login'] = trim($user_login);

  

		if(isset($user_pass))

		$creds['user_password'] = trim($user_pass);

  

		if(isset($rememberme))

		$creds['remember'] = $rememberme;

  

		$user = wp_signon($creds,false);

  

		if ( is_wp_error($user) )//Displays error when username or/and password does not matches

		{

			$loginErr= "Entered Username or Password do not match or are incorrect";

  

  ?>

  <style type="text/css">

				#loginErr

				{

					display:block;

					width:350px;

				}

			</style>

  <?php

  

		}

		else

		{

?>

<!--HTML when user successfully logs in-->

<style type="text/css">

  #profile-page{ display:none;}

  </style>

  <div id="UPB-Standard-Form">

   <div id="top-entry-header">

    <div class="login-success"> Login Success! </div>

    <div class="login-dis"> Please choose your destination. </div>

   </div>

   

  <div id="UPB-Standard-Form-entry-content">

   <div class="UPB-Standard-Form-main-upb-form">

   <div class="UPB-Button-input">

  <a class="UPB-Button" href="<?php echo $pageURL; ?><?php echo $sign; ?>login4=1" title="View Profile">View Profile</a> 

  <a class="UPB-Button" href="<?php echo wp_logout_url( get_permalink() ); ?>" title="Logout">Logout</a>

  

  <div class="clear"></div>

  </div>

  

   </div><!--------------UPB-Standard-Form-main-upb-form------------->

  </div><!---------------------UPB-Standard-Form-entry-content---------->

  </div>

  <?php

		}

	

}

			

	if(isset($login1) && is_user_logged_in()==false)

	{

		include 'UPB_register_file.php';

	}

	else if(isset($login3) && is_user_logged_in()==false)

	{

		include 'UPB_recover_password_file.php';

	}

	else if(isset($changeavatar))

	{

		include 'UPB_edit_profile_image.php';

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

		if ( is_user_logged_in() )

		{

?>

<!--HTML for page shown when accessing the login form when user is already logged in-->

<div id="UPB-Standard-Form">

  <div id="UPB-Standard-Form-entry-content">

    

     <div class="UPB-Standard-Form-main-upb-form">

    <div class="already-logged">

     

      <div class="You-are-already-logged-in">

       

       <h3>You are already logged-in.</h3>

       

      </div><!--------------You-are-already-logged-in-------->

      <div  class="UPB-Button-input"> 

      

       <a class="UPB-Button" href="<?php echo site_url(); ?>">Go back to site</a>

       <a class="UPB-Button" href="<?php echo wp_logout_url( get_permalink() ); ?>" title="Logout">Logout</a>

        

      <div class="clear"></div> 

      

      </div><!--------UPB-Button-input------->

        

     <div class="clear"></div>

    </div>

    

    <div class="clear"></div>

  </div><!--------UPB-Standard-Form-main-upb-form------->

  

  <div class="clear"></div>

  

  </div><!--------UPB-Standard-Form-entry-content-------->

  

 <div class="clear"></div>

 

</div><!------UPB-Standard-Form------>

<?php

		}

		else

		{

			

		

?>

<div id="profile-page"> 

<script type="text/javascript">

  

function validateLogin()

{

	var user_login = document.getElementById("user_login").value;

	var user_pass = document.getElementById("user_pass").value;

	if (user_login==null || user_login=="")

	{

		document.getElementById('divuser_login').style.display = 'block';

		document.getElementById("user_login").focus();

		return false;

	}

	if(user_pass==null || user_pass=="")

	{

		document.getElementById('divuser_pass').style.display = 'block';

		document.getElementById('divuser_login').style.display = 'none';

		document.getElementById("user_pass").focus();

		return false;

	}

	return true;

}

</script>

<form class="UPB-login-form" method="post" action="" id="loginform" name="loginform" onsubmit="javascript:return validateLogin();">

  

  <div id="UPB-Standard-Form">

  <div id="UPB-Standard-Form-entry-content">

  <?php if(isset($loginErr)): ?>

  <div id="loginErr" class="reg_frontErr"> <?php echo $loginErr; ?> </div>

  <?php endif; ?>

   <div class="UPB-Standard-Form-main-upb-form">

     <div class="login-form">

     

      <div class="formtable">

      <label for="user_login"> Username </label>

      <input type="text" size="20" value="<?php if(isset($user_login)) echo $user_login; ?>" class="input" id="user_login" name="user_login" >

      </div>

      <div class="reg_frontErr" id="divuser_login" style="display:none;">Please enter a username.</div>

      

      <div class="formtable">

      <label for="user_pass"> Password </label>

      <input type="password" size="20" value="" class="input" id="user_pass" name="user_pass" >

      </div>

      

      <div class="reg_frontErr" id="divuser_pass" style="display:none;">Please enter a password.</div>

      

       <div class="clear"></div>

       <div class="formtable forgot-password">

       <label>&nbsp;</label>

       <input type="checkbox" value="true" id="rememberme" name="rememberme">

       Remember Me

       </div>

      

     </div><!-------login-form----->

      

   <div class="clear"></div>   

      

   </div><!----------UPB-Standard-Form-main-upb-form------>

  </div><!--------UPB-Standard-Form-entry-content-------->

  <div id="UPB-Button-area">

      <div class="UPB-Button-input">

      <?php wp_nonce_field('upb_login_form'); ?>

        <input type="submit" value="Log In" class="UPB-Button" id="login" name="submit">

      </div>

      <div class="UPB-forgot-pass"> Forget Password? <a href="<?php echo $pageURL; ?><?php echo $sign; ?>login3=1" title="Lost Password">Click here</a> to resend </div>

      

      <div class="clear"></div>

	  

      <?php

$qry="SELECT value FROM $upb_option WHERE fieldname='upb_facebook_login'";

$facebook_login = $wpdb->get_var($qry);

if($facebook_login=='yes')

{

	include 'facebook/upb_facebook.php';

	upb_fb_login_validate();

	upb_fb_loginForm();

}

?>

    </div>

    

 <div class="clear"></div>

 

 </div><!------UPB-Standard-Form------>

  </form>

</div><!----------profile-page----------->

<?php

		}

	}

?>