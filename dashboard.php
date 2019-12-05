<?php 
/*
  Plugin Name: Admin Dashboard
  Plugin URI: https://www.nearbo.com
	Tags: dashboard,admin,admin dashboard
	Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5X2LYVG55XWQA&source=url
  Description: Admin Dashboard is a simple dashboard plugin, it's overwrite default dashboard page
  Version: 1.0
  Author: Libin Prasanth
  Author URI: https://www.linkedin.com/in/libinprasanth/
  License: GPLv2+
  Text Domain: admin-dashboard
*/
if ( ! defined( 'ABSPATH' ) ) exit;
define('UDASHBOARD_DIR_URL', plugin_dir_url( __FILE__ ).'');
define('UDASHBOARD_DIR_PATH', dirname(__FILE__));
require_once UDASHBOARD_DIR_PATH."/inc/icons.php";
require_once UDASHBOARD_DIR_PATH."/inc/main.php";