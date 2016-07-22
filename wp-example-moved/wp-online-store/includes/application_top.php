<?php

/*

  $Id$



  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com



  Copyright (c) 2008 osCommerce



  Released under the GNU General Public License

*/

// start the timer for the page parse time log

  define('PAGE_PARSE_START_TIME', microtime());

  global $HTTP_POST_VARS,$HTTP_GET_VARS;

  foreach($_POST as $key=>$val)

  	$HTTP_POST_VARS[$key]=$val;

  foreach($_GET as $key=>$val){

  	if($key=="products_id")

		$_GET[$key]=$HTTP_GET_VARS[$key]=str_replace(array('-','_'),array('{','}'),$val);	

	else

		$HTTP_GET_VARS[$key]=$val;	

}	

  

  foreach($GLOBALS as $key=>$val){

  	global $$key;

  		$$key=$val;

  }	

  

  error_reporting(E_ALL & ~E_NOTICE);



// check support for register_globals

  if (function_exists('ini_get') && (ini_get('register_globals') == false) && (PHP_VERSION < 4.3) ) {

    exit('Server Requirement Error: register_globals is disabled in your PHP configuration. This can be enabled in your php.ini configuration file or in the .htaccess file in your catalog directory. Please use PHP 4.3+ if register_globals cannot be enabled on the server.');

  }



// load server configuration parameters

  if (file_exists('includes/local/configure.php')) { // for developers

    include('local/configure.php');

  } else {

    include('configure.php');

  }



  if (strlen(DB_SERVER) < 1) {

    if (is_dir('install')) {

      header('Location: install/index.php');

    }

  }



// define the project version --- obsolete, now retrieved with tep_get_version()

  define('PROJECT_VERSION', 'osCommerce Online Merchant v2.3');



// some code to solve compatibility issues

  require(DIR_WS_FUNCTIONS . 'compatibility.php');



// set the type of request (secure or not)

global $request_type;

  $request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';



// set php_self in the local scope

  $PHP_SELF = (((strlen(ini_get('cgi.fix_pathinfo')) > 0) && ((bool)ini_get('cgi.fix_pathinfo') == false)) || !isset($HTTP_SERVER_VARS['SCRIPT_NAME'])) ? basename($HTTP_SERVER_VARS['PHP_SELF']) : basename($HTTP_SERVER_VARS['SCRIPT_NAME']);



  if ($request_type == 'NONSSL') {

    define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);

  } else {

    define('DIR_WS_CATALOG', DIR_WS_HTTPS_CATALOG);

  }



// include the list of project filenames

  require(DIR_WS_INCLUDES . 'filenames.php');



// include the list of project database tables

  require(DIR_WS_INCLUDES . 'database_tables.php');



// include the database functions

  require(DIR_WS_FUNCTIONS . 'database.php');

  

// start indvship

  function tep_get_configuration_key_value($lookup) {

	$configuration_query_raw= tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key='" . $lookup . "'");

	$configuration_query= tep_db_fetch_array($configuration_query_raw);

	$lookup_value= $configuration_query['configuration_value'];

	return $lookup_value;

  }

// end indvship  



// make a connection to the database... now

  tep_db_connect() or die('Unable to connect to database server!');















// set the application parameters

  $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);

  while ($configuration = tep_db_fetch_array($configuration_query)) {

    define($configuration['cfgKey'], $configuration['cfgValue']);

  }



// OTF contrib begins

  define('PRODUCTS_OPTIONS_TYPE_SELECT', 0);

  define('PRODUCTS_OPTIONS_TYPE_TEXT', 1);

  define('PRODUCTS_OPTIONS_TYPE_RADIO', 2);

  define('PRODUCTS_OPTIONS_TYPE_CHECKBOX', 3);

  define('PRODUCTS_OPTIONS_TYPE_TEXTAREA', 4);

  define('TEXT_PREFIX', 'txt_');



  define('PRODUCTS_OPTIONS_VALUE_TEXT_ID', 14);  //Must match id for user defined "TEXT" value in db table TABLE_PRODUCTS_OPTIONS_VALUES 

// OTF contrib ends



// if gzip_compression is enabled, start to buffer the output

  if ( (GZIP_COMPRESSION == 'true') && ($ext_zlib_loaded = extension_loaded('zlib')) && (PHP_VERSION >= '4') ) {

    if (($ini_zlib_output_compression = (int)ini_get('zlib.output_compression')) < 1) {

      if (PHP_VERSION >= '4.0.4') {

        ob_start('ob_gzhandler');

      } else {

        include(DIR_WS_FUNCTIONS . 'gzip_compression.php');

        ob_start();

        ob_implicit_flush();

      }

    } else {

      ini_set('zlib.output_compression_level', GZIP_LEVEL);

    }

  }



// set the HTTP GET parameters manually if search_engine_friendly_urls is enabled

  if (SEARCH_ENGINE_FRIENDLY_URLS == 'true') {

    if (strlen(getenv('PATH_INFO')) > 1) {

      $GET_array = array();

      $PHP_SELF = str_replace(getenv('PATH_INFO'), '', $PHP_SELF);

      $vars = explode('/', substr(getenv('PATH_INFO'), 1));

      do_magic_quotes_gpc($vars);

      for ($i=0, $n=sizeof($vars); $i<$n; $i++) {

        if (strpos($vars[$i], '[]')) {

          $GET_array[substr($vars[$i], 0, -2)][] = $vars[$i+1];

        } else {

          $HTTP_GET_VARS[$vars[$i]] = $vars[$i+1];

        }

        $i++;

      }



      if (sizeof($GET_array) > 0) {

        while (list($key, $value) = each($GET_array)) {

          $HTTP_GET_VARS[$key] = $value;

        }

      }

    }

  }



// define general functions used application-wide

  require(DIR_WS_FUNCTIONS . 'general.php');

  require(DIR_WS_FUNCTIONS . 'html_output.php');



// set the cookie domain

  $cookie_domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN);

  $cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);



// include cache functions if enabled

  if (USE_CACHE == 'true') include(DIR_WS_FUNCTIONS . 'cache.php');



// include shopping cart class

  require(DIR_WS_CLASSES . 'shopping_cart.php');



// include navigation history class

  require(DIR_WS_CLASSES . 'navigation_history.php');



// define how the session functions will be used

  require(DIR_WS_FUNCTIONS . 'sessions.php');



// set the session name and save path

  tep_session_name('osCsid');

  tep_session_save_path(SESSION_WRITE_DIRECTORY);



// set the session cookie parameters

   if (function_exists('session_set_cookie_params')) {

    session_set_cookie_params(0, $cookie_path, $cookie_domain);

  } elseif (function_exists('ini_set')) {

    ini_set('session.cookie_lifetime', '0');

    ini_set('session.cookie_path', $cookie_path);

    ini_set('session.cookie_domain', $cookie_domain);

  }



  @ini_set('session.use_only_cookies', (SESSION_FORCE_COOKIE_USE == 'True') ? 1 : 0);



// set the session ID if it exists

   if (isset($HTTP_POST_VARS[tep_session_name()])) {

     tep_session_id($HTTP_POST_VARS[tep_session_name()]);

   } elseif ( ($request_type == 'SSL') && isset($HTTP_GET_VARS[tep_session_name()]) ) {

     tep_session_id($HTTP_GET_VARS[tep_session_name()]);

   }



// start the session

global $session_started;

  $session_started = false;

  if (SESSION_FORCE_COOKIE_USE == 'True') {

    tep_setcookie('cookie_test', 'please_accept_for_session', time()+60*60*24*30, $cookie_path, $cookie_domain);



    if (isset($HTTP_COOKIE_VARS['cookie_test'])) {

      //tep_session_start();

      $session_started = true;

    }

  } elseif (SESSION_BLOCK_SPIDERS == 'True') {

  

    $user_agent = strtolower(getenv('HTTP_USER_AGENT'));

    $spider_flag = false;



    if (tep_not_null($user_agent)) {

      $spiders = file(DIR_WS_INCLUDES . 'spiders.txt');



      for ($i=0, $n=sizeof($spiders); $i<$n; $i++) {

        if (tep_not_null($spiders[$i])) {

          if (is_integer(strpos($user_agent, trim($spiders[$i])))) {

            $spider_flag = true;

            break;

          }

        }

      }

    }



    if ($spider_flag == false) {

      //tep_session_start();

      $session_started = true;

    }

  } else {

   // tep_session_start();

    $session_started = true;

  }



  if ( ($session_started == true) && (PHP_VERSION >= 4.3) && function_exists('ini_get') && (ini_get('register_globals') == false) ) {

    extract($_SESSION, EXTR_OVERWRITE+EXTR_REFS);

  }

global $sessiontoken;

// initialize a session token

  if (!tep_session_is_registered('sessiontoken')) {

    $sessiontoken = md5(tep_rand() . tep_rand() . tep_rand() . tep_rand());

    tep_session_register('sessiontoken');

  }

  else $sessiontoken=$_SESSION['sessiontoken'];



// set SID once, even if empty

  $SID = (defined('SID') ? SID : '');



// verify the ssl_session_id if the feature is enabled

  if ( ($request_type == 'SSL') && (SESSION_CHECK_SSL_SESSION_ID == 'True') && (ENABLE_SSL == true) && ($session_started == true) ) {

    $ssl_session_id = getenv('SSL_SESSION_ID');

    if (!tep_session_is_registered('SSL_SESSION_ID')) {

      $SESSION_SSL_ID = $ssl_session_id;

      tep_session_register('SESSION_SSL_ID');

    }



    if ($SESSION_SSL_ID != $ssl_session_id) {

      tep_session_destroy();

      tep_redirect(tep_href_link(FILENAME_SSL_CHECK));

    }

  }



// verify the browser user agent if the feature is enabled

  if (SESSION_CHECK_USER_AGENT == 'True') {

    $http_user_agent = getenv('HTTP_USER_AGENT');

    if (!tep_session_is_registered('SESSION_USER_AGENT')) {

      $SESSION_USER_AGENT = $http_user_agent;

      tep_session_register('SESSION_USER_AGENT');

    }



    if ($SESSION_USER_AGENT != $http_user_agent) {

      tep_session_destroy();

      tep_redirect(tep_href_link(FILENAME_LOGIN));

    }

  }



// verify the IP address if the feature is enabled

  if (SESSION_CHECK_IP_ADDRESS == 'True') {

    $ip_address = tep_get_ip_address();

    if (!tep_session_is_registered('SESSION_IP_ADDRESS')) {

      $SESSION_IP_ADDRESS = $ip_address;

      tep_session_register('SESSION_IP_ADDRESS');

    }



    if ($SESSION_IP_ADDRESS != $ip_address) {

      tep_session_destroy();

      tep_redirect(tep_href_link(FILENAME_LOGIN));

    }

  }



 foreach($_SESSION as $key=>$val){

	if($key!="cart" && $key!="language" && $key!="language_id" && $key!="currency" )

  	global $$key;



  		$$key=$val;



  }	



     global $cart;

   //  print_r($_SESSION);

// create the shopping cart

  if (!tep_session_is_registered('cart') ) {

	$cart = new shoppingCart;

     tep_session_register('cart');

  }

   else {

   if(is_string($_SESSION['cart']))

   	$cart =unserialize($_SESSION['cart']);  

   else

    $cart = new shoppingCart;

    

   tep_session_register('cart');

  //  print_r( $cart);

    }

// include currencies class and create an instance

  require(DIR_WS_CLASSES . 'currencies.php');

  global $currencies;

  global $currency;

  $currencies = new currencies();



// include the mail classes

  require(DIR_WS_CLASSES . 'mime.php');

  require(DIR_WS_CLASSES . 'email.php');



// set the language

global $language,$languages_id;

  if (!tep_session_is_registered('language') || isset($HTTP_GET_VARS['language'])) {

   



    include(DIR_WS_CLASSES . 'language.php');

    global $lng;

    $lng = new language();



    if (isset($HTTP_GET_VARS['language']) && tep_not_null($HTTP_GET_VARS['language'])) {

   	 tep_session_unregister('language');

      tep_session_unregister('languages_id');

      $lng->set_language($HTTP_GET_VARS['language']);

    } else {

      $lng->get_browser_language();

    }



   $language = $lng->language['directory'];

  $languages_id = $lng->language['id'];

    

   if (!tep_session_is_registered('language')) {

      tep_session_register('language');

      tep_session_register('languages_id');

    }

  }

$language = $_SESSION['language'];

	 $languages_id = $_SESSION['languages_id'];

    

// include the language translations

  require(DIR_WS_LANGUAGES . $language . '.php');



// currency

  if (!tep_session_is_registered('currency') || isset($HTTP_GET_VARS['currency']) || ( (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && (LANGUAGE_CURRENCY != $currency) ) ) {

  



    if (isset($HTTP_GET_VARS['currency']) && $currencies->is_set($HTTP_GET_VARS['currency'])) {

      $currency = $HTTP_GET_VARS['currency'];

    } else {

      $currency = ((USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && $currencies->is_set(LANGUAGE_CURRENCY)) ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;

    }

    

      if (!tep_session_is_registered('currency')) tep_session_register('currency');

  }

  else

  	$currency=$_SESSION['currency'];

//echo $_SESSION['currency'];

// navigation history

  if (!tep_session_is_registered('navigation') || !is_object($navigation)) {

   global $navigation;

    $navigation = new navigationHistory;

     tep_session_register('navigation');

  }

   if (tep_session_is_registered('navigation') && is_object($navigation)) {

  

  	$navigation=$_SESSION['navigation'];

  }

  $navigation->add_current_page();





  if (isset($HTTP_GET_VARS['view'])) {

    switch ($HTTP_GET_VARS['view']) {

      case 'list':

        $_SESSION['listing_mode'] = 'row';

        break;

      case 'grid':

        $_SESSION['listing_mode'] = 'grid';

        break;

    }

  }





// action recorder

  include('classes/action_recorder.php');



// Shopping cart actions

  if (isset($HTTP_GET_VARS['action'])) {

// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled

    if ($session_started == false) {

      tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));

    }



    if (DISPLAY_CART == 'true') {

      $goto =  FILENAME_SHOPPING_CART;

      $parameters = array('action', 'cPath', 'products_id', 'pid');

    } else {

      $goto = basename($PHP_SELF);

      if ($HTTP_GET_VARS['action'] == 'buy_now') {

        $parameters = array('action', 'pid', 'products_id');

      } else {

        $parameters = array('action', 'pid');

      }

    }

    switch ($HTTP_GET_VARS['action']) {

      // customer wants to update the product quantity in their shopping cart

      case 'update_product' : for ($i=0, $n=sizeof($HTTP_POST_VARS['products_id']); $i<$n; $i++) {

                                if (in_array($HTTP_POST_VARS['products_id'][$i], (is_array($HTTP_POST_VARS['cart_delete']) ? $HTTP_POST_VARS['cart_delete'] : array()))) {

                                  $cart->remove($HTTP_POST_VARS['products_id'][$i]);

                                } else {

                                  $attributes = ($HTTP_POST_VARS['id'][$HTTP_POST_VARS['products_id'][$i]]) ? $HTTP_POST_VARS['id'][$HTTP_POST_VARS['products_id'][$i]] : '';

                                  $cart->add_cart($HTTP_POST_VARS['products_id'][$i], $HTTP_POST_VARS['cart_quantity'][$i], $attributes, false);

                                }

                              }

                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));

                              break;

     // customer adds a product from the products page

      case 'add_product' :    if (isset($_POST['products_id']) && is_numeric($_POST['products_id'])) {

                                               // if (tep_has_product_attributes($_POST['products_id']) && PRODUCT_LIST_OPTIONS != 'true' && basename($PHP_SELF) != FILENAME_PRODUCT_INFO) tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_POST['products_id']));

								$attributes=$_POST['id'];

											    

                                if (isset($HTTP_POST_VARS['attrcomb']) && (preg_match("/^\d{1,10}-\d{1,10}(,\d{1,10}-\d{1,10})*$/",$_POST['attrcomb']))) {								$attributes=array();

                                  $attrlist=explode(',',$_POST['attrcomb']);

                                  foreach ($attrlist as $attr) {

                                    list($oid, $oval)=explode('-',$attr);

                                    if (is_numeric($oid) && $oid==(int)$oid && is_numeric($oval) && $oval==(int)$oval)

                                      $attributes[$oid]=$oval;

                                  }

                                }

                                if (isset($_POST['id']) && is_array($_POST['id'])) {

									$attributes=array();

                                  foreach ($_POST['id'] as $key=>$val) {

                                    if (is_numeric($key) && $key==(int)$key && is_numeric($val) && $val==(int)$val)

                                      $attributes=$attributes + $_POST['id'];

                                  }

                                }

                                                $add_quantity = (isset($_POST['cart_quantity']) ? (int)$_POST['cart_quantity'] : 1);

									if($add_quantity == "" || $add_quantity<0) {$add_quantity=1;}

                                $cart->add_cart($_POST['products_id'], $cart->get_quantity(tep_get_uprid($_POST['products_id'], $attributes))+$add_quantity, $attributes);

                              }

                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));

                              break;



      // customer removes a product from their shopping cart

      case 'remove_product' : if (isset($HTTP_GET_VARS['products_id'])) {

                                $cart->remove($HTTP_GET_VARS['products_id']);

                              }

                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));

                              break;

      // performed by the 'buy now' button in product listings and review page

      case 'buy_now' :        if (isset($HTTP_GET_VARS['products_id'])) {

                                if (tep_has_product_attributes($HTTP_GET_VARS['products_id'])) {

                                  tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['products_id']));

                                } else {

                                  $cart->add_cart($HTTP_GET_VARS['products_id'], $cart->get_quantity($HTTP_GET_VARS['products_id'])+1);

                                }

                              }

                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));

                              break;

      case 'notify' :         if (tep_session_is_registered('customer_id')) {

                                if (isset($HTTP_GET_VARS['products_id'])) {

                                  $notify = $HTTP_GET_VARS['products_id'];

                                } elseif (isset($HTTP_GET_VARS['notify'])) {

                                  $notify = $HTTP_GET_VARS['notify'];

                                } elseif (isset($HTTP_POST_VARS['notify'])) {

                                  $notify = $HTTP_POST_VARS['notify'];

                                } else {

                                  tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'notify'))));

                                }

                                if (!is_array($notify)) $notify = array($notify);

                                for ($i=0, $n=sizeof($notify); $i<$n; $i++) {

                                  $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . $notify[$i] . "' and customers_id = '" . $customer_id . "'");

                                  $check = tep_db_fetch_array($check_query);

                                  if ($check['count'] < 1) {

                                    tep_db_query("insert into " . TABLE_PRODUCTS_NOTIFICATIONS . " (products_id, customers_id, date_added) values ('" . $notify[$i] . "', '" . $customer_id . "', now())");

                                  }

                                }

                                tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'notify'))));

                              } else {

                                $navigation->set_snapshot();

                                tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));

                              }

                              break;

      case 'notify_remove' :  if (tep_session_is_registered('customer_id') && isset($HTTP_GET_VARS['products_id'])) {

                                $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . $HTTP_GET_VARS['products_id'] . "' and customers_id = '" . $customer_id . "'");

                                $check = tep_db_fetch_array($check_query);

                                if ($check['count'] > 0) {

                                  tep_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . $HTTP_GET_VARS['products_id'] . "' and customers_id = '" . $customer_id . "'");

                                }

                                tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action'))));

                              } else {

                                $navigation->set_snapshot();

                                tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));

                              }

                              break;

      case 'cust_order' :     if (tep_session_is_registered('customer_id') && isset($HTTP_GET_VARS['pid'])) {

                                if (tep_has_product_attributes($HTTP_GET_VARS['pid'])) {

                                  tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['pid']));

                                } else {

                                  $cart->add_cart($HTTP_GET_VARS['pid'], $cart->get_quantity($HTTP_GET_VARS['pid'])+1);

                                }

                              }

                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));

                              break;

    }

  }



// include the who's online functions

  require(DIR_WS_FUNCTIONS . 'whos_online.php');

  tep_update_whos_online();



// include the password crypto functions

  require(DIR_WS_FUNCTIONS . 'password_funcs.php');



// include validation functions (right now only email address)

  require(DIR_WS_FUNCTIONS . 'validations.php');



// split-page-results

  require(DIR_WS_CLASSES . 'split_page_results.php');



// infobox

  require(DIR_WS_CLASSES . 'boxes.php');



// auto activate and expire banners

  require(DIR_WS_FUNCTIONS . 'banner.php');

  tep_activate_banners();

  tep_expire_banners();



// auto expire special products

  require(DIR_WS_FUNCTIONS . 'specials.php');

  tep_expire_specials();



  require(DIR_WS_CLASSES . 'osc_template.php');

  global $oscTemplate;

  $oscTemplate = new oscTemplate();

 global $cPath;

// calculate category path

  if (isset($HTTP_GET_VARS['cPath'])) {

    $cPath = $HTTP_GET_VARS['cPath'];

  } elseif (isset($HTTP_GET_VARS['products_id']) && !isset($HTTP_GET_VARS['manufacturers_id'])) {

    $cPath = tep_get_product_path($HTTP_GET_VARS['products_id']);

  } else {

    $cPath = '';

  }

 global $cPath_array;

  if (tep_not_null($cPath)) {

    $cPath_array = tep_parse_category_path($cPath);

    $cPath = implode('_', $cPath_array);

    $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];

    

  } else {

    $current_category_id = 0;

  }



// include the breadcrumb class and start the breadcrumb trail

  require(DIR_WS_CLASSES . 'breadcrumb.php');

  global $breadcrumb;

  $breadcrumb = new breadcrumb;



  $breadcrumb->add(HEADER_TITLE_TOP, HTTP_SERVER);

  $breadcrumb->add(HEADER_TITLE_CATALOG, tep_href_link(FILENAME_DEFAULT));



// add category names or the manufacturer name to the breadcrumb trail

  if (isset($cPath_array)) {

    for ($i=0, $n=sizeof($cPath_array); $i<$n; $i++) {

      $categories_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$cPath_array[$i] . "' and language_id = '" . (int)$languages_id . "'");

      if (tep_db_num_rows($categories_query) > 0) {

        $categories = tep_db_fetch_array($categories_query);

        $breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', array_slice($cPath_array, 0, ($i+1)))));

      } else {

        break;

      }

    }

  } elseif (isset($HTTP_GET_VARS['manufacturers_id'])) {

    $manufacturers_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");

    if (tep_db_num_rows($manufacturers_query)) {

      $manufacturers = tep_db_fetch_array($manufacturers_query);

      $breadcrumb->add($manufacturers['manufacturers_name'], tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id']));

    }

  }



// add the products model to the breadcrumb trail

  if (isset($HTTP_GET_VARS['products_id'])) {

    $model_query = tep_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'");

    if (tep_db_num_rows($model_query)) {

      $model = tep_db_fetch_array($model_query);

      $breadcrumb->add($model['products_model'], tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $cPath . '&products_id=' . $HTTP_GET_VARS['products_id']));

    }

  }



// initialize the message stack for output messages

  require(DIR_WS_CLASSES . 'message_stack.php');

   global $messageStack;

  $messageStack = new messageStack;

  

    if ( isset($_REQUEST['keywords']))

  			$HTTP_GET_VARS['keywords']=$_REQUEST['keywords'];

  if ( isset($_REQUEST['dfrom']))

  			$HTTP_GET_VARS['dfrom']=$_REQUEST['dfrom'];

  if ( isset($_REQUEST['dto']))

  			$HTTP_GET_VARS['dto']=$_REQUEST['dto'];

  if ( isset($_REQUEST['pfrom']))

  			$HTTP_GET_VARS['pfrom']=$_REQUEST['pfrom'];

  if ( isset($_REQUEST['pto']))

  			$HTTP_GET_VARS['pto']=$_REQUEST['pto'];

  if ( isset($_REQUEST['manufacturers_id']))

  			$HTTP_GET_VARS['manufacturers_id']=$_REQUEST['manufacturers_id'];

	if ( isset($_REQUEST['categories_id']))

  			$HTTP_GET_VARS['categories_id']=$_REQUEST['categories_id'];

  if ( isset($_REQUEST['filter_id']))

  			$HTTP_GET_VARS['filter_id']=$_REQUEST['filter_id'];			

if ( isset($_REQUEST['sort']))

  			$HTTP_GET_VARS['sort']=$_REQUEST['sort'];		

  			

  global $customer_default_address_id,$customer_id,$customer_first_name,$customer_country_id,$customer_zone_id,$billto,$sendto, $shipping;

  

  	if (tep_session_is_registered('customer_default_address_id')) {

  		$customer_default_address_id=$_SESSION['customer_default_address_id'];

  	}

  	if (tep_session_is_registered('customer_id')) {

  		$customer_id=$_SESSION['customer_id'];

  	}

  	if (tep_session_is_registered('customer_first_name')) {

  		$customer_first_name=$_SESSION['customer_first_name'];

  	}

  	if (tep_session_is_registered('customer_country_id')) {

  		$customer_country_id=$_SESSION['customer_country_id'];

  	}

  	if (tep_session_is_registered('customer_zone_id')) {

  		$customer_zone_id=$_SESSION['customer_zone_id'];

  	}

  	if (tep_session_is_registered('billto')) {

  		$billto=$_SESSION['billto'];

  	}

  	if (tep_session_is_registered('sendto')) {

  		 $sendto=$_SESSION['sendto'];

  	}

  	if (tep_session_is_registered('shipping')) {

  		 $shipping=$_SESSION['shipping'];

  	}



// Discount Code 2.6 - start

	if (MODULE_ORDER_TOTAL_DISCOUNT_STATUS == 'true') {



			

			if (!empty($HTTP_POST_VARS['discount_code'])) 

			{

				 tep_session_register('sess_discount_code'); 	

				 $sess_discount_code =tep_db_prepare_input($HTTP_POST_VARS['discount_code']);

				 

			}

			if($_POST && empty($HTTP_POST_VARS['discount_code'])){

					$_SESSION["sess_discount_code"]="";

			

			}



			

	}

	// Discount Code 2.6 - end

	

	





  // PWA BOF

  if (tep_session_is_registered('customer_id') && tep_session_is_registered('customer_is_guest') && substr(basename($PHP_SELF),0,7)=='account') tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));

// PWA EOF



  // category description

    $category_query = tep_db_query("select cd.categories_name, c.categories_image from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . (int)$languages_id . "'");

  $category = tep_db_fetch_array($category_query);

  $categories_desc_query = tep_db_query("select categories_description from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$cPath_array[count($cPath_array)-1] . "' and language_id = '" . (int)$languages_id . "'");

  $categories_desc = tep_db_fetch_array($categories_desc_query);

  // ends here







?>



