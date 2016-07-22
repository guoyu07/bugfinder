<?php

/*Controls "Manage User Roles" setting in dashboard*/

$path =  plugin_dir_url(__FILE__); 

global $wpdb;

$upb_fields =$wpdb->prefix."upb_fields";

if(isset($_POST['delete_role']))//For deleting user role

{		

	$retrieved_nonce = $_REQUEST['_wpnonce'];

  	if (!wp_verify_nonce($retrieved_nonce, 'upb_remove_user_role' ) ) die( 'Failed security check' );



	 remove_role( $_POST['role']); 	

}

?>

<!--HTML for showing user roles list-->

<div class="main">

  <div class="header"></div>

  <div class="content-wrap">

    <div class="pre-s-main">

      <div class="pre-s-top-part">

        <div class="pres-s-left-icon"> <img src="<?php echo $path; ?>images/upb-logo.jpg"/> </div>

        <div class="pres-s-heading" style="margin-top:15px;"> <a href="http://cmshelplive.com/chl-products/ultimate-profile-builder-pro.html" ><img src="<?php echo $path; ?>images/pro-banner-ubp.jpg" /></a> </div>

        <br>

        <div class="UPB-add-user">

          <div class="user-role manage-custom-field">

            <ul>

              <li class="upb_message"><span  class="user-icon">User Roles</span></li>

              <li class="help">

        <a href="<?php echo admin_url().'admin.php?page=UltimatePB_Custom_User_Role'; ?>"><span class="newrole-icon"></span></a>

        <a href="<?php echo admin_url().'admin.php?page=UltimatePB_Field'; ?>"><span  class="newfield-icon"></span></a>

        <a href="<?php echo admin_url().'admin.php?page=UltimatePB_settings'; ?>"><span  class="newsetting-icon"></span></a>

        </li>

            </ul>

          </div>

        </div>

      </div>

    </div>

 

 

    <div id="profile_fields" class="UPB-profile-fields">

      <div id="container">

        <div class="rows profile-top-user">

          <div class="cols" style="min-width:25px;">&nbsp;</div>

          <div class="cols" style="min-width:54px; text-align:center">Id</div>

          <div class="cols">Role Name</div>

          <div class="cols">Role Value</div>

        </div>

       

<?php

$roles = get_editable_roles();//Gets roles from database to fill user role list

$i = 1;

foreach($roles as $key=>$role)

{

?>

<style type="text/css">

.input-width input{

	width:25px;

}

</style>

        <div class="rows result">

          <div class="cols" style="min-width:25px;">

          <?php if($key == 'administrator' || $key == 'editor' || $key == 'subscriber' || $key == 'contributor' || $key=='author') :?>

          <input style="width:20px;" type="button" class="button" value="Delete" name="delete_role" id="delete_field_disable"/>

          <?php else: ?>

            <form method="post" action="">

            	<?php wp_nonce_field('upb_remove_user_role'); ?>

              <input type="hidden" value="<?php echo $key;?>" name="role" />

              <input type="submit" class="button" value="Delete" name="delete_role" id="delete_field" onClick="return confirmdelete()"/>

            </form>

            <?php endif; ?>

          </div>

          <div class="cols" style="min-width:54px; text-align:center"><?php echo $i;?></div>

          <div class="cols"><?php echo $role['name'];?></div>

          <div class="cols"><?php echo $key;?></div>

         

        </div>

        

<?php $i++; } ?>

        <div class="add_new_field"><a href="?page=UltimatePB_Custom_User_Role" class="button-primary">Add New Role</a></div>

      </div>

    </div>

   

  </div>

</div>

<script>

function confirmdelete()//Confirmation for deleting a user role

{

	var a = confirm("Are you Sure ?");

	if(a==true)

	{

		return true;	

	}

	else

	return false;

}

</script>