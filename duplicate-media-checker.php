<?php
/*
Plugin Name: Duplicate Media Checker
Plugin URI: http://mindsharelabs.com/downloads/duplicate-media-checker/
Description: Prevents duplicate files from being uploaded to the Media Library
Version: 0.1
Author: Mindshare Studios, Inc.
Author URI: http://mindsharelabs.com/
*/

/**
 * @copyright Copyright (c) 2012. All rights reserved.
 * @author    Mindshare Studios, Inc.
 *
 * @license   Released under the GPL license http://www.opensource.org/licenses/gpl-license.php
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * **********************************************************************
 *
 */

/* CONSTANTS */
if(!defined('DMC_MIN_WP_VERSION')) {
	define('DMC_MIN_WP_VERSION', '3.1');
}

if(!defined('DMC_PLUGIN_NAME')) {
	define('DMC_PLUGIN_NAME', 'Duplicate Media Checker');
}

if(!defined('DMC_PLUGIN_SLUG')) {
	define('DMC_PLUGIN_SLUG', 'duplicate-media-checker');
}

if(!defined('DMC_DIR_PATH')) {
	define('DMC_DIR_PATH', plugin_dir_path(__FILE__));
}

if(!defined('DMC_DIR_URL')) {
	define('DMC_DIR_URL', plugin_dir_url(__FILE__));
}

// check WordPress version
global $wp_version;
if(version_compare($wp_version, DMC_MIN_WP_VERSION, "<")) {
	exit(DMC_PLUGIN_NAME.' requires WordPress '.DMC_MIN_WP_VERSION.' or newer.');
}

// deny direct access
if(!function_exists('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

if(is_admin()) {
	require_once(DMC_DIR_PATH.'lib/options/options.php'); // include options framework
	require_once(DMC_DIR_PATH.'views/dmc-options.php'); // include options file
	require_once(DMC_DIR_PATH.'includes/Duplicate_Media_Checker.php'); // include options file
}
