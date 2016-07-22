<?php

/*Controls custom field creation in the dashboard area*/

global $wpdb;

$upb_fields =$wpdb->prefix."upb_fields";

$upb_usermeta =$wpdb->prefix."usermeta";

$path =  plugin_dir_url(__FILE__); 

if(isset($_POST['field_submit']))/*Saves the field after clicking save button*/

{

	$retrieved_nonce = $_REQUEST['_wpnonce'];

	if (!wp_verify_nonce($retrieved_nonce, 'upb_support_form' ) ) die( 'Failed security check' );



	$message ="";

	if($_POST['field_name']!="")

	$message .= "Name: ".$_POST['field_name']."\r\n";

	

	if($_POST['field_email']!="")

	$message .= "Email: ".$_POST['field_email']."\r\n";

	

	if($_POST['field_phone']!="")

	$message .= "Phone: ".$_POST['field_phone']."\r\n";

	

	if($_POST['field_issue']!="")

	$message .= "Issue: ".$_POST['field_issue']."\r\n";

	

	$subject = 'Product Support';

	$to = 'support@cmshelplive.com';

	$headers = 'From: '.$_POST['field_name'].' <'.$_POST['field_email'].'>' . "\r\n";

    wp_mail($to,$subject,$message, $headers);

	

	$result = 'Thank you for contacting us. You will shortly receive your support ticket ID through email you used to send this support request.';

	

	

}

?>

<div class="main">

  <div class="header"></div>

  <div class="content-wrap">

    <div class="pre-s-main">

      <div class="pre-s-top-part">

        <div class="pres-s-left-icon"> <img src="<?php echo $path; ?>images/upb-logo.jpg"/> </div>

        <div class="pres-s-heading" style="margin-top:15px;"> <a href="http://cmshelplive.com/chl-products/ultimate-profile-builder-pro.html" ><img src="<?php echo $path; ?>images/pro-banner-ubp.jpg" /></a> </div>

      </div>

    </div>

    <?php if(!empty($result)):?>

<div class="form_field add-form-field"><?php echo $result;?></div>

<?php else:?>

<!--HTML for custom field creation-->

<div class="form_field add-form-field">You can directly create a new support ticket on our Helpdesk by using this form. Please allow 10-15 minutes for confirmation of ticket creation. Information will be sent on your email.</div>

    <form method="post" action="">

      <div class="form_field add-form-field">

        <p id="namefield">

          <label for="field_name">Name</label>

          <input type="text" name="field_name" id="field_name">

        </p>

        <p id="emailfield">

          <label for="field_email">Email</label>

          <input type="text" name="field_email" id="field_email">

          <div class="reg_frontErr" style="display:none;"></div>

        </p>

        <p id="phonefield">

          <label for="field_phone">Phone Number</label>

          <input type="text" name="field_phone" id="field_phone">

        </p>

        <p id="issuefield">

          <label for="field_issue">Issue</label>

          <textarea name="field_issue" id="field_issue" cols="25" rows="5"></textarea>

        </p>

        <p id="submit_field">

          <?php wp_nonce_field('upb_support_form'); ?>

          <input type="submit" id="field_submit" name="field_submit" class="button-primary" value="Send" onClick="return email_Validation()" style="width:auto;" />

        </p>

      </div>

    </form>

    <?php endif; ?>

  </div>

</div>

<script>

function email_Validation()

{

 var email_val="";

  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

  var email = jQuery("#field_email").val();

  	var isemail =  regex.test(email);

		

	

	

	if(email=="")

    {

	 jQuery('.reg_frontErr').html('!Oops E-mail address is required.');

     jQuery('.reg_frontErr').show();  

 	}

	else

	if(isemail==false && email!="")

    {

	 jQuery('.reg_frontErr').html('Please enter a valid e-mail address.');

     jQuery('.reg_frontErr').show(); 

 	}

	else

	{

		jQuery('.reg_frontErr').html('');

		jQuery('.reg_frontErr').hide(); 

	}

	

	if(jQuery('.reg_frontErr').html()=='')

	{

		return true;	

	}

	else

	{

		return false;	

	}

	

 }

 

</script>