<?php

/*Controls profile page view for guest users or other registered users*/

	$path =  plugin_dir_url(__FILE__);  // define path to link and scripts

	$pageURL = get_permalink();

	$sign = strpos($pageURL,'?')?'&':'?';

	$current_id = $_REQUEST['id'];

	$avtar_image = get_user_meta( $current_id, 'avtar_image' );

	global $wpdb;

	$upb_fields =$wpdb->prefix."upb_fields";

	$user_info = get_userdata($current_id);

	$user_description = $user_info->user_description;

function checkfieldname($fieldname,$value) //Checks and hides empty fields

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

				return false;

			}

}	

?>

<?php include 'UPB_theme.php'; ?>

<script language="javascript" type="text/javascript">

function toggleDivFun1(a) /*Creates expand toggle button for large text area field */

{

	jQuery(a).parent('.toggleDiv1').hide();

    jQuery(a).parent('.toggleDiv1').parent('.toggleDiv').children('.toggleDiv2').show();

}

function toggleDivFun2(a)

{

	jQuery(a).parent('.toggleDiv2').hide();

	jQuery(a).parent('.toggleDiv2').parent('.toggleDiv').children('.toggleDiv1').show();

}

</script>

<!--HTML for displaying the profile-->

<div id="UPB-Standard-Form">

    <div id="top-entry-header-user-name">

      <h3>

      <?php the_author_meta('first_name',$current_id); ?>

      &nbsp;

      <?php the_author_meta('last_name',$current_id); ?>

      

      </h3>

      

      <div class="UPB-Button-input right">

       <a class="UPB-Button" href="javascript:void(0);" onclick="javascript:history.back();">Go back</a> 

       </div>

       <div class="clear"></div>

    

     </div><!---------------top-entry-header-user-name--------->

     

  <div id="UPB-Standard-Form-entry-content">

    <div class="UPB-Standard-Form-main-upb-form" >

      <div class="profile-img-device-box">

        <div class="left-box">

          <?php if(isset($avtar_image[0]) && $avtar_image[0]!='') :?>

         

          <div class="img-box"> <img src="<?php echo $avtar_image[0]; ?>" /></div>

                         

          <?php else :?>

          <div class="img-box"> 

          <div class="default_profile_pic">

            <?php  //Displays default image when user has not uploaded an image profile

		  $user_info = get_userdata($current_id);

     	  $username = $user_info->user_login;

     	  $firstname = $user_info->first_name;

		  $lastname = $user_info->last_name;

		  

		  	if($firstname!="")

			{

				echo substr($firstname,0,1);

				echo substr($lastname,0,1);

				$thirdpartyname = $firstname;

					

			}

			else

			{

				echo substr($username,0,2);

				$thirdpartyname = $username;

			}

		  

		  

		  ?>

          </div>

          </div>

          <?php endif;

		  $user_info = get_userdata($current_id);

     	  $username = $user_info->user_login;

     	  $firstname = $user_info->first_name;

		  $lastname = $user_info->last_name;

		  if($firstname!="")

			{

				$thirdpartyname = $firstname;

					

			}

			else

			{

				$thirdpartyname = $username;

			}

		  

		   ?>

        </div>

        

        <div class="right-box">

        <?php if (checkfieldname("upb_nicknameshowhide","yes")==true && (get_user_meta($current_id,'nickname', true) !="")) : ?>

        <div class="user-name-info">Nick Name:

          <?php the_author_meta('nickname',$current_id); ?>

        </div>

        <?php endif; ?>

        <?php if (checkfieldname("upb_usernameshowhide","yes")==true ) : ?>

        <div class="user-name-info">User Name:

          <?php the_author_meta('user_login',$current_id); ?>

        </div>

        <?php endif; ?>

        <?php if (checkfieldname("upb_emailshowhide","yes")==true) : ?>

        <div class="user-name-info user-email-info">Email:

          <?php the_author_meta('user_email',$current_id); ?>

        </div>

        <?php endif; ?>

        <?php if (checkfieldname("upb_websiteshowhide","yes")==true) : ?>

        <div class="user-name-info user-web-info"> Website: <a href="<?php the_author_meta('user_url',$current_id); ?>">

          <?php the_author_meta('user_url',$current_id); ?>

          </a></div>

        <?php endif; ?>

       

        <?php if (checkfieldname("upb_aimshowhide","yes")==true && (get_user_meta($current_id,'aim', true) !="")) : ?>

        <div class="user-name-info user-aim-info"> AIM:

          <?php the_author_meta('aim',$current_id); ?>

        </div>

        <?php endif; ?>

        <?php if (checkfieldname("upb_yahooimshowhide","yes")==true && (get_user_meta($current_id,'yim', true) !="")) : ?>

        <div class="user-name-info user-yahoo-info">Yahoo:

          <?php the_author_meta('yim',$current_id); ?>

        </div>

        <?php endif; ?>

        <?php if (checkfieldname("upb_jabbergoogletalkshowhide","yes")==true && (get_user_meta($current_id,'jabber', true) !="")) : ?>

        <div class="user-name-info user-gtalk-info">Gtalk:

          <?php the_author_meta('jabber',$current_id); ?>

        </div>

        <?php endif; ?>

      </div>

      

      <div class="clear"></div><!--------profile-img-device-box-------------->

	   

      </div>

	  <!--Custom fields start-->

      <div class="custom_fields-box">

      <div class="custom_fields">

        <?php $qry1 = "select * from $upb_fields";

							if ( is_user_logged_in() ) {

								$where = " where Visibility='1' || Visibility='2' ";

							}

							else

							{

								$where = " where Visibility='1'";

							}

							$orderby = " order by ordering asc";

							$qry1.=$where.$orderby;

							$reg1 = $wpdb->get_results($qry1);

							

							foreach($reg1 as $row1)

							{

								

								 if($row1->Type!='textarea')

								 {

								 	$key = str_replace(" ","_",$row1->Name);

									$value = get_user_meta($current_id, $key, true);

								 	if($value!=""):

								?>

        <div class="user-custom_field">

          <?php if($value!="")echo '<div class="field_label">'. $row1->Name.':</div>';?>

          <?php 

								if(is_array($value))

								{

									echo '<div class="field_value">';

									foreach($value as $val)

									{

										echo '<div class="field_mulitple_value">'.$val.'</div>';	

									}

									echo '</div>';

								}

								else

								{

										echo '<div class="field_value">'.$value.'</div>'; 

								}

								?>

                                <div class="clear"></div>

        </div>

        <?php

								 endif;

								 } 

							 }

							 ?>

     <div class="clear"></div>

      </div>

      

      <div class="clear"></div>

      </div>

      <?php if (checkfieldname("upb_biographicalinfoshowhide","yes")==true && $user_description!="") : ?>

      <div class="profile-about-me">

        <h3>About <?php echo $thirdpartyname;?>:</h3>

        <div class="toggleDiv" >

          <div class="toggleDiv1" >

            <?php

					$user_description_half = substr($user_description, 0, 200);

					echo $user_description_half."...";

					

					if(strlen($user_description) > 200)

					{

?>

            <a onclick="toggleDivFun1(this)" href="javascript:void(0);" style="text-decoration:none;"> <img src="<?php echo $path . 'images/read-more.png'; ?>" width="18" height="18" border="0"> </a>

            <?php

					}

?>

          </div>

          <div class="toggleDiv2" style="display:none;">

            <?php

					echo $user_description;

?>

            <a onclick="toggleDivFun2(this)" href="javascript:void(0);" style="text-decoration:none;"> <img src="<?php echo $path . 'images/read-less.png'; ?>" width="18" height="18" border="0"> </a> </div>

        </div>

      </div>

      <?php endif; ?>

      <?php

					$qry2 = "select * from $upb_fields";

					if( is_user_logged_in() ) {

						$where = " where Visibility='1' || Visibility='2' ";

					}

					else

					{

						$where = " where Visibility='1'";

					

					}

					

					$orderby = " order by ordering asc";

					$qry2.=$where.$orderby;

					$reg2 = $wpdb->get_results($qry2);

					

							  foreach($reg2 as $row2)

							  {

								 if($row2->Type=='textarea')

								 {

								 $key = str_replace(" ","_",$row2->Name);

								 $value = get_user_meta($current_id, $key, true);

								 if($value!=""):

								 ?>

      <div class="profile-about-me">

        <div style="font-size:25px;"> <?php echo $row2->Name; ?>: </div>

        <div class="toggleDiv" >

          <div class="toggleDiv1" >

            <?php

							$Valuehalf = substr($value, 0, 200);

							echo $Valuehalf."...";

							if(strlen($value) > 200)

							{

			?>

            <a onclick="toggleDivFun1(this)" href="javascript:void(0);" style="text-decoration:none;"> <img src="<?php echo $path . 'images/read-more.png'; ?>" width="18" height="18" border="0"> </a>

            			<?php

							}

						?>

          </div>

          <div class="toggleDiv2" style="display:none;">

           			 <?php

							echo $value;

						?>

            <a onclick="toggleDivFun2(this)" href="javascript:void(0);" style="text-decoration:none;"> <img src="<?php echo $path . 'images/read-less.png'; ?>" width="18" height="18" border="0"> </a> </div>

        </div>

      </div>

      <?php

	 endif;

 	}

 }

?>

<div class="cler"></div>

    </div>

	<!--HTML for displaying user posts-->

  <div class="clear"></div>

  </div>

  <div class="my-post">

      <?php

					$user_post_count = count_user_posts( $current_id );//Fetches posts

					if($user_post_count && checkfieldname("upb_postshowhide","yes")==true)

					{

?>

      <h3><?php echo $thirdpartyname."'s"; ?> Posts</h3>

      <p>

        <?php

						//global $current_user;

						//get_currentuserinfo();

						$author_query = array('posts_per_page' => '-1','author' => $current_id);

						$author_posts = new WP_Query($author_query);

						while($author_posts->have_posts()) : $author_posts->the_post();

?>

        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">

        <?php

								the_title();

?>

        </a> <br/>

        <?php

						endwhile;

						echo '</p>';

					}

					

				?>

    </div>

</div>

