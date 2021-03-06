<?php
/*
Plugin Name: eNevada Resource Map
URI: http://enevada.org
Description: This is a custom WordPress plugin for handling the asset map on the eNevada website.
Version: 1.0.0
Author: Zachary Draper
Author URI: http://zacharydraper.com
License: Copyright 2017 Zachary Draper and Entrepreneurship Nevada. All rights reserved.
*/

// no direct access
defined('ABSPATH') or die('No direct access');

// establish the database version number
global $enrm_db_version;
$enrm_db_version = '1.0';

/**
 * Function to setup database tables
 */
function enrm_database_setup(){
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();

	// include the WordPress functions for creating database tables
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	// build the resources table query
	$sql = "CREATE TABLE {$wpdb->prefix}en_resources(
		id int(11) NOT NULL AUTO_INCREMENT,
		status varchar(20) NOT NULL DEFAULT 'publish',
		name varchar(250) NOT NULL,
		org int(11) NOT NULL,
		contact_name varchar(75) NOT NULL,
		description varchar(500) NOT NULL,
		email varchar(100) NOT NULL,
		slug varchar(150) NOT NULL,
		telephone varchar(14) NOT NULL,
		website varchar(100) NOT NULL,
		created datetime NOT NULL,
		created_by int(11) NOT NULL,
		modified datetime,
		modified_by int(11),
		PRIMARY KEY (id),
		UNIQUE unique_index(name)
	) $charset_collate;";
	
	// create the resources table
	dbDelta($sql);

	// build the categories table query
	$sql = "CREATE TABLE {$wpdb->prefix}en_categories(
		id int(11) NOT NULL AUTO_INCREMENT,
		status varchar(20) NOT NULL DEFAULT 'publish',
		name varchar(50) NOT NULL,
		description varchar(250) NOT NULL,
		created datetime NOT NULL,
		created_by int(11) NOT NULL,
		modified datetime,
		modified_by int(11),
		PRIMARY KEY (id),
		UNIQUE unique_index(name)
	) $charset_collate;";
	
	// create the categories table
	dbDelta($sql);

	// build the resource categories table query
	$sql = "CREATE TABLE {$wpdb->prefix}en_resource_categories(
		id int(11) NOT NULL AUTO_INCREMENT,
		resource int(11) NOT NULL,
		category int(11) NOT NULL,
		PRIMARY KEY (id),
		UNIQUE unique_index(resource, category)
	) $charset_collate;";
	
	// create the resource categories table
	dbDelta($sql);

	// build the vendor orgs table
	$sql = "CREATE TABLE {$wpdb->prefix}en_orgs(
		id int(11) NOT NULL AUTO_INCREMENT,
		status varchar(20) NOT NULL DEFAULT 'publish',
		name varchar(250) NOT NULL,
		email varchar(100) NOT NULL,
		fname varchar(50) NOT NULL,
		lname varchar(50) NOT NULL,
		description varchar(500) NOT NULL,
		created datetime NOT NULL,
		created_by int(11) NOT NULL,
		modified datetime,
		modified_by int(11),
		PRIMARY KEY (id),
		UNIQUE unique_index(name)
	) $charset_collate;";
	
	// create the vendor orgs table
	dbDelta($sql);

	// store the current database verson
	add_option('enrm_db_version', $enrm_db_version);
}

// call the install function on activation
register_activation_hook(__FILE__, 'enrm_database_setup');

/**
 * Function to build the admin menus
 */
function enrm_admin_menu_setup(){
	add_menu_page('eNevada Resources', 'Resources', 'manage_options', 'enrm', 'enrm_resources_screen', 'dashicons-book-alt');
	add_submenu_page('enrm', 'Categories', 'Categories', 'manage_options', 'enrm-categories', 'enrm_categories_screen');
	add_submenu_page('enrm', 'Organizations', 'Organizations', 'manage_options', 'enrm-orgs', 'enrm_orgs_screen');
}

add_action('admin_menu', 'enrm_admin_menu_setup');

/**
 * Get the WordPress Media Uploader ready
 */
function enrm_enqueue_media_uploader(){
  wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'enrm_enqueue_media_uploader');

/**
 * Function to draw the categories screen
 */
function enrm_categories_screen(){
	include_once plugin_dir_path(dirname(__FILE__)).'enevada-resources/category.php';
}

/**
 * Function to draw the organizations screen
 */
function enrm_orgs_screen(){
	include_once plugin_dir_path(dirname(__FILE__)).'enevada-resources/org.php';
}

/**
 * Function to draw the resources screen
 */
function enrm_resources_screen(){
	include_once plugin_dir_path(dirname(__FILE__)).'enevada-resources/resource.php';
}

/** ======= Virtual Page Support ====== **/

// only load if this is not the admin panel
if(!is_admin()){
	// inlcude the class
	include_once 'inc/class-virtualthemedpage-bc.php';

	// instantiate the class, specify the template file to use
	$vp = new Virtual_Themed_Pages_BC();

	// add the re-write rule
	$vp->add('#/asset-map(\.html)?$#i', 'render_resources_page');
	$vp->add('#/asset-map/#i', 'render_individual_resource_page');
}

/**
 * This page renders the virtual page for a single resource
 *
 * @param obj The virtual page class object
 * @param string The URL that is loading
 */
function render_individual_resource_page($v, $url){
	global $wpdb;

	// specify the template file to use
	$v->template = 'resource';

	// include the helper
	require_once('helper.php');

	// create a resource object
	$resource = new en_Resource();

	// determine the slug, load it into the object
	$uri = $_SERVER['REQUEST_URI'];
	$slug = substr($uri, 11);
	$end = strrpos($slug, '/');
	$end = ($end ? $end : strrpos($slug, '.'));
	if($end){
		$slug = substr($slug, 0, $end);
	}
	$resource->slug = $slug;

	// load the resource data from the database
	$resource->load();

	// check that such a resource exists
	$v->body = '';
	$v->title = 'No resource Found';
	if($resource->name != '' && $resource->status == 'publish'){
		// load the name
		$v->title = $resource->name;
	}

	$_SESSION['en_resource'] = $resource;
}

/**
 * This page renders the virtual page for a single resource
 *
 * @param obj The virtual page class object
 * @param string The URL that is loading
 */
function render_resources_page($v, $url){
	// include the helper
	require_once('helper.php');

	// specify the template file to use
	$v->body = '';
	$v->template = 'resources';
	$v->title = 'Resources';
}
?>