<?php

add_action( 'admin_menu', 'marks_plugin_menu' );

function marks_plugin_menu() {
    add_plugins_page('Marks', 'Marks', 'edit_posts', 'marks_plugin', 'marks_plugin_page');
}

function marks_plugin_page() {
    if ( !current_user_can( 'edit_posts' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
    
    $marks_courses = new WP_Query(array(
        'post_type' => 'marks_class',
        'showposts' => -1,
        'meta_query' => array(
            array(
                'key' => 'marks_opt_in',
                'value' => 'on'
            )
        )
    ));

    ?>
    <div class="wrap">
        <h2>Marks</h2>
        <table class="wp-list-table widefat">
            <thead>
                <tr>
                    <th>Class</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($marks_courses->have_posts()) {
                    $marks_courses->the_post();
                    ?>
                    <tr>
                        <td><?php the_title(); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Class</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php
}