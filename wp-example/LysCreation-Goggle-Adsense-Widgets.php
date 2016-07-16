<?php

/*

Plugin Name: Lys Creation Google Adsense (Plugin) Widget

Plugin URI: http://www.lyscreation.net/

Description: Adds a sidebar widget to display Customized Google Adsense. Make your own Adds in the widget-control-panel.

Author: Med BELAADEL

Version: 0.1.2

License: GPL

Author URI: http://www.LYSCREATION.net

License: GPL2



    Copyright 2011  Mohammed BELAADEL  (email : contact@lyscreation.com)



    This program is free software; you can redistribute it and/or modify

    it under the terms of the GNU General Public License version 2, 

    as published by the Free Software Foundation. 

    

    You may NOT assume that you can use any other version of the GPL.



    This program is distributed in the hope that it will be useful,

    but WITHOUT ANY WARRANTY; without even the implied warranty of

    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

    GNU General Public License for more details.

    

    The license for this software can likely be found here: 

    http://www.gnu.org/licenses/gpl-2.0.html

    

*/



if ( ! defined( 'WP_GOOGLE_ADSENSE_PLUGIN_BASENAME' ) )

	define( 'WP_GOOGLE_ADSENSE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );



if ( ! defined( 'WP_GOOGLE_ADSENSE_PLUGIN_NAME' ) )

	define( 'WP_GOOGLE_ADSENSE_PLUGIN_NAME', trim( dirname( WP_GOOGLE_ADSENSE_PLUGIN_BASENAME ), '/' ) );



if ( ! defined( 'WP_GOOGLE_ADSENSE_PLUGIN_DIR' ) )

	define( 'WP_GOOGLE_ADSENSE_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . WP_GOOGLE_ADSENSE_PLUGIN_NAME );



define( 'widget_max_nbre', '12' );

define( 'produit', 'ggl-adds-widget' );

/*

class widget_google_adsense_init extends WP_Widget {

*/

function widget_google_adsense_init() {            



	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )



		return;

		

	function widget_google_adsense_set_plugin_meta($links, $file){

	  $plugin = WP_GOOGLE_ADSENSE_PLUGIN_BASENAME;  

	  if ($file == $plugin) 

	  {

		$lien = 'http://www.lyscreation.net/donate?plugin='.produit;

	

		$links[] = '<a href="http://wordpress.org/extend/plugins/adsense-google-widget/" title="WordPress Site" target="_blank">' . 'Download plugin' . '</a>';    

		$links[] = '<a href="'.$lien.'" title="Donate / Don" target="_blank">' . '"Donate / Don" - PayPal' . '</a>';

	  }

	  return $links;

	}

	if( is_admin() )

	{

	  add_filter( 'plugin_row_meta', 'widget_google_adsense_set_plugin_meta', 10, 2 );

	}





	function widget_google_adsense_control($number) {

		$options = $newoptions = get_option('widget_google_adsense');

		if ( $_POST["google_adsensesubmit-$number"] ) {

			$newoptions[$number]['title'] = strip_tags(stripslashes($_POST["google_adsensetitle-$number"]));

			$newoptions[$number]['ID'] = strip_tags(stripslashes($_POST["google_adsenseID-$number"]));

			$newoptions[$number]['width'] = strip_tags(stripslashes($_POST["google_adsensewidth-$number"]));

			$newoptions[$number]['height'] = strip_tags(stripslashes($_POST["google_adsenseheight-$number"]));

			$newoptions[$number]['format'] = strip_tags(stripslashes($_POST["google_adsenseformat-$number"]));

			$newoptions[$number]['content'] = strip_tags(stripslashes($_POST["google_adsensecontent-$number"]));

			$newoptions[$number]['type'] = strip_tags(stripslashes($_POST["google_adsensetype-$number"]));

			$newoptions[$number]['b_color'] = strip_tags(stripslashes($_POST["google_adsense_b_color-$number"]));

			$newoptions[$number]['bg_color'] = strip_tags(stripslashes($_POST["google_adsense_bg_color-$number"]));

			$newoptions[$number]['l_f_color'] = strip_tags(stripslashes($_POST["google_adsense_l_f_color-$number"]));

			$newoptions[$number]['f_color'] = strip_tags(stripslashes($_POST["google_adsense_f_color-$number"]));

			$newoptions[$number]['l_f_hover'] = strip_tags(stripslashes($_POST["google_adsense_l_f_hover-$number"]));

			$newoptions[$number]['show'] = strip_tags(stripslashes($_POST["google_adsenseshow-$number"]));

			$newoptions[$number]['numero'] = strip_tags(stripslashes($_POST["numero-$number"]));



		}

		if ($options[$number]['title']=='') {

			$newoptions[$number]['title'] = 'Google Adsense';

		}

		if ($options[$number]['ID']=='') {

			$newoptions[$number]['ID'] = 'pub-1697768673775509'; /* pub-6795014292675223 */

		}

		if ($options[$number]['width']=='') {

			$newoptions[$number]['width'] = '300';

		}

		if ($options[$number]['height']=='') {

			$newoptions[$number]['height'] = '250';

		}

		if ($options[$number]['content']=='') {

			$newoptions[$number]['content'] = '300|250';

		}

		if ($options[$number]['type']=='') {

			$newoptions[$number]['type'] = 'text_image';

		}

		if ($options[$number]['b_color']=='') {

			$newoptions[$number]['b_color'] = 'EBEAEA';

		}

		if ($options[$number]['bg_color']=='') {

			$newoptions[$number]['bg_color'] = 'ffffff';

		}

		if ($options[$number]['l_f_color']=='') {

			$newoptions[$number]['l_f_color'] = 'DB4304';

		}

		if ($options[$number]['f_color']=='') {

			$newoptions[$number]['f_color'] = '000000';

		}

		if ($options[$number]['l_f_hover']=='') {

			$newoptions[$number]['l_f_hover'] = '000000';

		}

		if ( $options != $newoptions ) {

			$options = $newoptions;

			update_option('widget_google_adsense', $options);

		}

		$allSelected = $homeSelected = $postSelected = $pageSelected = $categorySelected = false;

		switch ($options[$number]['show']) {

			case "all":

			$allSelected = true;

			break;

			case "":

			$allSelected = true;

			break;

			case "home":

			$homeSelected = true;

			break;

			case "post":

			$postSelected = true;

			break;

			case "page":

			$pageSelected = true;

			break;

			case "category":

			$categorySelected = true;

			break;

		}    

		$predefini = ($options[$number]['format']==0?0:1);

	?>



		<label for="google_adsensetitle-<?php echo "$number"; ?>" title="" style="line-height:35px;display:block;">Title/Titre: <input type="text" style="width: 442px;" id="google_adsensetitle-<?php echo "$number"; ?>" name="google_adsensetitle-<?php echo "$number"; ?>" value="<?php echo htmlspecialchars($options[$number]['title']); ?>" /></label>



		<label for="google_adsenseID-<?php echo "$number"; ?>" title="" style="line-height:35px;display:block;">Adsense ID: <input type="text" style="width: 382px;" id="google_adsenseID-<?php echo "$number"; ?>" name="google_adsenseID-<?php echo "$number"; ?>" value="<?php echo htmlspecialchars($options[$number]['ID']); ?>" /></label>



		<label for="google_adsensewidth-<?php echo "$number"; ?>" title="" style="line-height:35px;display:block;">Width/Largeur: <input type="text" size="6" maxlength="3" style="width: 80px;" id="google_adsensewidth-<?php echo "$number"; ?>" name="google_adsensewidth-<?php echo "$number"; ?>" value="<?php echo htmlspecialchars($options[$number]['width']); ?>" /> px</label>



		<label for="google_adsenseheight-<?php echo "$number"; ?>" title="" style="line-height:35px;display:block;">Height/Hauteur: <input type="text" size="6" maxlength="3" style="width: 80px;" id="google_adsenseheight-<?php echo "$number"; ?>" name="google_adsenseheight-<?php echo "$number"; ?>" value="<?php echo htmlspecialchars($options[$number]['height']); ?>" /> px</label>



		<label for="google_adsense_b_color-<?php echo "$number"; ?>" title="" style="line-height:35px;display:block;">Border Color / Couleur de bordure: <input onchange="javascript:if(this.value!=''){var couleur = '#'+this.value; document.getElementById('<?php echo "$number"; ?>-google_adsense_b_color').style.backgroundColor=couleur;}else{alert('Empty Color / Couleur Vide');}" type="text" size="6" maxlength="6" style="width: 80px;" id="google_adsense_b_color-<?php echo "$number"; ?>" name="google_adsense_b_color-<?php echo "$number"; ?>" value="<?php echo htmlspecialchars($options[$number]['b_color']); ?>" /><input type="text" size="1" readonly="readonly" id="<?php echo "$number"; ?>-google_adsense_b_color" style="background-color: #<?php echo htmlspecialchars($options[$number]['b_color']); ?>" /></label>



		<label for="google_adsense_bg_color-<?php echo "$number"; ?>" title="" style="line-height:35px;display:block;">Background Color / Couleur de fond: <input onchange="javascript:if(this.value!=''){var couleur = '#'+this.value; document.getElementById('<?php echo "$number"; ?>-google_adsense_bg_color').style.backgroundColor=couleur;}else{alert('Empty Color / Couleur Vide');}" type="text" size="6" maxlength="6" style="width: 80px;" id="google_adsense_bg_color-<?php echo "$number"; ?>" name="google_adsense_bg_color-<?php echo "$number"; ?>" value="<?php echo htmlspecialchars($options[$number]['bg_color']); ?>" /><input type="text" size="1" readonly="readonly" id="<?php echo "$number"; ?>-google_adsense_bg_color" style="background-color: #<?php echo htmlspecialchars($options[$number]['bg_color']); ?>" /></label>



		<label for="google_adsense_f_color-<?php echo "$number"; ?>" title="" style="line-height:35px;display:block;">Text Color / Couleur du Texte: <input onchange="javascript:if(this.value!=''){var couleur = '#'+this.value; document.getElementById('<?php echo "$number"; ?>-google_adsense_f_color').style.backgroundColor=couleur;}else{alert('Empty Color / Couleur Vide');}" type="text" size="6" maxlength="6" style="width: 80px;" id="google_adsense_f_color-<?php echo "$number"; ?>" name="google_adsense_f_color-<?php echo "$number"; ?>" value="<?php echo htmlspecialchars($options[$number]['f_color']); ?>" /><input type="text" size="1" readonly="readonly" id="<?php echo "$number"; ?>-google_adsense_f_color" style="background-color: #<?php echo htmlspecialchars($options[$number]['f_color']); ?>" /></label>



		<label for="google_adsense_l_f_color-<?php echo "$number"; ?>" title="" style="line-height:35px;display:block;">Link Color / Coleur du lien: <input onchange="javascript:if(this.value!=''){var couleur = '#'+this.value; document.getElementById('<?php echo "$number"; ?>-google_adsense_l_f_color').style.backgroundColor=couleur;}else{alert('Empty Color / Couleur Vide');}" type="text" size="6" maxlength="6" style="width: 80px;" id="google_adsense_l_f_color-<?php echo "$number"; ?>" name="google_adsense_l_f_color-<?php echo "$number"; ?>" value="<?php echo htmlspecialchars($options[$number]['l_f_color']); ?>" /><input type="text" size="1" readonly="readonly" id="<?php echo "$number"; ?>-google_adsense_l_f_color" style="background-color: #<?php echo htmlspecialchars($options[$number]['l_f_color']); ?>" /></label>



		<label for="google_adsense_l_f_hover-<?php echo "$number"; ?>" title="" style="line-height:35px;display:block;">Hover Color / Lien du survol: <input onchange="javascript:if(this.value!=''){var couleur = '#'+this.value; document.getElementById('<?php echo "$number"; ?>-google_adsense_l_f_hover').style.backgroundColor=couleur;}else{alert('Empty Color / Couleur Vide');}" type="text" size="6" maxlength="6" style="width: 80px;" id="google_adsense_l_f_hover-<?php echo "$number"; ?>" name="google_adsense_l_f_hover-<?php echo "$number"; ?>" value="<?php echo htmlspecialchars($options[$number]['l_f_hover']); ?>" /><input type="text" size="1" readonly="readonly" id="<?php echo "$number"; ?>-google_adsense_l_f_hover" style="background-color: #<?php echo htmlspecialchars($options[$number]['l_f_hover']); ?>" /></label>



		<label for="google_adsenseformat-<?php echo "$number"; ?>"  title="" style="line-height:35px;display:block;">Predefined / Pr&eacute;d&eacute;fini: <input type="radio" name="google_adsenseformat-<?php echo "$number"; ?>" value="0" <?php if ($predefini==0){echo "checked";} ?>> No/Non <input type="radio" name="google_adsenseformat-<?php echo "$number"; ?>" value="1" <?php if ($predefini==1){echo "checked";} ?>> Yes/Oui ("Yes", select a model / "Oui" choisissez un mod&egrave;le)</label>



		<label for="google_adsensecontent-<?php echo "$number"; ?>" title="" style="width: 495px;display:block;"> Size / Taille

		<select style="width: 270px;" id="google_adsensecontent-<?php echo "$number"; ?>" name="google_adsensecontent-<?php echo "$number"; ?>">

		<optgroup label="Recommand&eacute;e">

		<option <?php echo (htmlspecialchars($options[$number]['content'])=='300|250'?'selected':''); ?> value="300|250">300x250</option>

		<option <?php echo (htmlspecialchars($options[$number]['content'])=='336|280'?'selected':''); ?> value="336|280">336x280</option>

		<option <?php echo (htmlspecialchars($options[$number]['content'])=='728|90'?'selected':''); ?> value="728|90">728x90</option>

		<option <?php echo (htmlspecialchars($options[$number]['content'])=='160|600'?'selected':''); ?> value="160|600">160x600</option>

		</optgroup>

		<optgroup label="Horizontal">

		<option <?php echo (htmlspecialchars($options[$number]['content'])=='468|60'?'selected':''); ?> value="468|60">468x60</option>

		<option <?php echo (htmlspecialchars($options[$number]['content'])=='234|60'?'selected':''); ?> value="234|60">234x60</option>

		</optgroup>

		<optgroup label="Vertical">

		<option <?php echo (htmlspecialchars($options[$number]['content'])=='120|600'?'selected':''); ?> value="120|600">120x600</option>

		<option <?php echo (htmlspecialchars($options[$number]['content'])=='120|400'?'selected':''); ?> value="120|400">120x400</option>

		</optgroup>

		<optgroup label="Car&eacute;e">

		<option <?php echo (htmlspecialchars($options[$number]['content'])=='250|250'?'selected':''); ?> value="250|250">250x250</option>

		<option <?php echo (htmlspecialchars($options[$number]['content'])=='200|200'?'selected':''); ?> value="200|200">200x200</option>

		<option <?php echo (htmlspecialchars($options[$number]['content'])=='180|150'?'selected':''); ?> value="180|150">180x150</option>

		<option <?php echo (htmlspecialchars($options[$number]['content'])=='125|125'?'selected':''); ?> value="125|125">125x125</option>

		</optgroup>

		<optgroup label="Blocs Th&eacute;matiques">

		<option <?php echo (htmlspecialchars($options[$number]['content'])=='728|15'?'selected':''); ?> value="728|15">728x15</option>

		<option <?php echo (htmlspecialchars($options[$number]['content'])=='468|15'?'selected':''); ?> value="468|15">468x15</option>

		<option <?php echo (htmlspecialchars($options[$number]['content'])=='200|90'?'selected':''); ?> value="200|90">200x90</option>

		<option <?php echo (htmlspecialchars($options[$number]['content'])=='180|90'?'selected':''); ?> value="180|90">180x90</option>

		<option <?php echo (htmlspecialchars($options[$number]['content'])=='160|90'?'selected':''); ?> value="160|90">160x90</option>

		<option <?php echo (htmlspecialchars($options[$number]['content'])=='120|90'?'selected':''); ?> value="120|90">120x90</option>

		</optgroup>

		</select></label>



		<label for="google_adsensetype-<?php echo "$number"; ?>" title="" style="width: 495px;display:block;"> Type

		<select style="width: 270px;" id="google_adsensetype-<?php echo "$number"; ?>" name="google_adsensetype-<?php echo "$number"; ?>">

		<optgroup label="Image/Flash">

		<option <?php echo (htmlspecialchars($options[$number]['type'])=='image'?'selected':''); ?> value="image">Image/Flash</option>

		</optgroup>

		<optgroup label="Text">

		<option <?php echo (htmlspecialchars($options[$number]['type'])=='text'?'selected':''); ?> value="text">Text</option>

		</optgroup>

		<optgroup label="Image/Flash & Text">

		<option <?php echo (htmlspecialchars($options[$number]['type'])=='text_image'?'selected':''); ?> value="text_image">Image/Flash & Text</option>

		</optgroup>

		</select></label>



		<label for="google_adsenseshow-<?php echo "$number"; ?>"  title="" style="line-height:35px;">Display only on / Afficher uniquement sur: <select name="google_adsenseshow-<?php echo"$number"; ?>" id="google_adsenseshow-<?php echo"$number"; ?>"><option label="All" value="all" <?php if ($allSelected){echo "selected";} ?>>All / Tous</option><option label="Home" value="home" <?php if ($homeSelected){echo "selected";} ?>>Home / Accueil</option><option label="Post" value="post" <?php if ($postSelected){echo "selected";} ?>>Post(s) / Article(s)</option><option label="Page" value="page" <?php if ($pageSelected){echo "selected";} ?>>Page(s)</option><option label="Category" value="category" <?php if ($categorySelected){echo "selected";} ?>>Category / Cat&eacute;gorie</option></select></label> 

		<input type="hidden" name="google_adsensesubmit-<?php echo "$number"; ?>" id="google_adsensesubmit-<?php echo "$number"; ?>" value="<?php echo "$number"; ?>" />

		<input type="hidden" name="numero-<?php echo "$number"; ?>" id="numero-<?php echo "$number"; ?>" value="<?php echo "$number"; ?>" />

	<?php

	}

	

	function widget_google_adsense($args, $number = 1) {

		$dvwVersion = "Google Adsense Widget v. 0.1.2";

		extract($args);

		$options = get_option('widget_google_adsense');

		$title = $options[$number]['title'];

		if($options[$number]['format']==1){

			if ($options[$number]['content']!='') {

				$pieces = explode("|", $options[$number]['content']);

			}

			else {

				$pieces[0] = 300;

				$pieces[1] = 250;



			}

		}

		else{

			$pieces[0] = $options[$number]['width'];

			$pieces[1] = $options[$number]['height'];

		}

		$i	= 0;

		$starting = '<div class="ngg-galleryoverview" id="google-adsense-widget" style="text-align:center; vertical-align:middle;">

                

'; 

		$ending = '

 	<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>

</div>

'; 

			$show = $options[$number]['show'];                                      // Get the setting on where to show the widget

			$width = $options[$number]['width'];                                    // User specified with of player

			$height = $options[$number]['height'];                                  // User specified height of player

			$google_pub			= $options[$number]['ID'];

			$google_advaced		= $options[$number]['width'];

			$google_prefixed	= $options[$number]['height'];

			if($options[$number]['format']==1){

			$width 			= $pieces[0];

			$height 		= $pieces[1];

			}

			else{

			$width 			= $options[$number]['width'];

			$height 		= $options[$number]['height'];

			}

			$b_color 		= $options[$number]['b_color'];

			$f_color 		= $options[$number]['f_color'];

			$l_f_color 		= $options[$number]['l_f_color'];

			$l_f_hover 		= $options[$number]['l_f_hover'];

			$bg_color 		= $options[$number]['bg_color'];

			$type 			= $options[$number]['type'];



			if ($height=='') {

				if ($width==''){$width = 300;}

				}

			if ($width=='') {

				if ($height==''){$height = 250;}

				}                         

	/* Check the optional parameters and change the link and leading text accordingly */

			/*

			if ($pieces[3]==''){$mediaURL = $medialoc.$mediaID;}

			else {$mediaURL=$pieces[3]; $leadingtext = $pieces[2];}

			*/

			$embeddedAdsense = '

<script type="text/javascript">

	google_ad_client = "'.$google_pub.'";

	google_ad_width = '.$width.';

	google_ad_height = '.$height.';

	google_ad_format = "'.$width.'x'.$height.'_as";

	google_ad_type = "'.$type.'";

	google_color_border = "'.$b_color.'";

	google_color_bg = "'.$bg_color.'";

	google_color_link = "'.$l_f_color.'";

	google_color_text = "'.$f_color.'";

	google_color_url = "'.$l_f_hover.'";

</script>';

/* Put it all together */

		$fulltext = $starting.$embeddedAdsense.$ending;

/* And do the widget dance! */

		?>

		<?php echo $before_widget; ?>

		<?php 

             echo "<div class='DaikosAdsenses'>"; 

/* Do the conditional tag checks. */

   		switch ($show) {

				case "all": 

					$title ? print($before_title . $title . $after_title) : null;

                	echo $fulltext;

					break;

				case "home":

				if (is_home()) {

					$title ? print($before_title . $title . $after_title) : null;

                	echo $fulltext;

		  		}

          		else {

            		echo "<!-- Google Adsense Widget is disabled for this page/post! -->";

          		}

				break;

				case "post":

				if (is_single($slug)) {

					$title ? print($before_title . $title . $after_title) : null;

                	echo $fulltext;

		  		}

          		else {

            		echo "<!-- Google Adsense Widget is disabled for this page/post! -->";

          		}

				break;

				case "page":

				if (is_page($slug)) {

					$title ? print($before_title . $title . $after_title) : null;

                	echo $fulltext;

		  		}

          		else {

            		echo "<!-- Google Adsense Widget is disabled for this page/post! -->";

          		}

				break;

				case "category":

				if (is_category($slug)) {

					$title ? print($before_title . $title . $after_title) : null;

                	echo $fulltext;

		  		}

          		else {

            		echo "<!-- Google Adsense Widget is disabled for this page/post! -->";

          		}

				break;				

			}

              echo "</div>"; ?>

			<?php echo $after_widget; ?>

			<?php

	}

	function widget_google_adsense_setup() {

		$options = $newoptions = get_option('widget_google_adsense');

		if ( isset($_POST['number-google_adsense-submit']) ) {

			$number = (int) $_POST['google_adsense-number'];

			if ( $number > widget_max_nbre ) $number = widget_max_nbre;

			if ( $number < 1 ) $number = 1;

			$newoptions['number'] = $number;

		}

		else {

			$number = widget_max_nbre;

			if ( $number > widget_max_nbre ) $number = widget_max_nbre;

			if ( $number < 1 ) $number = 1;

			$newoptions['number'] = $number;

		}

		if ( $options != $newoptions ) {

			$options = $newoptions;

			update_option('widget_google_adsense', $newoptions);

			widget_google_adsense_register($newoptions['number']);

		}

	}

	function widget_google_adsense_page() {

		$options = $newoptions = get_option('widget_google_adsense');

	?>



		<div class="wrap">

			<form method="POST">

				<h2><?php _e("Google Adsense Widgets", "widgets"); ?></h2>

				<p style="line-height: 30px;"><?php _e('How many Adsense widgets would you like?', 'widgets'); ?>

				<select id="google_adsense-number" name="google_adsense-number" value="<?php echo $options['number']; ?>">

	<?php for ( $i = 1; $i < widget_max_nbre+1; ++$i ) echo "<option value='$i' ".($options['number']==$i ? "selected='selected'" : '').">$i</option>"; ?>

				</select>

				<span class="submit"><input type="submit" name="number-google_adsense-submit" id="number-google_adsense-submit" value="<?php _e('Save'); ?>" /></span></p>

			</form>

		</div>

	<?php

	}



	function widget_google_adsense_register() {

		$options = get_option('widget_google_adsense');

		$number = $options['number'];

		if ( $number < 1 ) $number = 1;

		if ( $number > widget_max_nbre ) $number = widget_max_nbre;

		for ($i = 1; $i <= widget_max_nbre; $i++) {

			$name = array('Google Adsense Widget %s', 'widgets', $i);

			if ( function_exists( 'register_sidebar_widget') ){

				register_sidebar_widget($name, $i <= $number ? 'widget_google_adsense' : 'widget_google_adsense', $i);

				// register_sidebar_widget takes the name of the widget and the function that will be used to display it.

				}

			elseif ( function_exists( 'wp_register_sidebar_widget') ){

 				wp_register_sidebar_widget( $i, $name, $i <= $number ? 'widget_google_adsense' : 'widget_google_adsense', $options );

			}

			if ( function_exists( 'register_widget_control') ){

				register_widget_control($name, $i <= $number ? 'widget_google_adsense_control' : 'widget_google_adsense_control', 490, 455, $i);

				// register_widget_control takes the name of the widget and the function that will be used to to change options (in the design panel).

			}

			elseif ( function_exists( 'wp_register_widget_control') ){

 				wp_register_widget_control( $i, $name, $i <= $number ? 'widget_google_adsense_control' : 'widget_google_adsense_control' );

			}

		}

		add_action('sidebar_admin_setup', 'widget_google_adsense_setup');

		add_action('sidebar_admin_page', 'widget_google_adsense_page');

	}

		for ($i = 1; $i <= widget_max_nbre; $i++) {

			$name = array('Google Adsense Widget %s', 'widgets', $i);

			if ( function_exists( 'register_sidebar_widget') ){

				register_sidebar_widget($name, $i <= $number ? 'widget_google_adsense' : 'widget_google_adsense', $i);

				// register_sidebar_widget takes the name of the widget and the function that will be used to display it.

				}

			elseif ( function_exists( 'wp_register_sidebar_widget') ){

 				wp_register_sidebar_widget( $i, $name, $i <= $number ? 'widget_google_adsense' : 'widget_google_adsense', $options );

			}

			if ( function_exists( 'register_widget_control') ){

				register_widget_control($name, $i <= $number ? 'widget_google_adsense_control' : 'widget_google_adsense_control', 490, 455, $i);

				// register_widget_control takes the name of the widget and the function that will be used to to change options (in the design panel).

			}

			elseif ( function_exists( 'wp_register_widget_control') ){

 				wp_register_widget_control( $i, $name, $i <= $number ? 'widget_google_adsense_control' : 'widget_google_adsense_control' );

			}

		}

	add_action('init', 'widget_google_adsense_register', 12);

}

function google_adsense_display($args){

	$options = get_option('google_adsense_widget');

	extract (shortcode_atts(

	array(

			'url'=> get_permalink(),

			'title' => get_the_title()

	),$args));



	$embeddedAdsense = '

<script type="text/javascript">

	google_ad_client = "'.$options[$number]['ID'].'";

	google_ad_width = '.$options[$number]['width'].';

	google_ad_height = '.$options[$number]['height'].';

	google_ad_format = "'.$options[$number]['width'].'x'.$options[$number]['height'].'_as";

	google_ad_type = "'.$options[$number]['type'].'";

	google_color_border = "'.$options[$number]['b_color'].'";

	google_color_bg = "'.$options[$number]['bg_color'].'";

	google_color_link = "'.$options[$number]['l_f_color'].'";

	google_color_text = "'.$options[$number]['f_color'].'";

	google_color_url = "'.$options[$number]['l_f_hover'].'";

</script>';





	return $embeddedAdsense;

}



add_shortcode('google_adsense_display','google_adsense_display');

add_action('widgets_init', 'widget_google_adsense_init');

//add_action('plugins_loaded', 'widget_google_adsense_init'); 



/*

}

add_action('widgets_init', create_function('', 'return register_widget("widget_google_adsense_init");'));

*/

?>