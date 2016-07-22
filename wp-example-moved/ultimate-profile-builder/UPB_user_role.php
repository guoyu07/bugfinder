<?php 

/*Controls User Role creation in dashboard "Add User Role" and "Manage User Role" settings.*/

global $wpdb;

$path =  plugin_dir_url(__FILE__); 

if(isset($_POST['role_submit'])) //Saves user role data after submission

{

	$retrieved_nonce = $_REQUEST['_wpnonce'];

  	if (!wp_verify_nonce($retrieved_nonce, 'upb_add_user_role' ) ) die( 'Failed security check' );



	$parentrole = get_role( $_POST['parent_role'] );	

	if(!empty($_POST['role_name'])){

	$a = add_role( str_replace(" ","_",$_POST['role_name']), $_POST['role_name'], $parentrole->capabilities );

	$qry_group = "insert into $upb_group(name) values('".str_replace(" ","_",$_POST['role_name'])."')";

	

	$wpdb->query($qry_group);

	//print_r($a);die;

	if($a!="")

	{

		$message = 'New Role "'.$_POST['role_name'].'" successfully created!';

		$error="";

	}

	else

	{

		$message="";	

		$error = "Warning! User Role already exists. Please choose a unique Role.";

	}

	}

}

$roles = get_editable_roles();

?>

<!--HTML for user role creation form-->

<div class="main">

<div class="header"></div>

<div class="content-wrap">

<div class="pre-s-main">

  <div class="pre-s-top-part">

    <div class="pres-s-left-icon"> <img src="<?php echo $path; ?>images/upb-logo.jpg"/> </div>

    <div class="pres-s-heading" style="margin-top:15px;"> <a href="http://cmshelplive.com/chl-products/ultimate-profile-builder-pro.html" ><img src="<?php echo $path; ?>images/pro-banner-ubp.jpg" /></a> </div>

  </div>

</div>

<?php

if(isset($message) && $message!=""): ?>

<div class="parent_Role">

  <div class="UPB-add-user">

    <div class="user-role successfully-created">

      <ul>

        <li class="upb_message"><span  class="user-icon"><?php echo $message; ?></span></li>

        <li class="help">

        <a href="<?php echo admin_url().'admin.php?page=UltimatePB_Custom_User_Role'; ?>"><span class="newrole-icon"></span></a>

        <a href="<?php echo admin_url().'admin.php?page=UltimatePB_Field'; ?>"><span  class="newfield-icon"></span></a>

        <a href="<?php echo admin_url().'admin.php?page=UltimatePB_settings'; ?>"><span  class="newsetting-icon"></span></a>

        </li>

      </ul>

    </div>

    <div class="newrole-success"><a href="./admin.php?page=UltimatePB_Custom_User_Role" class="custom-role">Add another Role</a> <a href="./admin.php?page=UltimatePB_Fields" class="Adding-Custom-Fields">Add Custom Field</a></div>

  </div>

</div>

<?php else: ?>

<div class="parent_Role">

<?php 

$qry = "select count(*) from $upb_group";

$count = $wpdb->get_var($qry);

?>

  <div class="UPB-add-user">

    <div class="user-role">

      <ul>

        <li class="add-new-role"><span  class="user-icon">ADD USER ROLE</span></li>

        <li class="help">

        <a href="<?php echo admin_url().'admin.php?page=UltimatePB_Custom_User_Role'; ?>"><span class="newrole-icon"></span></a>

        <a href="<?php echo admin_url().'admin.php?page=UltimatePB_Field'; ?>"><span  class="newfield-icon"></span></a>

        <a href="<?php echo admin_url().'admin.php?page=UltimatePB_settings'; ?>"><span  class="newsetting-icon"></span></a>

        </li>

      </ul>

    </div>

    <form  name="add_role" id="add_role" action="" method="post">

      <div class="add-new-role-form">

        <div class="UPB-addrole-label">

          <p>

            <label>Name: </label>

            <input id="role_name" name="role_name" class="role_name" type="text" >

          </p>

        </div>

        <div class="UPB-addrole-label">

          <p>

            <label>Permission Level: </label>

            <select name="parent_role" id="parent_role">

              <?php

			  foreach($roles as $key=>$role)

			  {

				  echo '<option value="'.$key.'">'.$role['name'].'</option>';	

			  }

			  ?>

            </select>

          </p>

        </div>

        <div class="role-display-none" style="display:none; float:left; color:red; width:100%;">Please Enter Valid Role Name (only a-z,A-Z,0-9 allowed)</div>

          <div class="error_message" style="float:left; color:red; width:100%;"><?php if(isset($error)) echo $error; ?></div>

        <div class="add-role-submit" style="margin-top:10px;">

          <?php wp_nonce_field('upb_add_user_role'); ?>

          <input id="role_submit" name="role_submit" class="role_submit" type="submit" value="Save" onClick="return blankselect()" on>

        </div>

      </div>

    </form>

    <?php endif; ?>

  </div>

</div>

<script type="text/javascript">

function blankselect() //Checks if user role field is empty

{

	var role = jQuery('#role_name').val();

	var reg = /^[a-zA-Z0-9]*$/;

	if(!reg.test(role))

	{

		jQuery('.role-display-none').css('display','block');

		return false;	

	}

}

</script> 