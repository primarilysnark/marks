<?php

/* ----------------------------------------
     SETUP SCHOOL YEAR CUSTOM POST TYPE
---------------------------------------- */

add_action('init', 'marks_school_year_register');
add_filter('manage_edit-marks_school_year_columns', 'marks_school_year_edit_columns');

function marks_school_year_register() {

	$args = array(
		'labels' => array(
            'name' => _x('School Years', 'post type general name'),
            'singular_name' => _x('School Year', 'post type singular name'),
            'add_new' => _x('Add New', 'marks_school_year'),
            'add_new_item' => __('Add New School Year'),
            'edit_item' => __('Edit School Year'),
            'new_item' => __('New School Year'),
            'view_item' => __('View School Years'),
            'search_items' => __('Search School Years'),
            'not_found' =>  __('Nothing found'),
            'not_found_in_trash' => __('Nothing found in Trash'),
            'parent_item_colon' => ''
        ),
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title')
	);

	register_post_type( 'marks_school_year' , $args );
}

function marks_school_year_edit_columns($columns) {
    return array(
        'cb' => '<input type="checkbox" />',
        'title' => 'School Year'
    );
}