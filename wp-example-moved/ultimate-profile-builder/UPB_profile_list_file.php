<?php

	global $wpdb;

	$upb_option=$wpdb->prefix."upb_option";

	$query = "select value from $upb_option where fieldname='upb_profile_list_view'";

	$value = $wpdb->get_var($query);

	if( $value == "table")

    {

    	include 'UPB_box_profile_list.php';

	}

	else

    {

    	include 'UPB_box_profile_list.php';

	}

?>