<?php

/*
Plugin Name: Marks
Version: 0.1
Plugin URI: http://www.0x0259.com/marks
Author: Joshua Starkey
Author URI: http://www.0x0259.com
Description: A complete grading program built for HIS Ministry Co-op

Compatible with WordPress 3.2+.

*/

// Database setup
require_once('modules/database_setup.php');

// Post type setup (e.g. classes, sessions, etc.)
require_once('modules/custom_post_types.php');

// Plugin page setup for testing
require_once('modules/plugin_page_setup.php');