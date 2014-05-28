<?php

add_action( 'admin_menu', 'marks_plugin_menu' );

function marks_plugin_menu() {
    add_plugins_page('Marks', 'Marks', 'edit_posts', 'marks_plugin', 'marks_plugin_page');
}

function marks_plugin_page() {
    global $wpdb;
    
    $action = $_POST['action'];
    $assignmentId = $_POST['assignmentId'];
    
    if ($action == 'delete') {
        $wpdb->delete(
            $wpdb->prefix . 'marks_assignments',
            array (
                'ID' => $assignmentId
            )
        );
    }
    else if ($action == 'add') {
        $wpdb->insert(
            $wpdb->prefix . 'marks_assignments',
            array(
                'courseId' => $_POST['courseId'],
                'assignmentName' => $_POST['assignmentName'],
                'unitNumber' => $_POST['unitNumber'],
                'weekNumber' => $_POST['weekNumber'],
                'dueDate' => $_POST['dueDate'],
                'pointsPossible' => $_POST['pointsPossible'],
                'status' => 'A'
            )
        );
    }
    
    if ( !current_user_can( 'edit_posts' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
    
    $marks_courses = new WP_Query(array(
        'post_type' => 'marks_session',
        'showposts' => -1,
        'meta_query' => array(
            array(
                'key' => 'marks_opt_in',
                'value' => 'on'
            )
        )
    ));
    $marks_courses_copy = $marks_courses;

    ?>
    <div class="wrap">
        <h2>Marks</h2>
        <table class="wp-list-table widefat">
            <thead>
                <tr>
                    <th>Class</th>
                    <th>Unit</th>
                    <th>Week</th>
                    <th>Assignment</th>
                    <th>Due Date</th>
                    <th>Points Possible</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($marks_courses->have_posts()) {
                    $marks_courses->the_post();
                    $assignments = $wpdb->get_results(
                        $wpdb->prepare(
                            "
                            SELECT *
                            FROM ".$wpdb->prefix."marks_assignments
                            WHERE courseId = %d
                            ORDER BY unitNumber, weekNumber, assignmentName",
                            $marks_courses->post->ID
                        ), ARRAY_A
                    );
                    $firstRow = true;
                    foreach ($assignments as $assignment)
                    {
                        ?>
                            <form action="" method="post">
                                <input type="hidden" name="action" value="delete" />
                                <input type="hidden" name="assignmentId" value="<?php echo $assignment['id']; ?>" />
                                <tr>
                                    <td><?php if ($firstRow) { the_title(); $firstRow = false; } ?></td>
                                    <td><?php echo $assignment['unitNumber']; ?></td>
                                    <td><?php echo $assignment['weekNumber']; ?></td>
                                    <td><?php echo $assignment['assignmentName']; ?></td>
                                    <td><?php echo $assignment['dueDate']; ?></td>
                                    <td><?php echo $assignment['pointsPossible']; ?></td>
                                    <td><input type="submit" value="Delete" /></td>
                                </tr>
                            </form>
                        <?php
                    }
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Class</th>
                    <th>Unit</th>
                    <th>Week</th>
                    <th>Assignment</th>
                    <th>Due Date</th>
                    <th>Points Possible</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
        <p><b>Add New Assignment:</b></p>
        <form action="" method="post">
            <input type="hidden" name="action" value="add" />
            <table>
                <tr>
                    <td>Course:</td>
                    <td>
                        <select name="courseId">
                            <option></option>
                            <?php
                            while ($marks_courses_copy->have_posts()) {
                                $marks_courses_copy->the_post();
                                ?>
                                    <option value="<?php echo $marks_courses_copy->post->ID; ?>"><?php the_title(); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Assignment Name:</td>
                    <td><input type="text" name="assignmentName" /></td>
                </tr>
                <tr>
                    <td>Unit Number:</td>
                    <td><input type="text" name="unitNumber" /></td>
                </tr>
                <tr>
                    <td>Week Number:</td>
                    <td><input type="text" name="weekNumber" /></td>
                </tr>
                <tr>
                    <td>Due Date:</td>
                    <td><input type="text" name="dueDate" /></td>
                </tr>
                <tr>
                    <td>Points Possible:</td>
                    <td><input type="text" name="pointsPossible" /></td>
                </tr>
                <tr>
                    <th colspan="2"><input type="submit" value="Add Assignment"></th>
                </tr>
            </table>
        </form>
    </div>
    <?php
}