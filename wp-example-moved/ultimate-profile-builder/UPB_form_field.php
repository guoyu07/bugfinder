<?php

/*Controls custom field creation in the dashboard area*/

global $wpdb;

$upb_fields =$wpdb->prefix."upb_fields";

$upb_usermeta =$wpdb->prefix."usermeta";

$path =  plugin_dir_url(__FILE__); 

$qrytotalrows = "SELECT count(*) FROM $upb_fields";

$totalrows = $wpdb->get_var($qrytotalrows) + 1;

if(isset($_POST['field_submit']))/*Saves the field after clicking save button*/

{

  $retrieved_nonce = $_REQUEST['_wpnonce'];

  if (!wp_verify_nonce($retrieved_nonce, 'upb_add_field' ) ) die( 'Failed security check' );



  if($_POST['select_type']=='term_checkbox')

  {

	  $rgf=1;

  }

  else

  {

	  $rgf="";

  }

  if($_POST['field_ordering']=="")

  {

	$_POST['field_ordering']=$totalrows;

  }

$usergroups = implode(",",$_POST['field_user_groups']);	

$qry = "insert into $upb_fields values('','".$_POST['select_type']."','".$_POST['field_name']."','".$_POST['field_value']."','".$_POST['field_class']."','".$_POST['field_maxLenght']."','".$_POST['field_cols']."','".$_POST['field_rows']."','".$_POST['field_Options']."','".$_POST['field_Des']."','','','1','".$_POST['field_ordering']."','".$usergroups."','".$rgf."')";

$wpdb->query($qry);

	$current_user = wp_get_current_user();

	$current_ID = $current_user->ID;

	$meta_key = str_replace(" ","_",$_POST['field_name']);

	$meta_value = "";

	$unique = "";

	add_user_meta( $current_ID, $meta_key, $meta_value, $unique );

	header("location:admin.php?page=UltimatePB_Fields");

}

?>

<script>	

/*Defines field parameters when selecting field type in drop down*/

function getfields(a)

{

	jQuery('#user_groupsfield').show();

	if(a=='')

	{

		jQuery('#namefield').hide();

		jQuery('#classfield').hide();

		jQuery('#desfield').hide();

		jQuery('#maxlenghtfield').hide();

		jQuery('#requirefield').hide();

		jQuery('#visibilityfield').hide();

		jQuery('#rulesfield').hide();

		jQuery('#readonlyfield').hide();

		jQuery('#registrationformfield').hide();

		jQuery('#colsfield').hide();

		jQuery('#rowsfield').hide();

		jQuery('#optionsfield').hide();

		jQuery('#valuefield').hide();

		jQuery('#submit_field').hide();

		jQuery('#orderingfield').hide();

		jQuery('#user_groupsfield').hide();

	}

	if(a=='text' || a=='password')

	{

		jQuery('#namefield').show();

		jQuery('#classfield').show();

		jQuery('#desfield').show();

		jQuery('#maxlenghtfield').show();

		jQuery('#requirefield').show();

		jQuery('#visibilityfield').show();

		jQuery('#rulesfield').show();

		jQuery('#readonlyfield').hide();

		jQuery('#registrationformfield').show();

		jQuery('#colsfield').hide();

		jQuery('#rowsfield').hide();

		jQuery('#optionsfield').hide();

		jQuery('#valuefield').hide();

		jQuery('#submit_field').show();

		jQuery('#orderingfield').show();

	}

	if(a=='submit' || a=='reset' ||  a=='hidden')

	{

		jQuery('#namefield').show();

		jQuery('#classfield').show();

		jQuery('#valuefield').show();

		jQuery('#submit_field').show();

		jQuery('#orderingfield').show();

		jQuery('#desfield').hide();

		jQuery('#maxlenghtfield').hide();

		jQuery('#requirefield').hide();

		jQuery('#visibilityfield').hide();

		jQuery('#rulesfield').hide();

		jQuery('#readonlyfield').hide();

		jQuery('#colsfield').hide();

		jQuery('#rowsfield').hide();

		jQuery('#optionsfield').hide();

		jQuery('#registrationformfield').hide();

	}

	if(a=='select' || a=='radio' || a=='checkbox')

	{

		jQuery('#namefield').show();

		jQuery('#classfield').show();

		jQuery('#optionsfield').show();

		jQuery('#desfield').show();

		jQuery('#valuefield').show();

		jQuery('#requirefield').hide();

		jQuery('#visibilityfield').show();

		jQuery('#rulesfield').show();

		jQuery('#readonlyfield').show();

		jQuery('#registrationformfield').show();

		jQuery('#submit_field').show();

		jQuery('#orderingfield').show();

		jQuery('#maxlenghtfield').hide();

		jQuery('#colsfield').hide();

		jQuery('#rowsfield').hide();

	}

	if(a=='select')

	{

		jQuery('#requirefield').show();	

	}

	if(a=='textarea')

	{

		jQuery('#namefield').show();

		jQuery('#classfield').show();

		jQuery('#desfield').show();

		jQuery('#requirefield').show();

		jQuery('#visibilityfield').show();

		jQuery('#rulesfield').show();

		jQuery('#readonlyfield').show();

		jQuery('#registrationformfield').show();

		jQuery('#colsfield').show();

		jQuery('#rowsfield').show();

		jQuery('#submit_field').show();

		jQuery('#orderingfield').show();

		jQuery('#maxlenghtfield').show();

		jQuery('#optionsfield').hide();

		jQuery('#valuefield').hide();

	}

	if(a=='file')

	{

		jQuery('#namefield').show();

		jQuery('#classfield').show();

		jQuery('#desfield').show();

		jQuery('#requirefield').show();

		jQuery('#visibilityfield').show();

		jQuery('#rulesfield').show();

		jQuery('#readonlyfield').hide();

		jQuery('#registrationformfield').show();

		jQuery('#submit_field').show();

		jQuery('#orderingfield').show();

		jQuery('#maxlenghtfield').hide();

		jQuery('#colsfield').hide();

		jQuery('#rowsfield').hide();

		jQuery('#optionsfield').hide();

		jQuery('#valuefield').hide();

	}	

	if(a=='heading' || a=='paragraph')

	{

		jQuery('#namefield').show();

		jQuery('#classfield').show();

		jQuery('#desfield').show();

		jQuery('#requirefield').hide();

		jQuery('#visibilityfield').show();

		jQuery('#rulesfield').show();

		jQuery('#readonlyfield').hide();

		jQuery('#registrationformfield').show();

		jQuery('#submit_field').show();

		jQuery('#orderingfield').show();

		jQuery('#maxlenghtfield').hide();

		jQuery('#colsfield').hide();

		jQuery('#rowsfield').hide();

		jQuery('#optionsfield').hide();

		jQuery('#valuefield').show();

	}	

	if(a=='DatePicker' || a=='term_checkbox')

	{

		jQuery('#namefield').show();

		jQuery('#classfield').hide();

		jQuery('#desfield').show();

		jQuery('#requirefield').show();

		jQuery('#visibilityfield').show();

		jQuery('#rulesfield').show();

		jQuery('#readonlyfield').hide();

		jQuery('#registrationformfield').show();

		jQuery('#submit_field').show();

		jQuery('#orderingfield').show();

		jQuery('#maxlenghtfield').hide();

		jQuery('#colsfield').hide();

		jQuery('#rowsfield').hide();

		jQuery('#optionsfield').hide();

		jQuery('#valuefield').hide();

	}	

	

	if(a!='term_checkbox')

	{

	 jQuery('#desfield label').html('Description');

	 jQuery('#namefield label').html('Label');

	 jQuery('.info').hide();

	}

	if(a=='term_checkbox')

	{

	 jQuery('#desfield label').html('Terms & Conditions');

	 jQuery('#namefield label').html('Name');

	 jQuery('#visibilityfield').hide();

	 jQuery('#rulesfield').hide();

	 jQuery('#readonlyfield').hide();

	 jQuery('#registrationformfield').hide();

	 jQuery('.info').html('Use this checkbox field for adding Terms & Conditions to the registration form.');

	 jQuery('.info').show();

	}

	if(a=='heading')

	{

		jQuery('.info').html('This Heading field is working only for "Registration" and "Edit Profile" page.');

	 	jQuery('.info').show();

	}

	

	if(a=='paragraph')

	{

		jQuery('.info').html('This Paragraph field is working only for "Registration" and "Edit Profile" page.');

		jQuery('#optionsfield label').html('Paragraph Text');

	 	jQuery('.info').show();

		jQuery('#valuefield').hide();

		jQuery('#optionsfield').show();

	}

	if(a != 'paragraph')

	  {

		  jQuery('#optionsfield label').html('Options: <small style="float:left;">(value seprated by comma ",")</small>');	

	  }

	

	if(a=='email' || a=='number')

	{

		jQuery('#namefield').show();

		jQuery('#classfield').show();

		jQuery('#desfield').show();

		jQuery('#requirefield').show();

		jQuery('#visibilityfield').show();

		jQuery('#rulesfield').show();

		jQuery('#readonlyfield').hide();

		jQuery('#registrationformfield').show();

		jQuery('#submit_field').show();

		jQuery('#orderingfield').show();

		jQuery('#maxlenghtfield').hide();

		jQuery('#colsfield').hide();

		jQuery('#rowsfield').hide();

		jQuery('#optionsfield').hide();

		jQuery('#valuefield').hide();

	}	

}

/*Field selection ends*/

</script>

<style type="text/css" media="all">

.chosen-rtl .chosen-drop {

	left: -9000px;

}

.chosen-container {

	min-width: 200px !important;

}

ul.chosen-choices li.search-field input {

	width: auto !important;

}

#role_message,.options_message{ color:red;}

</style>

<div class="main">

  <div class="header"></div>

  <div class="content-wrap">

    <div class="pre-s-main">

      <div class="pre-s-top-part">

        <div class="pres-s-left-icon"> <img src="<?php echo $path; ?>images/upb-logo.jpg"/> </div>

        <div class="pres-s-heading" style="margin-top:15px;"> <a href="http://cmshelplive.com/chl-products/ultimate-profile-builder-pro.html" ><img src="<?php echo $path; ?>images/pro-banner-ubp.jpg" /></a> </div>

      </div>

    </div>

<style>

.form_field p{ display:none;}

#selectfieldtype{ display:block;}

</style>

<?php

$qrycount = "select count(*) from $upb_fields";

$num_rowscount =$wpdb->get_var($qrycount);

?>

<!--HTML for custom field creation-->

    <form method="post" action="">

      <div class="form_field add-form-field">

      <p class="info"></p>

        <p id="selectfieldtype">

          <label for="select_type">Select Type:</label>

          <select name="select_type" id="select_type" onChange="getfields(this.value)">

            <option value="">Select A Field</option>

            <option value="heading">Heading</option>

            <option value="paragraph">Paragraph</option>

            <option value="text">Text Field</option>

            <option value="select">Drop Down</option>

            <option value="radio">Radio Button</option>

            <option value="textarea">Text Area</option>

            <option value="checkbox">Check Box</option>

            <?php /*?><option value="file">File Upload</option><?php */?>

            <option value="DatePicker">Date Picker</option>

            <option value="email">Email</option>

            <option value="number">Number</option>

            <option value="term_checkbox">Terms & Conditions Checkbox</option>

          </select>

        </p>

        <p id="user_groupsfield">

          <label for="field_user_groups">Assign User Role(s)</label>

          <select data-placeholder="Choose User Role..." name="field_user_groups[]" id="field_user_groups" class="chosen-select" multiple="multiple" tabindex="4" required>

            <option value=""></option>

<?php

$roles = get_editable_roles();

foreach($roles as $key=>$role)

{

	echo '<option value="'.$key.'">'.$role['name'].'</option>';	

}

?>

          </select>

          <div id="role_message"></div>

        </p>

        <p id="namefield">

          <label for="field_name">Label</label>

          <input type="text" name="field_name" id="field_name" required onBlur="check()">

          <div id="user-result"></div>

        </p>

        <p id="valuefield">

          <label for="field_value"> Default Value</label>

          <input type="text" name="field_value" id="field_value">

        </p>

        <p id="classfield">

          <label for="field_class">CSS Class Attribute</label>

          <input type="text" name="field_class" id="field_class">

        </p>

        <p id="maxlenghtfield">

          <label for="field_maxLenght">Maximum Length</label>

          <input type="text" name="field_maxLenght" id="field_maxLenght">

        </p>

        <p id="colsfield">

          <label for="field_cols">Columns</label>

          <input type="text" name="field_cols" id="field_cols">

        </p>

        <p id="rowsfield">

          <label for="field_rows">Rows</label>

          <input type="text" name="field_rows" id="field_rows">

        </p>

        <p id="optionsfield">

          <label for="field_Options">Options <small style="float:left;">(value seprated by comma ",")</small></label>

          <textarea type="text" name="field_Options" id="field_Options" cols="25" rows="5"></textarea>

          <div class="options_message"></div>

        </p>

        <p id="desfield">

          <label for="field_Des">Description</label>

          <textarea type="text" name="field_Des" id="field_Des" cols="25" rows="5"></textarea>

        </p>

        <p id="orderingfield">

          <label for="field_ordering">Ordering</label>

          <select name="field_ordering" id="field_ordering">

            <option value="">Select Ordering</option>

            <?php 

	 

	  for($i=1;$i<=$totalrows;$i++)

	  {?>

            <option value="<?php echo $i;?>"><?php echo $i;?></option>

            <?php }  ?>

          </select>

        </p>

        <p id="submit_field">

        <?php wp_nonce_field('upb_add_field'); ?>

          <input type="submit" id="field_submit" name="field_submit" class="button-primary" value="Save" style="width:auto;" onClick=" return validation()"/>

        </p>

      </div>

    </form>

  </div>

</div>

<!--jQuery for selecting multiple user roles during custom field creation-->

<script type="text/javascript">

    var config = {

      '.chosen-select'           : {},

      '.chosen-select-deselect'  : {allow_single_deselect:true},

      '.chosen-select-no-single' : {disable_search_threshold:10},

      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},

      '.chosen-select-width'     : {width:"95%"}

    }

    for (var selector in config) {

      jQuery(selector).chosen(config[selector]);

    }

  </script> 

  

  <!--AJAX for checking if the custom field already exists-->

<script type="text/javascript">

function validation()

{

		a=jQuery('#field_user_groups').val();

		b = jQuery('#select_type').val();

		if(a==null)

		{

			jQuery('#role_message').html('At least one user role needs to be assigned.');	

		}

		else

		{

			jQuery('#role_message').html('');

		}

		

		if(b=='select' || b=='radio' || b=='checkbox')

		{

			c = jQuery('#field_Options').val();

			if(c=='')

			{

				jQuery('.options_message').html('Please provide at least one value for the field.');	

			}

			else

			{

				jQuery('.options_message').html('');	

			}

		}

		if(jQuery('#role_message').html()=='' && jQuery('.options_message').html()=='')

		{

			return true;

		}

		else

		{

			return false;	

		}

		

}

  function check() { //user types username on inputfiled

  	 //get the string typed by user

	 name = jQuery("#field_name").val();

	 

	 var role = jQuery('#field_name').val();

	var reg = /^[a-zA-Z0-9-_\s]*$/;

	if(!reg.test(role))

	{

		

		jQuery("#user-result").html('<div style="color:red">Special characters are not allowed. Use only a-z, A-Z, 0-9, _, - and space.</div>');

	   jQuery("#submit_field").hide();	

	}

	else

	{

	 

	 

   jQuery.post('<?php echo get_option('siteurl').'/wp-admin/admin-ajax.php';?>?action=check_fieldname&cookie=encodeURIComponent(document.cookie)', {'name':name}, function(data) { 

   //make ajax call to check_username.php

   if(data=="")

   {

	  jQuery("#user-result").html('');

	 // jQuery("#submit_field").show();

	  jQuery("#submit_field").css('display','block');

   }

   else

   {

   jQuery("#user-result").html(data);

   jQuery("#submit_field").hide();

   }

    //dump the data received from PHP page

   });

   

	}

}

  </script>