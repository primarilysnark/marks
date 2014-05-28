<?php

/* ----------------------------------------
      SETUP CO-OP DAY CUSTOM POST TYPE
---------------------------------------- */

add_action('init', 'marks_coop_day_register');
add_action('manage_posts_custom_column', 'marks_coop_day_custom_columns');
add_filter('manage_edit-marks_coop_day_columns', 'marks_coop_day_edit_columns');

function marks_coop_day_register() {

	$args = array(
		'labels' => array(
            'name' => _x('Co-op Days', 'post type general name'),
            'singular_name' => _x('Co-op Day', 'post type singular name'),
            'add_new' => _x('Add New', 'marks_coop_day'),
            'add_new_item' => __('Add New Co-op Day'),
            'edit_item' => __('Edit Co-op Day'),
            'new_item' => __('New Co-op Day'),
            'view_item' => __('View Co-op Days'),
            'search_items' => __('Search Co-op Days'),
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

	register_post_type( 'marks_coop_day' , $args );
}

function marks_coop_day_edit_columns($columns) {
    return array(
        'cb' => '<input type="checkbox" />',
        'title' => 'Co-op Day',
        'coop_classes' => 'Class Count'
    );
}

function marks_coop_day_custom_columns($column) {
    global $post;
    $post_save = $post;
    //$custom_post = get_post_custom($post->ID);

    switch ($column) {
        case 'coop_classes':
            $class_count = new WP_Query(array( 'post_type' => 'marks_class', 'showposts' => -1, 'meta_query' => array('key' => 'coop_day', 'value' => $post->post_title )));
            echo $class_count->post_count;
            break;
    }

    $post = $post_save;
}