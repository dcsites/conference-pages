<?php
/**
 * Plugin Name: Conference Pages
 * Plugin URI: https://github.com/dcsites/conference-pages
 * Description: Adds microsites for conferences
 * Version: 0.1
 * Author: ryanshoover
 * Author URI: https://dcsit.es
 * Text Domain: conference
 */

namespace ConferencePages;

define( __NAMESPACE__ . '\SLUG', 'conference-pages' );
define( __NAMESPACE__ . '\VERSION', '0.1.5' );
define( __NAMESPACE__ . '\PATH', plugin_dir_path(__FILE__) );
define( __NAMESPACE__ . '\URL', plugin_dir_url(__FILE__) );

require_once( PATH . 'inc/core.php' );

if ( is_admin() ) {
	require_once( PATH . 'inc/admin.php' );
}
