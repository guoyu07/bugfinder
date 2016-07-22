<?php

/*Controls editing existing custom field in the dashboard area*/

global $wpdb;

$upb_fields =$wpdb->prefix."upb_fields";

$path =  plugin_dir_url(__FILE__); 

?>

<style>

.form_field p {

	display: none;

}

#selectfieldtype {

	display: block;

}

</style>

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

<script> 

/*Defines field parameters when selecting field type in drop down*/

function getfields(a)

{

	jQuery('#user_groupsfield').show();

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

		jQuery('#registrationformfield').show();

		jQuery('#readonlyfield').show();

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

		jQuery('#registrationformfield').show();

		jQuery('#visibilityfield').show();

		jQuery('#rulesfield').show();

		jQuery('#readonlyfield').hide();

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

		jQuery('#requirefield').hide;

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

<?php

if(isset($_POST['field_submit'])) //Updates the existing field with new submitted changes

{

		$retrieved_nonce = $_REQUEST['_wpnonce'];

		if (!wp_verify_nonce($retrieved_nonce, 'upb_update_field' ) ) die( 'Failed security check' );



$usergroups = implode(",",$_POST['field_user_groups']);	

$qry = "update $upb_fields set Type = '".$_POST['select_type']."',Name = '".$_POST['field_name']."', Value ='".$_POST['field_value']."', Class ='".$_POST['field_class']."', Max_Length ='".$_POST['field_maxLenght']."',Cols='".$_POST['field_cols']."',Rows='".$_POST['field_rows']."',Option_Value ='".$_POST['field_Options']."', Description = '".$_POST['field_Des']."', Ordering ='".$_POST['field_ordering']."', user_group='".$usergroups."' where Id=".$_POST['field_id'];

$wpdb->query($qry);

	$current_user = wp_get_current_user();

	$current_ID = $current_user->ID;

	$meta_key = str_replace(" ","_",$_POST['field_name']);

	$meta_value = "";

	$unique = "";

	add_user_meta( $current_ID, $meta_key, $meta_value, $unique );

	header("location:admin.php?page=UltimatePB_Fields");

}

if(isset($_POST['id'])) //For deleting the existing custom field

{

	if(isset($_POST['delete_field']))

	{

		$retrieved_nonce = $_REQUEST['_wpnonce'];

		if (!wp_verify_nonce($retrieved_nonce, 'upb_delete_field' ) ) die( 'Failed security check' );



		$qry = "delete from $upb_fields where Id=".$_POST['id'];

		$wpdb->query($qry);	

		header("location:admin.php?page=UltimatePB_Fields");

	}

	if(isset($_POST['copy_field'])) //For copying the existing custom field and saving it as a new field

	{

		$retrieved_nonce = $_REQUEST['_wpnonce'];

		if (!wp_verify_nonce($retrieved_nonce, 'upb_copy_field' ) ) die( 'Failed security check' );



		$qry ="select * from $upb_fields where Id =".$_POST['id'];

		$row = $wpdb->get_row($qry);

		$array = explode('(',$row->Name);

		

		$qry1 ="select count(*) from $upb_fields where Name like '".$array[0]."%'";

		$row1 = $wpdb->get_var($qry1);

		

		//echo $row1;

		$qry1 = "insert into $upb_fields values('','".$row->Type."','".$array[0]."(".$row1.")','".$row->Value."','".$row->Class."','".$row->Max_Length."','".$row->Cols."','".$row->Rows."','".$row->Option_Value."','".$row->Description."','".$row->Require."','".$row->Readonly."','".$row->Visibility."','".$row->Ordering."','".$row->user_group."','".$row->registration."')";

		$wpdb->query($qry1);

		header("location:admin.php?page=UltimatePB_Fields");

	}

	if(isset($_POST['edit_field'])) // Fetches existing custom field values after clicking edit custom field link

	{

		$retrieved_nonce = $_REQUEST['_wpnonce'];

		if (!wp_verify_nonce($retrieved_nonce, 'upb_edit_field' ) ) die( 'Failed security check' );



		$qry ="select * from $upb_fields where Id =".$_POST['id'];

		$row = $wpdb->get_row($qry);

		$str=preg_replace('/[\s]+/',' ',$row->Type);

	}

}

?>

<!--HTML for custom field creation-->

<div class="main">

  <div class="header"></div>

  <div class="content-wrap">

    <div class="pre-s-main">

      <div class="pre-s-top-part">

        <div class="pres-s-left-icon"> <img src="<?php echo $path; ?>images/upb-logo.jpg"/> </div>

        <div class="pres-s-heading" style="margin-top:15px;"> <a href="http://cmshelplive.com/chl-products/ultimate-profile-builder-pro.html" ><img src="<?php echo $path; ?>images/pro-banner-ubp.jpg" /></a> </div>

      </div>

    </div>

    <form method="post" action="">

      <div class="form_field add-form-field">

       <p class="info"></p>

        <p id="selectfieldtype">

          <input type="hidden" name="field_id" id="field_id" value="<?php echo $row->Id; ?>" />

          <label for="select_type">Select Type:</label>

          <select name="select_type" id="select_type" onChange="getfields(this.value)">

            <option value="">Select A Field</option>

            <option value="heading" <?php if(isset($str) && $str=='heading') echo 'selected'; ?>>Heading</option>

            <option value="paragraph" <?php if(isset($str) && $str=='paragraph') echo 'selected'; ?>>Paragraph</option>

            <option value="text" <?php if(isset($str) && $str=='text') echo 'selected'; ?>>Text Field</option>

            <option value="select" <?php if(isset($str) && $str=='select') echo 'selected'; ?>>Drop Down</option>

            <option value="radio" <?php if(isset($str) && $str=='radio') echo 'selected'; ?>>Radio Button</option>

            <option value="textarea" <?php if(isset($str) && $str=='textarea') echo 'selected'; ?>>Text Area</option>

            <option value="checkbox" <?php if(isset($str) && $str=='checkbox') echo 'selected'; ?>>Check Box</option>

            <?php /*?><option value="file" <?php if(isset($str) && $str=='file') echo 'selected'; ?>>File Upload</option><?php */?>

            <option value="DatePicker" <?php if(isset($str) && $str=='DatePicker') echo 'selected'; ?>>Date Picker</option>

            <option value="email" <?php if(isset($str) && $str=='email') echo 'selected'; ?>>Email</option>

            <option value="number" <?php if(isset($str) && $str=='number') echo 'selected'; ?>>Number</option>

            <option value="term_checkbox" <?php if(isset($str) && $str=='term_checkbox') echo 'selected'; ?>>Terms & Conditions Checkbox</option>

          </select>

        </p>

        <p id="user_groupsfield">

          <label for="field_user_groups">Select User group</label>

          <select data-placeholder="Choose User Group..." name="field_user_groups[]" id="field_user_groups" class="chosen-select" multiple="multiple" tabindex="4" required>

            <option value=""></option>

<?php

$roles = get_editable_roles();

foreach($roles as $key=>$role)

{?>

            <option value="<?php echo $key; ?>" <?php if (isset($row) && strpos($row->user_group, $key) !== false) echo 'selected';?>><?php echo $role['name']; ?></option>

            <?php } ?>

          </select>

          <div id="role_message"></div>

        </p>

        <p id="namefield">

          <label for="field_name">Label</label>

          <input type="text" name="field_name" id="field_name" value="<?php echo $row->Name;?>" required onBlur="check()">

        <div id="user-result"></div>

        </p>

        <p id="valuefield">

          <label for="field_value"> Default Value</label>

          <?php $defval = htmlspecialchars($row->Value);?>

          <input type="text" name="field_value" id="field_value" value="<?php echo $defval?>">

        </p>

        <p id="classfield">

          <label for="field_class">CSS Class Attribute</label>

          <input type="text" name="field_class" id="field_class" value="<?php echo $row->Class;?>">

        </p>

        <p id="maxlenghtfield">

          <label for="field_maxLenght">Maximum Length</label>

          <input type="text" name="field_maxLenght" id="field_maxLenght" value="<?php echo $row->Max_Length;?>">

        </p>

        <p id="colsfield">

          <label for="field_cols">Cols</label>

          <input type="text" name="field_cols" id="field_cols" value="<?php echo $row->Cols;?>">

        </p>

        <p id="rowsfield">

          <label for="field_rows">Rows</label>

          <input type="text" name="field_rows" id="field_rows" value="<?php echo $row->Rows;?>">

        </p>

        <p id="optionsfield">

          <label for="field_Options">Options <small style="float:left;">(value seprated by comma ",")</small></label>

          <textarea type="text" name="field_Options" id="field_Options" cols="25" rows="5"><?php echo $row->Option_Value;?></textarea>

 		<div class="options_message"></div>

        </p>

        <p id="desfield">

          <label for="field_Des">Description</label>

          <textarea type="text" name="field_Des" id="field_Des" cols="25" rows="5"><?php echo $row->Description;?></textarea>

        </p>

        

        <p id="orderingfield">

          <label for="field_ordering">Ordering</label>

          <select name="field_ordering" id="field_ordering" required>

            <option value="">select ordering</option>

            <?php 

	  $qry = "SELECT count(*) FROM $upb_fields";

	  $totalrows = $wpdb->get_var($qry) +1;

	  for($i=1;$i<=$totalrows;$i++)

	  {?>

            <option value="<?php echo $i;?>" <?php if($row->Ordering==$i)echo 'selected';?>><?php echo $i;?></option>

            <?php } ?>

          </select>

        </p>

        <p id="submit_field">

        <?php wp_nonce_field('upb_update_field'); ?>

          <input type="submit" id="field_submit" name="field_submit" class="button-primary" value="Update Field" style="width:auto;" onClick=" return validation()" />

        </p>

      </div>

    </form>

  </div>

</div>

<script>

getfields("<?php echo $str;?>");

</script> 

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

	 

   jQuery.post('<?php echo get_option('siteurl').'/wp-admin/admin-ajax.php';?>?action=check_fieldname&cookie=encodeURIComponent(document.cookie)', {'name':name,'prevalue':'<?php echo $row->Name;?>'}, function(data) { 

   //make ajax call to check_username.php

 	if(data=="")

   {

	  jQuery("#user-result").html('');  

	  jQuery("#submit_field").show();

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