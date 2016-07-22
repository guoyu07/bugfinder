<?php

/*This code controls changing and activating plugin specific theme from dashboard*/

if($_POST['theme'])

{

	$upb_option=$wpdb->prefix."upb_option";

	$update="update $upb_option set value='".$_POST['theme']."' where fieldname='upb_theme'";

	$wpdb->query($update);

	echo 'success';

}

?>