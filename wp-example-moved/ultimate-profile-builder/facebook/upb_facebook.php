<?php 



include 'facebook/facebook.php';



function upb_fb_loginForm(){



		upb_fb_error_message();



		upb_fb_LoadScript();



		if(is_user_logged_in()==false){



		?>



			<div class="facebook_wrapper">

            <img src="<?php echo plugin_dir_url(__FILE__).'images/facebook_or.png';?>" style="border:none; box-shadow:none;">

            <br>

            <a href="javascript:void(0)" onClick="FBLogin();">



            <img src="<?php echo plugin_dir_url(__FILE__).'images/facebook_button.png';?>" alt="Fb Connect" title="Login with facebook" /></a></div>



		<?php 



		} 



	}



	



function upb_fb_LoadScript(){



	 global $wpdb;



	 $upb_option=$wpdb->prefix."upb_option";



	 $path =  plugin_dir_url(__FILE__);  // define path to link and scripts



	$pageURL = get_permalink();



	$sign = strpos($pageURL,'?')?'&':'?';



	 //facebook app secret



$qry1="SELECT value FROM $upb_option WHERE fieldname='upb_facebook_app_secret'";



$facebook_app_secret = $wpdb->get_var($qry1);



$qry2="SELECT value FROM $upb_option WHERE fieldname='upb_facebook_app_id'";



$facebook_app_id = $wpdb->get_var($qry2);







     $facebook = new Facebook(array(



		'appId'		=>  $facebook_app_id,



		'secret'	=> $facebook_app_secret,



		));



	?>



	<script type="text/javascript">



window.fbAsyncInit = function() {



	FB.init({



	appId      : "<?php echo $facebook_app_id; ?>", // replace your app id here



	status     : true, 



	cookie     : true, 



	xfbml      : true  



	});



};



(function(d){



	var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];



	if (d.getElementById(id)) {return;}



	js = d.createElement('script'); js.id = id; js.async = true;



	js.src = "//connect.facebook.net/en_US/all.js";



	ref.parentNode.insertBefore(js, ref);



}(document));







function FBLogin(){



	FB.login(function(response){



		if(response.authResponse){



			window.location.href = "<?php echo $pageURL.$sign;?>option=fblogin";



		}



	}, {scope: 'email'});



}



</script>



	<?php



	}



	



function upb_fb_error_message(){



		if(isset($_SESSION['msg'])){



			echo '<div class="'.$_SESSION['msg_class'].'">'.$_SESSION['msg'].'</div>';



			unset($_SESSION['msg']);



			unset($_SESSION['msg_class']);



		}



	}







function upb_fb_login_validate(){



	



	$path =  plugin_dir_url(__FILE__);  // define path to link and scripts



	$pageURL = get_permalink();



	$sign = strpos($pageURL,'?')?'&':'?';



	



	if(isset($_REQUEST['option']) && $_REQUEST['option']  == "fblogin"){



	 global $wpdb;



	 $upb_option=$wpdb->prefix."upb_option";



	 //facebook app secret



	  $qry1="SELECT value FROM $upb_option WHERE fieldname='upb_facebook_app_secret'";



	  $facebook_app_secret = $wpdb->get_var($qry1);



	  $qry2="SELECT value FROM $upb_option WHERE fieldname='upb_facebook_app_id'";



	  $facebook_app_id = $wpdb->get_var($qry2);



		$facebook   = new Facebook(array(



			'appId' => $facebook_app_id,



			'secret' => $facebook_app_secret,



			'cookie' => TRUE,



		));



		$fbuser = $facebook->getUser();



		if ($fbuser) {



			try {



				$user_profile = $facebook->api('/me');



			}



			catch (Exception $e) {



				echo $e->getMessage();



				exit();



			}



			if (!isset($user_profile['email'])) $user_profile['email'] = $user_profile['id'] . '@facebook.com';



			$user_fbid	= $fbuser;



			$user_email = $user_profile["email"];



			$user_fnmae = $user_profile["first_name"];



  



		  if( email_exists( $user_email )) { // user is a member 



			  $user = get_user_by('login', $user_email );



			  $user_id = $user->ID;

			  



			  wp_set_auth_cookie( $user_id, true );



		   } else { // this user is a guest



			  $random_password = wp_generate_password( 10, false );



			  $user_id = wp_create_user( $user_email, $random_password, $user_email );



			  update_user_meta($user_id, 'avtar_image', 'https://graph.facebook.com/' . $user_profile['id'] . '/picture?type=large');



			  wp_update_user(array(



						  'ID' => $user_id,



						  'display_name' => $user_profile['name'],



						  'first_name' => $user_profile['first_name'],



						  'last_name' => $user_profile['last_name']



						));



			  wp_set_auth_cookie( $user_id, true );



		   }



		   



   			wp_redirect( $pageURL.$sign.'login4=1');



			exit;



   



		}		



	}



}



?>