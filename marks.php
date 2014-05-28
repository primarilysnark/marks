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

// Co-op Day setup (e.g. Monday, Tuesday)
require_once('modules/coop_day_setup.php');

// Co-op Day setup (e.g. Monday, Tuesday)
require_once('modules/school_year_setup.php');

// Co-op Day setup (e.g. Monday, Tuesday)
require_once('modules/stage_setup.php');

// Class setup (e.g. Formal Logic, Rhetoric Science, etc.)
require_once('modules/class_setup.php');