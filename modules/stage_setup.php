<?php

/* ----------------------------------------
        SETUP STAGE CUSTOM POST TYPE
---------------------------------------- */

add_action('init', 'marks_stage_register');
add_filter('manage_edit-marks_stage_columns', 'marks_stage_edit_columns');

function marks_stage_register() {

	$args = array(
		'labels' => array(
            'name' => _x('Stages', 'post type general name'),
            'singular_name' => _x('Stage', 'post type singular name'),
            'add_new' => _x('Add New', 'marks_stage'),
            'add_new_item' => __('Add New Stage'),
            'edit_item' => __('Edit Stage'),
            'new_item' => __('New Stage'),
            'view_item' => __('View Stages'),
            'search_items' => __('Search Stages'),
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

	register_post_type( 'marks_stage' , $args );
}

function marks_stage_edit_columns($columns) {
    return array(
        'cb' => '<input type="checkbox" />',
        'title' => 'Stage'
    );
}