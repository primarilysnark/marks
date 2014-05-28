<?php

// Register custom post types
add_action('init', 'marks_register');

function marks_register() {

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
    
	$args = array(
		'labels' => array(
            'name' => _x('Classes', 'post type general name'),
            'singular_name' => _x('Class', 'post type singular name'),
            'add_new' => _x('Add New', 'marks_class'),
            'add_new_item' => __('Add New Class'),
            'edit_item' => __('Edit Class'),
            'new_item' => __('New Class'),
            'view_item' => __('View Class'),
            'search_items' => __('Search Classes'),
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

	register_post_type( 'marks_class' , $args );

	$args = array(
		'labels' => array(
            'name' => _x('Sessions', 'post type general name'),
            'singular_name' => _x('Session', 'post type singular name'),
            'add_new' => _x('Add New', 'marks_session'),
            'add_new_item' => __('Add New Session'),
            'edit_item' => __('Edit Session'),
            'new_item' => __('New Session'),
            'view_item' => __('View Session'),
            'search_items' => __('Search Sessions'),
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

	register_post_type( 'marks_session' , $args );
}

// Setup meta boxes for custom post types
add_action('add_meta_boxes', 'marks_add_custom_meta_boxes');

function marks_add_custom_meta_boxes() {
    add_meta_box('marks_class_settings-meta', 'Class Settings', 'marks_class_settings_meta', 'marks_class', 'normal', 'low');
    add_meta_box('marks_session-meta', 'Session Details', 'marks_session_meta', 'marks_session', 'normal', 'low');
    add_meta_box('marks_session_students-meta', 'Marks', 'marks_session_students_meta', 'marks_session', 'normal', 'low');
}

function marks_class_settings_meta() {
    global $post;
    $post_saved = $post;
    $custom_post = get_post_custom($post->ID);
    $coop_day = $custom_post['coop_day'][0];
    $stage = $custom_post['stage'][0];
    
    $coop_day_list = new WP_Query(array( 'post_type' => 'marks_coop_day', 'showposts' => -1, 'order' => 'ASC' ));
    $stage_list = new WP_Query(array( 'post_type' => 'marks_stage', 'showposts' => -1, 'order' => 'ASC' ));
    ?>
    <table>
        <tr>
            <td><b><label for="marks_coop_day">Co-op Day:</label></b></td>
            <td>
                <?php
                while ($coop_day_list->have_posts()) {
                    $coop_day_list->the_post();
                    ?>
                    <input name="marks_coop_day" type="radio" id="marks_coop_day_<?php the_title(); ?>" value="<?php the_title(); ?>" <?php if ($coop_day == get_the_title()) { ?> checked="checked" <?php } ?>><label for="marks_coop_day_<?php the_title(); ?>"><?php the_title(); ?></label> 
                    <?php
                }
                ?>
            </td>
        </tr>
        <tr>
            <td><b><label for="marks_stage">Stage:</label></b></td>
            <td>
                <select id="marks_stage" name="marks_stage">
                    <option></option>
                    <?php
                    while ($stage_list->have_posts()) {
                        $stage_list->the_post();
                        ?>
                        <option value="<?php the_title(); ?>" <?php if ($stage == get_the_title()) { ?> selected <?php } ?>><?php the_title(); ?></option>
                        <?php
                    }
                    ?>
                </select>
            </td>
        </tr>
    </table>
    <?
    $post = $post_saved;
}

function marks_session_meta() {
    global $post;
    $post_copy = $post;
    $custom_post = get_post_custom($post->ID);
    $school_year = $custom_post['school_year'][0];
    $class = $custom_post['class'][0];
    
    $school_year_list = new WP_Query(array( 'post_type' => 'marks_school_year', 'showposts' => -1, 'order' => 'ASC' ));
    $class_list = new WP_Query(array( 'post_type' => 'marks_class', 'showposts' => -1, 'order' => 'ASC' ));
    ?>
    <table>
        <tr>
            <td><b><label for="marks_school_year">School Year:</label></b></td>
            <td>
                <select id="marks_school_year" name="marks_school_year">
                    <option></option>
                    <?php
                    while ($school_year_list->have_posts()) {
                        $school_year_list->the_post();
                        ?>
                        <option value="<?php the_title(); ?>" <?php if ($school_year == get_the_title()) { ?> selected <?php } ?>><?php the_title(); ?></option>
                        <?php
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><b><label for="marks_class">Class:</label></b></td>
            <td>
                <select id="marks_class" name="marks_class">
                    <option></option>
                    <?php
                    while ($class_list->have_posts()) {
                        $class_list->the_post();
                        ?>
                        <option value="<?php the_title(); ?>" <?php if ($class == get_the_title()) { ?> selected <?php } ?>><?php the_title(); ?></option>
                        <?php
                    }
                    ?>
                </select>
            </td>
        </tr>
    </table>
    <?php
    $post = $post_copy;
}

function marks_session_students_meta() {
    global $post;
    $custom_post = get_post_custom($post->ID);
    $student_list = $custom_post['student_list'][0];
    $marks_opt_in = $custom_post['marks_opt_in'][0];

    ?>
        <label for="marks_marks_opt_in">Enable Marks:</label>
        <input type="checkbox" id="marks_marks_opt_in" name="marks_marks_opt_in" <?php if ($marks_opt_in) { ?> checked="checked" <?php } ?> /><br /><br />    
    <?php
    
    $user_list = get_users('orderby=nicename&order=ASC');
    
    foreach ($user_list as $user) {
        ?>
            <input type="checkbox" class="mark_student_item" id="marks_student_<?php echo $user->ID; ?>" value="<?php echo $user->ID; ?>" name="marks_student_<?php echo $user->ID; ?>" /><label for="marks_student_<?php echo $user->ID; ?>"><?php echo $user->display_name; ?></label>
        <?php
    }
    ?>
        <input type="hidden" name="marks_student_list" id="marks_student_list" value="<?php echo $student_list; ?>" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script type="text/javascript">
            var studentList = $('.mark_student_item'),
                studentListInput = $('#marks_student_list'),
                listOfStudentsInit = studentListInput.val().split(',');
            
            studentList.each(function () {
                if (listOfStudentsInit.indexOf($(this).val()) != -1) {
                    $(this).prop('checked', 'checked');
                }
            });
            
            studentList.on('click', function () {
                var studentIDList = '';
                studentList.filter(':checked').each(function () {
                    studentIDList += ($(this).val() + ',');
                });
                studentListInput.val(studentIDList.substring(0, studentIDList.length - 1));
            });
        </script>
    <?php
}

// Setup custom save actions for post types
add_action('save_post', 'marks_class_save_details');
add_action('save_post', 'marks_session_save_details');

function marks_class_save_details() {
    global $post;
    global $wpdb;
    $slug = 'marks_class';
    
    if ($slug != $_POST['post_type']) {
        return;
    }
    
    update_post_meta($post->ID, 'coop_day', $_POST['marks_coop_day']);
    update_post_meta($post->ID, 'stage', $_POST['marks_stage']);
}

function marks_session_save_details() {
    global $post;
    global $wpdb;
    $slug = 'marks_session';
    
    if ($slug != $_POST['post_type']) {
        return;
    }
    
    $marks_student_list = explode(',', $_POST['marks_student_list']);
    
    // Get list of students from database
    $db_student_list = $wpdb->get_col($wpdb->prepare("SELECT userId FROM ".$wpdb->prefix."marks_user_course_intersect WHERE courseId = %d", $post->ID));
    
    // Deactive students who are no longer selected
    foreach($db_student_list as $db_student) {
        if (!in_array($db_student, $marks_student_list)) {
            $wpdb->update(
                $wpdb->prefix . 'marks_user_course_intersect',
                array(
                    'status' => 'I'
                ),
                array('userId' => $db_student)
            );
        }
    }
    
    if ($marks_student_list[0] != "") {
        // Loop through selected students
        foreach($marks_student_list as $marks_student) {

            // Check if student exists
            $student_exist_check = $wpdb->get_col( $wpdb->prepare(
                "
                SELECT userId
                FROM ".$wpdb->prefix."marks_user_course_intersect
                WHERE userId = %d",
                $marks_student
            ));

            // Check if student set to active
            $student_active_check = $wpdb->get_col( $wpdb->prepare(
                "
                SELECT userId
                FROM ".$wpdb->prefix."marks_user_course_intersect
                WHERE userId = %d
                AND status = %s",
                $marks_student,
                'I'
            ));

            // If student does not exist, add student
            if (!$student_exist_check) {
                $wpdb->insert(
                    $wpdb->prefix . 'marks_user_course_intersect',
                    array(
                        'userId' => $marks_student,
                        'courseId' => $post->ID,
                        'courseAdmin' => 'N',
                        'status' => 'A',
                        'createdDate' => current_time('mysql')
                    )
                );
            }
            // If student not active, make student active
            else if ($student_active_check) {
                $student_status = $wpdb->update(
                    $wpdb->prefix . 'marks_user_course_intersect',
                    array(
                        'status' => 'A'
                    ),
                    array('userId' => $marks_student)
                );
            }
        }
    }
    
    update_post_meta($post->ID, 'school_year', $_POST['marks_school_year']);
    update_post_meta($post->ID, 'class', $_POST['marks_class']);
    update_post_meta($post->ID, 'marks_opt_in', $_POST['marks_marks_opt_in']);
    update_post_meta($post->ID, 'student_list', $_POST['marks_student_list']);
}

// Setup MARKS_SCHOOL columns
add_filter('manage_edit-marks_school_year_columns', 'marks_school_year_edit_columns');

function marks_school_year_edit_columns($columns) {
    return array(
        'cb' => '<input type="checkbox" />',
        'title' => 'School Year'
    );
}

// Setup MARKS_COOP_DAY columns
add_action('manage_posts_custom_column', 'marks_coop_day_custom_columns');
add_filter('manage_edit-marks_coop_day_columns', 'marks_coop_day_edit_columns');

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
            $class_count = new WP_Query(array( 'post_type' => 'marks_class', 'showposts' => -1, 'meta_query' => array(array('key' => 'coop_day', 'value' => $post->post_title ))));
            echo $class_count->post_count;
            break;
    }

    $post = $post_save;
}

// Setup MARKS_STAGE columns
add_filter('manage_edit-marks_stage_columns', 'marks_stage_edit_columns');

function marks_stage_edit_columns($columns) {
    return array(
        'cb' => '<input type="checkbox" />',
        'title' => 'Stage'
    );
}

// Setup MARKS_CLASS columns
add_action('manage_posts_custom_column', 'marks_class_custom_columns');
add_filter('manage_edit-marks_class_columns', 'marks_class_edit_columns');

function marks_class_edit_columns($columns) {
    return array(
        'cb' => '<input type="checkbox" />',
        'title' => 'Class Name',
        'coop_day' => 'Co-op Day',
        'stage' => 'Class Stage'
    );
}

function marks_class_custom_columns($column) {
    global $post;
    $custom_post = get_post_custom($post->ID);
    
    switch ($column) {
        case 'coop_day':
            echo $custom_post['coop_day'][0];
            break;
        case 'stage':
            echo $custom_post['stage'][0];
            break;
    }
}

// Setup MARKS_SESSION columns
add_action('manage_posts_custom_column', 'marks_session_custom_columns');
add_filter('manage_edit-marks_session_columns', 'marks_session_edit_columns');

function marks_session_edit_columns($columns) {
    return array(
        'cb' => '<input type="checkbox" />',
        'title' => 'Session Name',
        'class' => 'Class',
        'school_year' => 'School Year',
        'marks_opt_in' => 'Uses Marks',
        'student_count' => 'Total Students'
    );
}

function marks_session_custom_columns($column) {
    global $post;
    $custom_post = get_post_custom($post->ID);
    
    switch ($column) {
        case 'class':
            echo $custom_post['class'][0];
            break;
        case 'school_year':
            echo $custom_post['school_year'][0];
            break;
        case 'marks_opt_in':
            if ($custom_post['marks_opt_in'][0]) {
                echo 'Yes';
            } else {
                echo 'No';
            }
            break;
        case 'student_count':
            $student_list = split(',', $custom_post['student_list'][0]);
            echo ($student_list[0] == "") ? '0' : count($student_list);
            break;
    }
}