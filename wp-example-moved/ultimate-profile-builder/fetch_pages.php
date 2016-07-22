<script>

/*Controls profile list view on the front end.*/

var view = window.location.hash;

if(current_layout==0 || view=="#grid")

gridview();

if(current_layout==1 || view=="#list")

listview();

/*Controls grid view jQuery on front end*/

function gridview()

{

	current_layout=0;

	window.location.hash = 'grid';

	jQuery("#listusers .list-box").hide("slow");

	jQuery(".listview_header").hide("slow");

	jQuery("#listusers .box-view").show("slow");

}

/*Controls list view jQuery on front end*/

function listview()

{

	current_layout=1;

	window.location.hash = 'list';

	jQuery("#listusers .box-view").hide("slow");

	jQuery("#listusers .list-box").show("slow");

	jQuery(".listview_header").show("slow");

}

</script>

<?php

/*Hides blank fields in profile list view on the front end*/

function checkfieldname($fieldname,$value)

{

		global $wpdb;

		$upb_option=$wpdb->prefix."upb_option";

		$select="select value from $upb_option where fieldname='".$fieldname."'";

		$data = $wpdb->get_var($select);

		if($data==$value)

		{

			return true;	

		}

		else

		{

			return	false;	

		}

}

$role = $_REQUEST['role'];

$path =  plugin_dir_url(__FILE__);   // define path to link and scripts

$pageURL = $_REQUEST['pageurl'];

$sign = strpos($pageURL,'?')?'&':'?';	

$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);

$upb_option=$wpdb->prefix."upb_option";

$item_per_page = $wpdb->get_var("select value from $upb_option where fieldname='upb_profile_max_resutls'");

if(!is_numeric($page_number)){die('Invalid page number!');}

$position = ($page_number * $item_per_page);

$endlimit = $position + $item_per_page;

$usermeta_table=$wpdb->prefix."usermeta";

$users_table=$wpdb->prefix."users";

/*Returns profiles for list view based on search criteria. If there is no search criteria, then all profiles are showed*/ 

if(!empty($_REQUEST['search']))

{

	$search = ( isset($_REQUEST['search']) ) ? sanitize_text_field($_REQUEST['search']) : false ;			

			$args = array(

			'offset' => $position ,

			'number' => $item_per_page,

			'meta_query' => array(

			'relation' => 'OR',

			array(

				'key'     => 'first_name',

				'value'   => $search,

				'compare' => 'LIKE'

				 ),

			array(

				'key'     => 'last_name',

				'value'   => $search,

				'compare' => 'LIKE'

				)

					)

						 );

	$my_users = new WP_User_Query($args);	

}

else

{

	$my_users = new WP_User_Query( 

			array( 

				'role' => $role,

				'offset' => $position ,

				'number' => $item_per_page,

			));

}

$blogusers = $my_users->get_results();

$get_total_rows = $my_users->total_users;

/*Controls width of a single profile box in profiles grid view on front end based on column value in dashboard plugin settings*/

$query = "select value from $upb_option where fieldname='upb_profile_list_column'";

$value = $wpdb->get_var($query);

$column = $value;

if($column==1)

{

  $width = 96;

}

if($column==2)

{

  $width = 43;

}

if($column==3)

{

	$width = 27;

	

}

if($column==4)

{

  $width = 18;

  

}

if($get_total_rows!=0)

{ 

?>

<div class="listview_header" style="display:none;">

  <div class="profile-avatar">Avatar</div>

  <div class="listview-proflle-name">Name</div>

  <div class="about-profile">About</div>

  <div class="post-profile">Posts</div>

  <div class="clear"></div>

</div>

<?php 

} 

if($get_total_rows==0)

{

	echo "Oops! No users in this group yet.";

}

$i = 1;

$j = 0;

foreach($blogusers as $row)

{

  

  $userid = $row->ID;

  $user_info = get_userdata($userid);

  $user_firstname = $user_info->user_firstname;

  $user_lastname = $user_info->user_lastname;

  $user_description = $user_info->user_description;

  $publish_posts = $user_info->publish_posts;

  $avtar_image = get_user_meta( $userid, 'avtar_image' );

?>

<!----------------------- Grid view layout start -------------------------->

<div class="box-view" id="userview" style="width:<?php echo $width.'%';?>;">

<!-- show first name and last name if first name empty then show user name ----->

  <div class="box-view-name" id="userviewname"> <a href="<?php echo $pageURL; ?><?php echo $sign; ?>id=<?php echo $userid; ?>">

    <?php if($user_firstname!=""){ 

										echo $user_firstname . " " . $user_lastname; 

										}

										else

										{

										echo  $user_info->user_login;

										}

										?>

    </a> </div>

  <div class="box-view-img" align="center" id="userviewimg"> 

  <a href="<?php echo $pageURL; ?><?php echo $sign; ?>id=<?php echo $userid; ?>">

  <!--show user avatar if exists --->

    <?php if(isset($avtar_image[0]) && $avtar_image[0]!='') :?>

    

    

   						 		 <img src="<?php echo $avtar_image[0]; ?>"/>

  						

    <?php else :?>

    <div class="default_profile_pic_boxview">

      <?php

		  $userinfo = get_userdata($userid);

     	  $username = $userinfo->user_login;

     	  $firstname = $userinfo->first_name;

		  $lastname = $userinfo->last_name;

		  

		  	if($firstname!="")

			{

				echo substr($firstname,0,1);

				echo substr($lastname,0,1);

					

			}

			else

			{

				echo substr($username,0,2);

			}

		  

		  

		  ?>

    </div>

    <?php endif; ?>

    </a> </div>

   <div class="clear"></div>

  <div class="profile-dec" align="center">

    <?php

									$user_description = substr($user_description, 0, 50); 

									echo $user_description."...";

    ?>

  </div>

  <div class="box-view-post" id="userviewpost"> Post <span class="box-view-post-num" id="userviewpostnum"><?php echo count_user_posts( $userid ); ?></span> </div>

  <div class="clear"></div>

</div>

<?php

if($i<=$column){ if($i==$column) echo '<div class="upb_box_wrapper"></div>';}

if($i>$column){ if($i%$column==0) echo '<div class="upb_box_wrapper"></div>';}

?>

<!----------------------- Grid view layout end -------------------------->

<!----------------------- List view layout start -------------------------->

<div class="list-box" style="display:none;"> <a href="<?php echo $pageURL; ?><?php echo $sign; ?>id=<?php echo $userid; ?>">

  <div class="profile-avatar">

    <?php if(isset($avtar_image[0]) && $avtar_image[0]!='') :?>

          <img src="<?php echo $avtar_image[0]; ?>" width="38" height="40" />

    <?php else :?>

    <div class="default_profile_pic_listview">

      <?php

		  $userinfo = get_userdata($userid);

     	  $username = $userinfo->user_login;

     	  $firstname = $userinfo->first_name;

		  $lastname = $userinfo->last_name;

		  

		  	if($firstname!="")

			{

				echo substr($firstname,0,1);

				echo substr($lastname,0,1);

					

			}

			else

			{

				echo substr($username,0,2);

			}

		  

		  

		  ?>

    </div>

    <?php endif; ?>

  </div>

  <div class="listview-proflle-name">

    <?php

  if($user_firstname!="")

  {

  echo $user_firstname . " " . $user_lastname;

  }

  else

  {

  echo $user_info->user_login;	

  }

?>

  </div>

  <div class="about-profile ">

    <?php

  $user_description = substr($user_description, 0, 50);

  echo $user_description."...";

   ?>

  </div>

  <div class="post-profile">

    <?php

	echo count_user_posts( $userid );

	?>

  </div>

  </a> </div>

<?php

$i++;

}

?>