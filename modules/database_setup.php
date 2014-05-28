<?php

register_activation_hook( __FILE__, 'marks_install' );
add_action( 'plugins_loaded', 'marks_update_db_check' );

global $marks_db_version;
global $wpdb;
$marks_db_version = '1.0';

function marks_install() {
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    global $wpdb;
    global $marks_db_version;

    $table_name = $wpdb->prefix . 'marks_user_course_intersect';
    $sql = "CREATE TABLE ".$table_name." (
        id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        userId mediumint(9) NOT NULL,
        courseId mediumint(9) NOT NULL,
        courseAdmin SET('Y','N') NOT NULL,
        status SET('A','I') NOT NULL,
        createdDate datetime DEFAULT '0000-00-00 00:00:00' NOT NULL
    );";

    dbDelta( $sql );
    
    $table_name = $wpdb->prefix . 'marks_assignments';
    $sql = "CREATE TABLE ".$table_name." (
        id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        courseId mediumint(9) NOT NULL,
        assignmentName varchar(100) NOT NULL,
        assignmentDesc varchar(1000),
        unitNumber SET('1','2','3','4','5'),
        weekNumber SET('1','2','3','4','5','6'),
        dueDate date,
        pointsPossible mediumint(9) NOT NULL,
        status SET('A','I') NOT NULL
    );";
    
    dbDelta( $sql );
    
    $table_name = $wpdb->prefix . 'marks_user_assignment_intersect';
    $sql = "CREATE TABLE ".$table_name." (
        id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        userId mediumint(9) NOT NULL,
        assignmentId mediumint(9) NOT NULL,
        pointsAchieved mediumint(9) NOT NULL,
        exempt SET('Y','N') NOT NULL,
        status SET('A','I') NOT NULL,
        createdDate date NOT NULL
    );";

    dbDelta( $sql );

    update_option( 'marks_db_version', $marks_db_version );
}

function marks_update_db_check() {
    global $marks_db_version;
    
    if (get_site_option( 'marks_db_version' ) != $marks_db_version) {
        marks_install();
    }
}