<?php

// ----------------------------------------
// includes
// ----------------------------------------
require_once "ihcUser.php";
require_once "ihcCourseMulti.php";
require_once "ihcCourse.php";
require_once "ihcAssignment.php";
require_once "ihcStudentMarks.php";

require_once "ihcCourseView.php";
require_once "ihcStudentMarksView.php";


// ----------------------------------------
// script functions
// ----------------------------------------


// -----------------
// request variables
// -----------------
// Course Id
if (!empty($_REQUEST['cid']))
{
	$inboundCourseId = $_REQUEST['cid'];
}
else
{
	// force set to a junk course id
	$inboundCourseId = -1;
}

// Student Id
if (!empty($_REQUEST['sid']))
{
	$inboundStudentId = $_REQUEST['sid'];
}
else
{
	// force set to a junk student id
	$inboundStudentId = -1;
}


// ----------------------------------------
// main
// ----------------------------------------

// CREATE OBJECTS
$currentUser = new ihcUser( $current_user );
$course = new ihcCourse( $inboundCourseId, $current_user->ID);

echo '<section class="container_12">';

// only paint links if applicable
$currentRole = $currentUser->getUserRole( $course );

if (($currentRole == 'ADMIN') || ($currentRole == 'INSTRUCTOR_THIS_COURSE') || ($currentRole == 'STUDENT') || ($currentRole == 'PARENT'))
{
	// BEGIN CONTENT PROCESSING
	$course->populateCourse();
	
	?>
		<?php
	
		// only updates by special roles
		if (($currentRole == 'ADMIN') || ($currentRole == 'INSTRUCTOR_THIS_COURSE'))
		{
			// for admin's
			$courseView = new ihcCourseView( $inboundStudentId );
			$courseView->paintCourseInfo( $course );
			$courseView->paintStudentSelector( $course->getArrayOfStudents(), $course, $scriptFullName, $inboundStudentId );
			$courseView->paintLinkUpdateMarks( $course->getCourseId(), $inboundStudentId );
			$course->populateMarksSumRow( $inboundStudentId );
		}
		else
		{
			// for students/parents/etc
			$courseView = new ihcCourseView( $current_user->ID );
			$courseView->paintCourseInfo( $course );
			// set student id, for all subsequent painting
			$inboundStudentId = $current_user->ID;
			$courseView->paintLinkPrintMarks( $course->getCourseId(), $inboundStudentId );
		}
		
		// only display the grid if there's a student selected
		if ($inboundStudentId != -1)
		{
			$courseView->paintMarksTableStart( $inboundCourseId );
			
			// PERFORM ALL ACTIONS FOR EACH MARK IN COURSE (INCLUDES ASSIGNMENTS WITHOUT MARKS ASSOCIATED)
			while ( ( $currentAssignmentId = $course->getNextAssignmentIdForCourse() ) !== false )
			{
				//$studentMarksRow = new ihcStudentMarks( $currentAssignmentId, $current_user->ID );
				$studentMarksRow = new ihcStudentMarks( $currentAssignmentId, $inboundStudentId );
				$marksView = new ihcStudentMarksView();
				$marksView->paintStudentMarksRowInfo( $studentMarksRow );
			}
	
			$courseView->paintMarksTableEnd( $course );		
		}
	}
	else
	{
		echo "<br />You are not authorized to view this course's information. " . 
		      "This is filler text until Josh cleans it up (1).<br />";
	}
	?>
	</section>
