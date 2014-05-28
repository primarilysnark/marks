<?php

add_action( 'admin_menu', 'marks_plugin_menu' );

function marks_plugin_menu() {
    add_plugins_page('Marks', 'Marks', 'edit_posts', 'marks_plugin', 'marks_plugin_page');
}

function marks_plugin_page() {
    global $wpdb;
    
    $action = $_POST['action'];    
    if ($action == 'deleteAssignment') {
        $wpdb->delete(
            $wpdb->prefix . 'marks_assignments',
            array (
                'ID' => $$_POST['assignmentId']
            )
        );
    }
    if ($action == 'deleteGrade') {
        $wpdb->delete(
            $wpdb->prefix . 'marks_user_assignment_intersect',
            array (
                'assignmentId' => $_POST['assignmentId'],
                'userId' => $_POST['userId']
            )
        );
    }
    else if ($action == 'addGrade') {
        $wpdb->insert(
            $wpdb->prefix . 'marks_user_assignment_intersect',
            array(
                'userId' => $_POST['userId'],
                'assignmentId' => $_POST['assignmentId'],
                'pointsAchieved' => $_POST['pointsAchieved'],
                'exempt' => $_POST['exempt'],
                'status' => 'A'
            )
        );
    }
    else if ($action == 'addAssignment') {
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
    else if ($action == 'edit') {
        $wpdb->update(
            $wpdb->prefix . 'marks_assignments',
            array(
                'courseId' => $_POST['courseId'],
                'assignmentName' => $_POST['assignmentName'],
                'unitNumber' => $_POST['unitNumber'],
                'weekNumber' => $_POST['weekNumber'],
                'dueDate' => $_POST['dueDate'],
                'pointsPossible' => $_POST['pointsPossible'],
                'status' => 'A'
            ),
            array(
                'id' => $_POST['assignmentId']
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
                    <th>Student</th>
                    <th>Points Achieved</th>
                    <th></th>
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
                        $grades = $wpdb->get_results(
                            $wpdb->prepare(
                                "
                                SELECT u.display_name, ai.*
                                FROM ".$wpdb->prefix."marks_user_assignment_intersect ai, ".$wpdb->prefix."users u
                                WHERE ai.assignmentId = %d
                                AND u.ID = ai.userId
                                AND ai.status = 'A'
                                ORDER BY u.display_name",
                                $assignment['id']
                            ), ARRAY_A
                        );
                        
                        foreach ($grades as $grade)
                        {
                                ?>
                                <tr>
                                    <td><?php if ($firstRow) { the_title(); $firstRow = false; } ?></td>
                                    <td><?php echo $assignment['unitNumber']; ?></td>
                                    <td><?php echo $assignment['weekNumber']; ?></td>
                                    <td><?php echo $assignment['assignmentName']; ?></td>
                                    <td><?php echo $assignment['dueDate']; ?></td>
                                    <td><?php echo $assignment['pointsPossible']; ?></td>
                                    <td><?php echo $grade['display_name']; ?></td>
                                    <td><?php echo $grade['pointsAchieved']; ?></td>
                                    <form action="" method="post">
                                        <input type="hidden" name="action" value="deleteAssignment" />
                                        <input type="hidden" name="assignmentId" value="<?php echo $assignment['id']; ?>" />
                                        <td><input type="submit" value="Delete Assignment" /></td>
                                    </form>
                                    <form action="" method="post">
                                        <input type="hidden" name="action" value="deleteGrade" />
                                        <input type="hidden" name="assignmentId" value="<?php echo $assignment['id']; ?>" />
                                        <input type="hidden" name="userId" value="<?php echo $grade['userId']; ?>" />
                                        <td><input type="submit" value="Delete Grade" /></td>
                                    </form>
                                </tr>
                            <?php
                        }
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
                    <th>Student</th>
                    <th>Points Achieved</th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
        <p><b>Add New Assignment:</b></p>
        <form action="" method="post">
            <input type="hidden" name="action" value="addAssignment" />
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
        
        <p><b>Edit Assignment:</b></p>
        <form action="" method="post">
            <input type="hidden" name="action" value="edit" />
            <table>
                <tr>
                    <td>Assignment:</td>
                    <td>
                        <select name="assignmentId">
                            <option></option>
                            <?php
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
    
                            foreach ($assignments as $assignment)
                            {
                                ?>
                                    <option value="<?php echo $assignment['id']; ?>"><?php echo $assignment['assignmentName']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                </tr>
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
        
        <p><b>Add Grade:</b></p>
        <form action="" method="post">
            <input type="hidden" name="action" value="addGrade" />
            <table>
                <tr>
                    <td>Assignment:</td>
                    <td>
                        <select name="assignmentId">
                            <option></option>
                            <?php
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
    
                            foreach ($assignments as $assignment)
                            {
                                ?>
                                    <option value="<?php echo $assignment['id']; ?>"><?php echo $assignment['assignmentName']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>User:</td>
                    <td>
                        <select name="userId">
                            <option></option>
                            <?php
                            $user_list = get_users('orderby=nicename&order=ASC');
    
                            foreach ($user_list as $user) {
                                ?>
                                    <option value="<?php echo $user->ID; ?>"><?php echo $user->display_name; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Points Possible:</td>
                    <td><input type="text" name="pointsAchieved" /></td>
                </tr>
                <tr>
                    <td>Exempt:</td>
                    <td>
                        <select name="exempt">
                            <option></option>
                            <option value="Y">Yes</option>
                            <option value="N">No</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th colspan="2"><input type="submit" value="Add Grade"></th>
                </tr>
            </table>
        </form>
    </div>
    <?php
}