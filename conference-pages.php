<?php

/*
Plugin Name: My Plugin
Plugin URI: http://ryan.hoover.ws
Description: A plugin by me
Version: 0.1
Author: ryanshoover
Author URI: http://ryan.hoover.ws
Text Domain: my-plugin
*/

namespace MyPlugin;

define( 'MY_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'MY_PLUGIN_URL', plugin_dir_url(__FILE__) );

require_once( MY_PLUGIN_PATH . 'lib/core.php' );

if ( is_admin() )
	require_once( MY_PLUGIN_PATH . 'lib/admin.php' );
