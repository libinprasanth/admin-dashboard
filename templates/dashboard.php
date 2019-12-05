<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
global $post;
$admin_path = get_current_blog_id();
$dashboard_menus = get_option('dashboard_dashboard_menus');
// Get all dashboard content  
$content = "<div class='row'>";
foreach($dashboard_menus as $menu){
	$name = $menu['name'];
	$icon = $menu['icon'];
	$link = get_admin_url($admin_path, $menu['path']);
	$content .= "<div class='col'><div class='dashboard-boxes'><a href='$link'><div class='dashboard-boxes-inner'><i class='$icon'></i><h5>$name</h5></div></a></div></div>";
} 
$content .= "</div>";

echo <<<HTML
<div class="wrp-dashboard">
<div class="dashboard-box">
  <div class="brand-card-header-container">
    <h4>{$this->title}</h4>
  </div>
  <div class="table-wrp-inner"> 
    {$content}
  </div>
</div>
</div>
HTML;

?>