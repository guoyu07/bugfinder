<?php

/*Controls the display of custom fields based on role selected in dashboard settings "Manage Custom Fields"*/

if(isset($_POST['field_user_role']))//Checks if a specific role is selected or not

{

$_SESSION['selectfield'] = $_POST['field_user_role'];

}

else

{

$_SESSION['selectfield']="";

}

$path =  plugin_dir_url(__FILE__); 

global $wpdb;

$upb_fields =$wpdb->prefix."upb_fields";

?>

<!--HTML for displaying the custom fields list-->

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

              <li class="upb_message"><span  class="user-icon">Custom Fields</span></li>

              <li class="help">

        <a href="<?php echo admin_url().'admin.php?page=UltimatePB_Custom_User_Role'; ?>"><span class="newrole-icon"></span></a>

        <a href="<?php echo admin_url().'admin.php?page=UltimatePB_Field'; ?>"><span  class="newfield-icon"></span></a>

        <a href="<?php echo admin_url().'admin.php?page=UltimatePB_settings'; ?>"><span  class="newsetting-icon"></span></a>

        </li>

            </ul>

          </div>

          <div class="header_part upb-head-section">

            <form method="post">

              <label for="field_user_groups">Select User Role</label>

              <select name="field_user_role" id="field_user_role" onChange="submit()">

                <option value="">All Roles</option>

                <?php

$roles = get_editable_roles();//Fills user roles in drop down list

foreach($roles as $key=>$role)

{ 

if($key !=""){

?>

                <option value="<?php echo $key; ?>" <?php if(isset($_SESSION['selectfield']) && $key == $_SESSION['selectfield'])echo 'selected'; ?>><?php echo $role['name']; ?></option>

<?php } 

}

?>

              </select>

            </form>

            <?php

		  $qrycount = "select count(*) from $upb_fields";

			$num_rowscount = $wpdb->get_var($qrycount);

?>

          </div>

        </div>

      </div>

    </div>

    <?php if(isset($_SESSION['selectfield'])) : ?>

    <div id="profile_fields" class="UPB-profile-fields">

      <div id="container">

        <div class="rows profile-top-user">

          <div class="cols" style="min-width:25px;">&nbsp;</div>

          <div class="cols" style="min-width:54px; text-align:center">Id</div>

          <div class="cols">Field Name</div>

          <div class="cols">Field Type</div>

          <div class="cols" style="min-width:50px;">Ordering</div>

          <div class="cols" style="text-align:right;">Action</div>

        </div>

<?php

$qry = "select * from $upb_fields where user_group like '%".$_SESSION['selectfield']."%' order by ordering asc";

$reg = $wpdb->get_results($qry);

if(empty($reg))

{

?>

<!--HTML when there is no existing custom field in currently selected user role-->

        <div class="rows">

          <div class="cols" style="width:100%;">Oops! There are no custom fields for this user role.</div>

        </div>

        <?php 	

}

else

{

$i=1;

foreach($reg as $row)

{

?>

<style type="text/css">

.input-width input{

	width:25px;

}

</style>

<!--HTML when there are already custom fields associated with selected user role-->

        <div class="rows result">

          <div class="cols" style="min-width:25px;">

            <form method="post" class="input-width" action="admin.php?page=UltimatePB_Field_edit">

            <?php wp_nonce_field('upb_delete_field'); ?>

              <input type="hidden" value="<?php echo $row->Id;?>" name="id" />

              <input type="submit" class="button" value="Delete" name="delete_field" id="delete_field" onClick="return confirmdelete()"/>

            </form>

          </div>

          <div class="cols" style="min-width:54px; text-align:center"><?php echo $i;?></div>

          <div class="cols"><?php echo $row->Name;?></div>

          <div class="cols"><?php echo $row->Type;?></div>

          <div class="cols" style="min-width:50px; text-align:center" ><?php echo $row->Ordering;?></div>

          <div class="cols" style="text-align:right; min-width:225px;">

            <form method="post" action="admin.php?page=UltimatePB_Field_edit">

              <input type="hidden" value="<?php echo $row->Id;?>" name="id" />

              <?php wp_nonce_field('upb_copy_field'); ?>

              <input type="submit" class="button" value="Copy" name="copy_field" id="copy_field">

            </form>

            <form method="post" action="admin.php?page=UltimatePB_Field_edit">

            	<?php wp_nonce_field('upb_edit_field'); ?>

              <input type="hidden" value="<?php echo $row->Id;?>" name="id" />

              <input type="submit" class="button" value="Edit" name="edit_field" id="edit_field">

            </form>

          </div>

        </div>

        <?php

		$i++;

}

}

?>

        <div class="add_new_field"><a href="?page=UltimatePB_Field" class="button-primary">Add</a></div>

      </div>

    </div>

    <?php endif; ?>

  </div>

</div>

<script>

function confirmdelete()//Function for confirming deleting custom field

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