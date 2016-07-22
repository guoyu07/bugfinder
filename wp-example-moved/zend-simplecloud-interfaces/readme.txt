=== Zend SimpleCloud Interfaces ===
Contributors: 99bots
Tags: zend, simplecloud, cloud, file storage, document storage, simple queue, service, php5
Tested up to: 2.9.2
Requires at least: 2.0
Stable tag: 1.0.0a1
Donate link: http://99bots.com/donate

The Zend SimpleCloud Interfaces contains everything you need to start writing scalable, highly available, and resilient cloud application plugins.

== Description ==

This plugin embeds and loads the Zend SimpleCloud PHP 5 client interfaces for supporting cloud applications that are portable across all major cloud vendors so that the client interfaces can be shared by different Wordpress plugins.    

This plugin fulfills the Zend SimpleCloud Framework dependency for other Wordpress plugins.  A significant benefit of using this plugin as your Zend SimpleCloud dependency instead of including the Zend SimpleCloud Framework directly in your individual plugins is to minimize redundancy, and to guarantee the latest version of the Zend SimpleCloud Framework.

The Zend SimpleCloud Framework is an open source, object-oriented web application framework implemented in PHP 5 and licensed under the New BSD License.

The current version uses Zend SimpleCloud 1.0.0a1

Please note that the current version of Zend SimpleCloud is considered a technology preview of Zend_Cloud.  Zend does NOT recommend using it in production, as the API is still subject to change.

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

See "Other Notes" for usage.

== Usage ==

Zend SimpleCloud Interfaces is automatically made available in the PHP include path.

You may check Zend SimpleCloud Interfaces availability using the WP_ZEND_SIMPLECLOUD_INTERFACES constant.  Here is an example of how to do that in your plugin code:

`function check_for_zend_simplecloud_interfaces() {
  // if the Zend SimpleCloud Interfaces plugin is successfully
  // loaded this constant is set to true
  if (defined('WP_ZEND_SIMPLECLOUD_INTERFACES') && 
     constant('WP_ZEND_SIMPLECLOUD_INTERFACES')) {
    return true;
  }
  // you can also check if the Zend SimpleCloud Interfaces are 
  // available on the system
  $paths = explode(PATH_SEPARATOR, get_include_path());
  foreach ($paths as $path) {
    if (file_exists("$path/Zend/Loader.php")) {
      define('WP_ZEND_SIMPLECLOUD_INTERFACES', true);
      return true;
    }
  }
  // nothing found, you may advice the user to install 
  // the Zend SimpleCloud Interfaces plugin
  define('WP_ZEND_SIMPLECLOUD_INTERFACES', false);
}

add_action('init', 'check_for_zend_simplecloud_interfaces');`
