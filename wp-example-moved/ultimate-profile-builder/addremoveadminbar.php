<?php

	global $wpdb;

	global $current_user;	

	$upb_option=$wpdb->prefix."upb_option";

	/*This controls visibility for admin bar in the plugin settings*/

	if ( is_user_logged_in() ) {

	switch (@$current_user->roles[0])

	{

		case "administrator":

		{

			$select="select * from $upb_option where fieldname='upb_adminshowhide'";

			$data = $wpdb->get_results($select);

				if($data[0]->value=='no')

				{

					show_admin_bar( false );

				}

				else

				{

					show_admin_bar( true );					

				}

			break;

		}

		

		case "author":

		{

			$select="select * from $upb_option where fieldname='upb_authorshowhide'";

			$data = $wpdb->get_results($select);

				if($data[0]->value=='no')

				{

					show_admin_bar( false );

				}

				else

				{

					show_admin_bar( true );					

				}

			break;

		}

		

		case "editor":

		{

			$select="select * from $upb_option where fieldname='upb_editorshowhide'";

			$data = $wpdb->get_results($select);

				if($data[0]->value=='no')

				{

					show_admin_bar( false );

				}

				else

				{

					show_admin_bar( true );					

				}

			break;

		}

		case "contributor":

		{

			$select="select * from $upb_option where fieldname='upb_contributorshowhide'";

			$data = $wpdb->get_results($select);

				if($data[0]->value=='no')

				{

					show_admin_bar( false );

				}

				else

				{

					show_admin_bar( true );					

				}

			break;

		}

		case "subscriber":

		{

			$select="select * from $upb_option where fieldname='upb_subscribershowhide'";

			$data = $wpdb->get_results($select);

				if($data[0]->value=='no')

				{

					show_admin_bar( false );

				}

				else

				{

					show_admin_bar( true );					

				}

			break;	

		}

	}

	}

?>