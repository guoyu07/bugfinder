<?php

/*Controls theme selection from dashboard*/

$path =  plugin_dir_url(__FILE__);  

$upb_option=$wpdb->prefix."upb_option";

$front_theme = $wpdb->get_var("select value from $upb_option where fieldname='upb_theme'");

$border_color = '#000';

$backgroundcolor = '#f9f9f9';

$button_colorLight = '#ffaa00';

$button_colorDark = '#ffc600';

$button_hover = '#f1b900';

$text_shadow = '#e09a00';

 

if($front_theme=='light')

{

 $border_color = '#000';

 $backgroundcolor = '#f9f9f9';

 $button_colorLight = '#ffaa00';

 $button_colorDark = '#ffc600';

 $button_hover = '#f1b900';

 $text_shadow = '#e09a00';

}

?>

<style>

#main-upb-form {

 background-color: <?php echo $backgroundcolor;

 ?> !important;

 border-top: 8px solid <?php echo $border_color;

 ?> !important;

}

/*-----------------------------------Button CSS Start-----------------------------------*/

.UltimatePB-Button, #upb-form input[type="submit"], #upb-form input[type="reset"] {

 background: <?php echo $button_colorLight;

?>;

 <?php if($front_theme!='lightmoderngreen' && $front_theme!='lightmodernyellow'): ?>  border-top: 1px solid <?php echo $button_colorDark;

?>;

 background: -webkit-gradient(linear, left top, left bottom, from(<?php echo $button_colorDark;

?>), to(<?php echo $button_colorLight;

?>));

 background: -webkit-linear-gradient(top, <?php echo $button_colorDark;

?>, <?php echo $button_colorLight;

?>);

 background: -moz-linear-gradient(top, <?php echo $button_colorDark;

?>, <?php echo $button_colorLight;

?>);

 background: -ms-linear-gradient(top, <?php echo $button_colorDark;

?>, <?php echo $button_colorLight;

?>);

 background: -o-linear-gradient(top, <?php echo $button_colorDark;

?>, <?php echo $button_colorLight;

?>);

 text-shadow:0px -1px 0px <?php echo $text_shadow;

?>;

	-webkit-border-radius: 5px;

	-moz-border-radius: 5px;

	border-radius: 5px;

 <?php endif;

?>  padding: 2px 23px;

	color: white;

	font-size: 16px;

	text-decoration: none;

	vertical-align: middle;

	float: left;

	margin-left: 19px;

	line-height: 25px;

}

.UltimatePB-Button:hover, .UltimatePB-Button:active, #upb-form input[type="submit"]:hover, #upb-form input[type="reset"]:hover, #upb-form input[type="submit"]:active, #upb-form input[type="reset"]:active {

 border-top-color: <?php echo $button_hover;

?>;

 background: <?php echo $button_hover;

?>;

}

.UltimatePB-Button-inp input {

 background: <?php echo $button_colorLight;

?>;

 <?php if($front_theme!='lightmoderngreen' && $front_theme!='lightmodernyellow'): ?>  border-top: 1px solid <?php echo $button_colorDark;

?>;

 background: -webkit-gradient(linear, left top, left bottom, from(<?php echo $button_colorDark;

?>), to(<?php echo $button_colorLight;

?>));

 background: -webkit-linear-gradient(top, <?php echo $button_colorDark;

?>, <?php echo $button_colorLight;

?>);

 background: -moz-linear-gradient(top, <?php echo $button_colorDark;

?>, <?php echo $button_colorLight;

?>);

 background: -ms-linear-gradient(top, <?php echo $button_colorDark;

?>, <?php echo $button_colorLight;

?>);

 background: -o-linear-gradient(top, <?php echo $button_colorDark;

?>, <?php echo $button_colorLight;

?>);

	-webkit-border-radius: 5px;

	-moz-border-radius: 5px;

	border-radius: 5px;

 text-shadow: 0px -1px 0px <?php echo $text_shadow;

?>;

 <?php endif;

?>  padding: 2px 23px;

	color: white;

	font-size: 16px;

	font-family: Arial, "Helvetica LT Std", Tahoma;

	text-decoration: none;

	vertical-align: middle;

	float: left;

	margin-left: 20px;

	line-height: 25px;

}

.UltimatePB-Button-inp input:hover, .UltimatePB-Button-inp input:active {

 background: <?php echo $button_hover;

?>;

	color: #fff;

 <?php if($front_theme!='lightmoderngreen' && $front_theme!='lightmodernyellow'): ?>  border-top-color: <?php echo $button_hover;

?>;

 text-shadow: 0px 0px 0px <?php echo $text_shadow;

?>;

 <?php endif;

?>

}

.UltimatePB-Button a {

	font-family: Arial, "Helvetica LT Std", Tahoma;

	font-size: 16px;

	color: #fff !important;

	text-decoration: none;

 <?php if($front_theme!='lightmoderngreen' && $front_theme!='lightmodernyellow'): ?>  text-shadow:0px -1px 0px <?php echo $text_shadow;

?>;

 <?php endif;

?>

}

.UltimatePB-Button a:hover, .UltimatePB-Button a:active {

	font-family: Arial, "Helvetica LT Std", Tahoma;

	font-size: 16px;

	color: #fff;

	text-decoration: none;

 <?php if($front_theme!='lightmoderngreen' && $front_theme!='lightmodernyellow'): ?>  text-shadow:0px -1px 0px <?php echo $text_shadow;

?>;

 <?php endif;

?>

}

/*-----------------------------------Button CSS Ends-----------------------------------*/

</style>