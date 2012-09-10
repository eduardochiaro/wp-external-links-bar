<?php
/**
 * @package wp-external-links-bar
 * @author Eduardo Chiaro
 * @version 1.1.1
 */
/*
Plugin Name: WP External Links Bar
Plugin URI: http://www.thedeveloperinside.com/resources/wp-external-links-bar/
Description: Maintain your external links (in posts and comments) in your site. Create a Link Bar like facebook, digg and google. With share link.
Author:Eduardo Chiaro
Version: 1.1.1
Author URI: http://www.eduardochiaro.it
*/

set_include_path( dirname(__FILE__) ."/". get_include_path() );   

require_once('wp-elb.admin.php');
require_once('wp-elb.class.php');


$class = new ExternalLinks();

$wpelb_options = $class->config;

function elb_searchLinks($content){
	global $class;
	return $class->searchLinks($content);
}

function elb_onlyLinks($content){
	global $class;
	return $class->onlyLinks($content);
}

function elb_makeBar(){
	global $class;
	return $class->makeBar();
}

function elb_admin_menu() {
	if ( function_exists('add_submenu_page') ){
		add_submenu_page('plugins.php', __('External Links Bar Configuration'), __('External Links Bar Configuration'), 10, 'wp-elb-config', 'elb_config');
	}
	if ( isset( $_GET['page'] ) && $_GET['page'] == "wp-elb-config" ){
		add_action('admin_head', 'elb_config_head');
	}

}
	
if($class->isValid()) {
	add_action('template_redirect', 'elb_makeBar',1);
} 

if($wpelb_options['posts_active'] && !is_admin()){
	add_filter('the_content', 'elb_searchLinks');
}

if($wpelb_options['comments_active'] && !is_admin()){
	add_filter('comment_text', 'elb_searchLinks');
}

if($wpelb_options['author_link_active'] && !is_admin()){
	add_filter('get_comment_author_url', 'elb_onlyLinks');
}

function elb_plugin_actions( $links, $file ){
	$this_plugin = plugin_basename(__FILE__);
	
	if ( $file == $this_plugin ){
		$settings_link = '<a href="plugins.php?page=wp-elb-config">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}
add_action('plugin_action_links','elb_plugin_actions',10, 2);
add_action('admin_menu', 'elb_admin_menu');


?>