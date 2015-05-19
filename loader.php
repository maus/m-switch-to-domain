<?php
/*
Plugin Name: [m~] Switch Domain
Description: Adds a link in the admin bar in the sites menu for going to the same page on a different domain.
License: GPL
Author: Marius Marinescu (m~)
Author URI: http://marius.marinescu.biz/
Text Domain: m-switch-domain
Domain Path: /languages
Version: 1.0.0
*/

define( "MSD_VERSION", '1.0.0' );

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( "SWITCH_DOMAIN_PLUGIN_URL", plugin_dir_path( __FILE__ ) );

require_once( plugin_dir_path( __FILE__ ) . 'public/class-switch-domain.php' );

add_action( 'plugins_loaded', array( 'mSwitchDomain', 'get_instance' ) );

?>