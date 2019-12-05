<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
$updated = false; 
	
if ( isset($_POST['dashboard-settings-item']) ){ 
  if (! isset( $_POST['dashboard-settings-item'] ) || !wp_verify_nonce( $_POST['dashboard-settings-item'], 'dashboard-settings-item' )) {
	  print 'Sorry, your nonce did not verify.';
    die();
  }
  $dashboard = wp_unslash($_REQUEST['dashboard']); 
	update_option('dashboard_dashboard_menus', $dashboard);
	$updated = true;
}	
function UDASHBOARD_admin_menu_item_url( $menu_item_file, $submenu_as_parent = true ) {
	global $menu, $submenu, $self, $parent_file, $submenu_file, $plugin_page, $typenow;
 
	$admin_is_parent = false;
	$item = '';
	$submenu_item = '';
	$url = '';
 
	// 1. Check if top-level menu item
	foreach( $menu as $key => $menu_item ) {
		if ( array_keys( $menu_item, $menu_item_file, true ) ) {
			$item = $menu[ $key ];
		}
 
		if ( $submenu_as_parent && ! empty( $submenu_item ) ) {
			$menu_hook = get_plugin_page_hook( $submenu_item[2], $item[2] );
			$menu_file = $submenu_item[2];
 
			if ( false !== ( $pos = strpos( $menu_file, '?' ) ) )
				$menu_file = substr( $menu_file, 0, $pos );
			if ( ! empty( $menu_hook ) || ( ( 'index.php' != $submenu_item[2] ) && file_exists( WP_PLUGIN_DIR . "/$menu_file" ) && ! file_exists( ABSPATH . "/wp-admin/$menu_file" ) ) ) {
				$admin_is_parent = true;
				$url = 'admin.php?page=' . $submenu_item[2];
			} else {
				$url = $submenu_item[2];
			}
		}
 
		elseif ( ! empty( $item[2] ) && current_user_can( $item[1] ) ) {
			$menu_hook = get_plugin_page_hook( $item[2], 'admin.php' );
			$menu_file = $item[2];
 
			if ( false !== ( $pos = strpos( $menu_file, '?' ) ) )
				$menu_file = substr( $menu_file, 0, $pos );
			if ( ! empty( $menu_hook ) || ( ( 'index.php' != $item[2] ) && file_exists( WP_PLUGIN_DIR . "/$menu_file" ) && ! file_exists( ABSPATH . "/wp-admin/$menu_file" ) ) ) {
				$admin_is_parent = true;
				$url = 'admin.php?page=' . $item[2];
			} else {
				$url = $item[2];
			}
		}
	}
 
	// 2. Check if sub-level menu item
	if ( ! $item ) {
		$sub_item = '';
		foreach( $submenu as $top_file => $submenu_items ) {
 
			// Reindex $submenu_items
			$submenu_items = array_values( $submenu_items );
 
			foreach( $submenu_items as $key => $submenu_item ) {
				if ( array_keys( $submenu_item, $menu_item_file ) ) {
					$sub_item = $submenu_items[ $key ];
					break;
				}
			}					
 
			if ( ! empty( $sub_item ) )
				break;
		}
 
		// Get top-level parent item
		foreach( $menu as $key => $menu_item ) {
			if ( array_keys( $menu_item, $top_file, true ) ) {
				$item = $menu[ $key ];
				break;
			}
		}
 
		// If the $menu_item_file parameter doesn't match any menu item, return false
		if ( ! $sub_item )
			return false;
 
		// Get URL
		$menu_file = $item[2];
 
		if ( false !== ( $pos = strpos( $menu_file, '?' ) ) )
			$menu_file = substr( $menu_file, 0, $pos );
 
		// Handle current for post_type=post|page|foo pages, which won't match $self.
		$self_type = ! empty( $typenow ) ? $self . '?post_type=' . $typenow : 'nothing';
		$menu_hook = get_plugin_page_hook( $sub_item[2], $item[2] );
 
		$sub_file = $sub_item[2];
		if ( false !== ( $pos = strpos( $sub_file, '?' ) ) )
			$sub_file = substr($sub_file, 0, $pos);
 
		if ( ! empty( $menu_hook ) || ( ( 'index.php' != $sub_item[2] ) && file_exists( WP_PLUGIN_DIR . "/$sub_file" ) && ! file_exists( ABSPATH . "/wp-admin/$sub_file" ) ) ) {
			// If admin.php is the current page or if the parent exists as a file in the plugins or admin dir
			if ( ( ! $admin_is_parent && file_exists( WP_PLUGIN_DIR . "/$menu_file" ) && ! is_dir( WP_PLUGIN_DIR . "/{$item[2]}" ) ) || file_exists( $menu_file ) )
				$url = add_query_arg( array( 'page' => $sub_item[2] ), $item[2] );
			else
				$url = add_query_arg( array( 'page' => $sub_item[2] ), 'admin.php' );
		} else {
			$url = $sub_item[2];
		}
	}
 
	return esc_url( $url );
 
} 
$menus = array();
global $submenu;
foreach($submenu as $key => $m){ 
  $i = 0; 
	foreach($m as $su){
		$parent = ($i == 0)?'':'---';
		array_push($menus, array("name"=> $parent.$su[0], "path"=> UDASHBOARD_admin_menu_item_url($su[2])));
		$i = $i + 1;
	}
} 

?>
<script  type='text/javascript'>
 window.menus = <?= json_encode($menus, true); ?>;
</script>
<div class="wrp-dashboard" >
  
  <div class="dashboard-box">
	<form method="post" action="?page=dashboard-dashboard-menus">
	  <div class="table-wrp">
		  <div class="brand-card-header-container">
		    <h4><?= __('Manage Dashboard Menus', 'admin-dashboard'); ?></h4> 
	  	</div>
		  <div class="table-wrp-inner"> 
        <?php if($updated){ ?>
				<div class="updated notice">
					<p><?= __('Dashboard Menus UpdatedMenus', 'admin-dashboard'); ?></p>
				</div>
				<?php } ?>
			  <?php $dashboard = get_option('dashboard_dashboard_menus'); $i = 0; ?>
       
       <div class="row sortableCol">
			   <div class='col' >
				   <a href="javascript:;" class="add-new-item">
					 <div class='dashboard-boxes add-new'>
					   <div class='dashboard-boxes-inner'>
						   <i class="la la-plus"></i>
						</div>
					 </div>
					 </a>
				 </div>
       <?php $j = 0; foreach($dashboard as $key => $item){ ?>
				 <div class='col portlet' id="icon-holder--<?= $j; ?>">
					 <div class='dashboard-boxes'  data-icon="<?= $item['icon']; ?>" data-name="<?= $item['name']; ?>" data-path="<?= $item['path']; ?>" data-id="#icon-holder--<?= $j; ?>">
             <a href="javascript:;" class="removeDashboard"><i class="la la-trash"></i></a>
		         <a href="javascript:;" class="editDashboard"><i class="la la-pencil"></i></a>
						 <div class='dashboard-boxes-inner'>
							<i class='icon-placeholder <?= (empty($item['icon'])?'la la-gear':$item['icon']); ?>'></i>
							<input type="text" class="icon-box" name="dashboard[<?= $j; ?>][icon]" value="<?= (empty($item['icon'])?'la la-gear':$item['icon']); ?>" style="display: none;" />
							<h5>
								<span class="name-placeholder" ><?= $item['name']; ?></span>
								<input type="text" placeholder="enter title" class="name-box" name="dashboard[<?= $j; ?>][name]" value="<?= esc_attr($item['name']); ?>" style="display: none;" />
							</h5>
							<h6 class="" >
                <span class="path-placeholder" ><?= $item['path']; ?></span>
								<select class="path-box" name="dashboard[<?= $j; ?>][path]" style="display: none;">
								  <?php 
									  foreach($menus as $m) { 
										$selected = ($m['path'] == $item['path'])?'selected':''; 
									?>
									  <option value="<?= esc_attr($m['path']); ?>" <?= $selected; ?>><?= $m['name']; ?></option>
									<?php } ?>
								</select> 
						  </h6>
						</div>
					 </div>
				 </div>
       <?php $j = $j+1;} ?>
       </div>
 
				
		  </div> 
			<div class="brand-card-footer-container">
			<?php
			  wp_nonce_field( 'dashboard-settings-item', 'dashboard-settings-item' );
			  submit_button();
		  ?>
			</div>
		</div>	
   
    <div class="ui-dashboard-modal-overlay"></div>
    <div class="ui-dashboard-modal">
		 <div class="searhIcons">
		   <input type="text" name="" placeholder="Search Icon" />
		 </div>
     <div class="icon-container-wp">
		  <div class="dsj">
			  <?php global $line_icons_d; foreach($line_icons_d as $icon){ ?>
				  <div class="icon-box-in">
					  <input type="radio" name="icon-chooser" value="<?= esc_attr($icon); ?>">
					  <div class="icon-box-in-pl">
					    <i class="<?= $icon; ?>"></i> 
						</div>
					</div>
				<?php } ?>
			</div>
		 </div>
    </div>
		
	</form>
	</div>
</div>