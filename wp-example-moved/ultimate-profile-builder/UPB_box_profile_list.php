<?php

$path =  plugin_dir_url(__FILE__);   // define path to link and scripts

if(isset($content['role']))

	$role = $content['role'];

	

$pageURL = get_permalink();

$sign = strpos($pageURL,'?')?'&':'?';

$item_per_page = $wpdb->get_var("select value from $upb_option where fieldname='upb_profile_max_resutls'");

$profile_view = $wpdb->get_var("select value from $upb_option where fieldname='upb_profile_list_view'");

$position = 1; 

/*Shows profile page on clicking a profile in list view*/

if(isset($_REQUEST['id']))

{

	$id = $_REQUEST['id'];

}

	if(isset($id))

	{

		include 'UPB_thirdpartyprofile.php';

	}

	else

	{

	/*Shows search results*/

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

/*Shows all profiles in absence of search criteria*/

{

  if(isset($role)) /*Fetches profiles from a specific role if defined in shortcode*/

  {

	  $my_users = new WP_User_Query( 

			  array( 

				  'role' => $role,

				  'offset' => $position ,

				  'number' => $item_per_page,

			  ));

  }

  else

  /*Shows all profiles if role is not specified in shortcode*/

  {

	  $my_users = new WP_User_Query( 

			  array( 

				  'offset' => $position ,

				  'number' => $item_per_page,

			  ));

  }

}

/*Counts total profiles fetched*/

$get_total_rows = $my_users->total_users;

/*Define number of profiles on a single page*/

$pages = ceil($get_total_rows/$item_per_page);

/*Creates pagination*/

if($pages > 1)

{

	$pagination	= '';

	$pagination	.= '<div align="center" class="pagination"><ul class="paginate">';

	for($i = 1; $i<=$pages; $i++)

	{

		$pagination .= '<li><a href="#" class="paginate_click" id="'.$i.'-page">'.$i.'</a></li>';

	}

	$pagination .= '</ul></div>';

}

?>

<script type="text/javascript">

<?php

if($profile_view=='box')

{

	?>

	var current_layout = '0';

	<?php

}

else 

{

	?>

	var current_layout = '1';

	<?php

}

?>

jQuery(document).ready(function() {

	jQuery("#listusers").load("<?php echo get_option('siteurl').'/wp-admin/admin-ajax.php';?>?action=fetch_pages&cookie=encodeURIComponent(document.cookie)&search=<?php if(isset($_REQUEST['search'])) echo $_REQUEST['search'];?>&pageurl=<?php if(isset($pageURL)) echo $pageURL;?>&role=<?php if(isset($role)) echo $role; ?>", {'page':0}, function() {jQuery("#1-page").addClass('active');});  //initial page number to load

	

	jQuery(".paginate_click").click(function(e) {

		jQuery("#listusers").prepend('<div class="loading-indication"><img src="<?php echo $path;?>/images/ajax-loader.gif" /> Loading...</div>');

		var clicked_id = jQuery(this).attr("id").split("-"); //ID of clicked element, split() to get page number.

		var page_num = parseInt(clicked_id[0]); //clicked_id[0] holds the page number we need 

		jQuery('.paginate_click').removeClass('active'); //remove any active class

        //post page number and load returned data into result element

        //notice (page_num-1), subtract 1 to get actual starting point

		jQuery("#listusers").load("<?php echo get_option('siteurl').'/wp-admin/admin-ajax.php';?>?action=fetch_pages&cookie=encodeURIComponent(document.cookie)&search=<?php if(isset($_REQUEST['search'])) echo $_REQUEST['search'];?>&pageurl=<?php if(isset($pageURL)) echo $pageURL;?>&role=<?php if(isset($role)) echo $role; ?>", {'page':(page_num-1)}, function(){

		});

		jQuery(this).addClass('active'); //add active class to currently clicked element (style purpose)

		return false; //prevent going to herf link

	});	

});

</script>

<?php include 'UPB_theme.php'; ?>

<div id="UPB-Standard-Form"> 

<div id="top-entry-header-user-name">

  

     <h3>Members</h3>

     <div class="UPB-Button-input right">

      <div class="upb-search-from-left">

        <form action="" id="form1" name="form1" method="post">

          Search members

          <input type="text" id="search" name="search" />

        </form>

      </div>

      

      <div class="viewselector"> 

     <a class="listview" onClick="listview()"></a> 

     <a class="gridview" onClick="gridview()"></a> 

     <div class="clear"></div>

     </div><!-------viewselector------->

     

     <div class="clear"></div>

     

    </div>

    

   <div class="clear"></div>

   

  </div><!------------top-entry-header-user-name------->

<div id="UPB-Standard-Form-entry-content"> 

    

    <!-----------------------Grid View Starts-------------------------->

    

    <div class="box-view-main" id="listusers"> 

    <div class="clear"></div>

    </div>

    <?php if(isset($pagination)) echo $pagination; ?>

    <!-----------------------Grid View Ends--------------------------> 

  <div class="clear"></div>

  </div><!----------------UPB-Standard-Form-entry-content-------------->

  

<div class="clear"></div>

  

</div><!---------UPB-Standard-Form------------>

<?php } ?>