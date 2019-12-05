<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
/* Admin dashboard class */
class UDASHBOARD_Admin{
	// Custom variables 
	protected $capability = 'read';
	protected $title = 'Dashboard';
	
	/* Function consstruct */
	public function __construct() {
		$this->init();
	}
	/* Function init() */
	public function init(){
		$this->options();
		add_action( 'admin_enqueue_scripts', array($this, 'register_all_scripts') );
		add_action( 'current_screen', array( $this, 'current_screen' ) ); // change curret screen 
		add_action( 'admin_menu', array( $this, 'admin_menu' ) ); // admin custom admin page 
		add_filter( 'admin_title', array( $this, 'admin_title' ), 10, 2 ); // set admin dashboard title  
		add_action( 'admin_init', array($this, 'wpse_60168_custom_menu_class') ); 
		add_action( 'init', array($this, 'app_output_buffer') );
	} 
	
	// app_output_buffer
	public function app_output_buffer() {
	  ob_start();
  }
	
	/* Initiate social media options */
	public function options(){
		
		$dashboard = array(
		  array("name" => "settings", "icon" => "la-gear", "path" => "options-general.php"),
			array("name" => "users", "icon" => "la-users", "path" => "users.php"),
			array("name" => "pages", "icon" => "la-edit", "path" => "edit.php?post_type=page"),
			array("name" => "blog post", "icon" => "la-newspaper-o", "path" => "edit.php"),
			array("name" => "plugins", "icon" => "la-plug", "path" => "plugins.php"),
			array("name" => "social media", "icon" => "la-share-alt-square", "path" => "admin.php?page=dashboard-social"),
			//array("name" => "ajax", "icon" => "spinner", "path" => "admin.php?page=dashboard-ajax")
		);
		add_option('dashboard_dashboard_menus', $dashboard); 
		
		$social = array(
		  array("name" => "facebook", "icon" => "la la-facebook-square", "path" => "", "status" => true),
			array("name" => "twitter", "icon" => "la la-twitter", "path" => "", "status" => true),
			array("name" => "linkedin", "icon" => "la la-linkedin", "path" => "", "status" => true),
			array("name" => "youtube", "icon" => "la la-youtube", "path" => "", "status" => true),
			array("name" => "instagram", "icon" => "la la-instagram", "path" => "", "status" => true),
			array("name" => "googleplus", "icon" => "la la-google-plus", "path" => "", "status" => true),
			array("name" => "blogger", "icon" => "la la-rss-square", "path" => "", "status" => true),
		);
		add_option('dashboard_social', $social);
		
	}
	
	/* Add Css and js in admin page */
	public function register_all_scripts(){
		wp_enqueue_style( 'dashboard-ui-admin', UDASHBOARD_DIR_URL . 'css/admin.css' );
		wp_enqueue_style( 'dashboard-line-awesome', UDASHBOARD_DIR_URL . 'line-awesome/css/line-awesome.min.css' );
		wp_enqueue_script( 'jquery-ui-sortable' ); 
		wp_enqueue_script( 'dashboard-admin-js', UDASHBOARD_DIR_URL . 'js/admin.js', array ( 'jquery' ), 1.1, true);
		
	}
	
	/**
  * Redirect users from the normal dashboard to your custom dashboard
  */
	final public function current_screen( $screen ) { 
    if( 'dashboard' == $screen->id && $screen->post_type == '') {
      wp_safe_redirect( admin_url('admin.php?page=dashboard-dashboard') );
      exit;
    }
	}
	
	final public function admin_menu() {
    /**
    * Adds a custom page to WordPress
    */
    add_menu_page( $this->title, '', $this->capability, 'dashboard-dashboard', array( $this, 'page_content' ) );
    /**
    * Remove the custom page from the admin menu
    */
    remove_menu_page('dashboard-dashboard');
    /**
    * Make dashboard menu item the active item
    */
    global $parent_file, $submenu_file;
    $parent_file = 'index.php';
    $submenu_file = 'index.php';
    /**
    * Rename the dashboard menu item
    */
    global $menu;
    $menu[2][0] = $this->title;
    /**
    * Rename the dashboard submenu item
    */
    global $submenu;
    $submenu['index.php'][0][0] = $this->title;
		
		add_menu_page('Dashboard Options', 'Dashboard Options', 'manage_options', 'dashboard-social', array($this,'dashboard_social') );
    add_submenu_page('dashboard-social', 'Social Media', 'Social Media', 'manage_options', 'dashboard-social' ); 
		add_submenu_page('dashboard-social', 'Dashboard Menus', 'Dashboard Menus', 'manage_options', 'dashboard-dashboard-menus', array($this, 'dashboard_dashboard_menu') );
    //add_submenu_page('dashboard-social', 'Ajax', 'Ajax', 'manage_options', 'dashboard-ajax' );
		
  }
	
	/* Admin Menu */
	public function dashboard_social(){
		require_once UDASHBOARD_DIR_PATH."/templates/social.php";  
	}
	/* Admin Menu */
	public function dashboard_dashboard_menu(){
		require_once UDASHBOARD_DIR_PATH."/templates/dashboard_dashboard_menu.php";  
	}
	
	/**
  * Fixes the page title in the browser.  
	*/
  final public function admin_title( $admin_title, $title ) {
    global $pagenow;
    if( 'admin.php' == $pagenow && isset( $_GET['page'] ) && 'dashboard-dashboard' == $_GET['page'] ) {
      $admin_title = $this->title . $admin_title;
    }
    return $admin_title;
  }
	
	function page_content() {
		require_once UDASHBOARD_DIR_PATH."/templates/dashboard.php";  
  } 
	
	// adding class to admin menu 
	public static function wpse_60168_custom_menu_class(){
    global $menu; 
    if($menu){
      	foreach( $menu as $key => $value ) 
    {
			if( 'edit.php?post_type=dashboard' == $value[2] )
				$menu[$key][4] .= " d-none";
		}
	}
    }
	
	
}
$loader = new UDASHBOARD_Admin();
 