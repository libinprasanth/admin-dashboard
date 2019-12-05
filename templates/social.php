<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
if ( isset($_POST['dashboard-settings-social']) ){
	if (! isset( $_POST['dashboard-settings-social'] ) || !wp_verify_nonce( $_POST['dashboard-settings-social'], 'dashboard-settings-social' )) {
	  print 'Sorry, your nonce did not verify.';
    die();
  }
	
  $social = get_option('dashboard_social');
	foreach($social as $key => $s){
		$social[$key]['path'] = esc_url_raw($_REQUEST[$s['name']]);
	}
	update_option('dashboard_social', $social);
}	
?>
<div class="wrp-dashboard" >
  
	<div class="dashboard-box">
	<form method="post" action="?page=dashboard-social">
	  <div class="table-wrp">
		  <div class="brand-card-header-container">
		    <h4><?= __('Manage Social Media', 'admin-dashboard'); ?></h4>
	  	</div>
		  <div class="table-wrp-inner">
			  <p><?= __("Get all social media link by calling the function <code>get_option('dashboard_social')</code>", 'admin-dashboard'); ?></p>
			  <?php $dashboard_social = get_option('dashboard_social'); ?>
				<table>
				  <?php foreach($dashboard_social as $social){ ?>
				  <tr class="user-email-wrap">
					  <th><label for="<?= esc_attr($social['name']); ?>"><?= esc_attr($social['name']); ?></label>  <i class="<?= esc_attr($social['icon']); ?>"></i></th>
					  <td><input type="text" name="<?= esc_attr($social['name']); ?>" id="<?= esc_attr($social['name']); ?>" value="<?= esc_attr($social['path']); ?>" class="form-control" >
						</td>
				  </tr> 
					<?php } ?>
				</table>
				
		  </div> 
			<div class="brand-card-footer-container">
			<?php
			  wp_nonce_field( 'dashboard-settings-social', 'dashboard-settings-social' );
			  submit_button();
		  ?>
			</div>
		</div>	
		
	</form>
	</div>
</div>