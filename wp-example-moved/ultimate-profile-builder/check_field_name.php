<?php

/*Used during custom field creation - Cross checks if the custom field being created already exists or not*/

global $wpdb;

$upb_fields =$wpdb->prefix."upb_fields";

if(isset($_POST['prevalue']))

{

	$qry = "select count(*) from $upb_fields where Name ='".$_POST['name']."' and Name !='".$_POST['prevalue']."'";

	$result = $wpdb->get_var($qry);

	if($result!=0)

	{

		echo '<div style=" color:red">Warning! Field label already exists. Please choose a unique label.</div>';	

	}

}

else

{

	$qry = "select * from $upb_fields where Name ='".$_POST['name']."'";

	$result = $wpdb->get_var($qry);

	if($result!=0)

	{

		echo '<div style=" color:red">Warning! Field label already exists. Please choose a unique label.</div>';	

	}

}

?>