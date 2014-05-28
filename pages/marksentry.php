<?php

/**
 * script: marks entry
 * acting as MVC controller
 * 
 * Description:
 * display all marks for a given course,
 * allows insert and/or update of marks
 * 
 * @author jeff starkey <jeffstarkey@inhiscompany.org>
 * @copyright parodi and starkey, 2010
 *  
 */

/* page to allow update of unit information */

// ----------------------------------------
// includes
// ----------------------------------------
require_once("ihcUser.php");
require_once("ihcCourse.php");
require_once("ihcAssignment.php");
require_once("ihcStudentMarks.php");

require_once("ihcCourseView.php");
require_once("ihcStudentMarksView.php");
require_once("ihcAssignmentView.php");

// ----------------------------------------
// script functions
// ----------------------------------------

$scriptFullName = get_option('home') . '/marksentry';

function paintFormTop( $inboundCourseId )
{
	echo '<form action="';
	echo $scriptFullName . '?cid=';
	echo $_REQUEST['cid'];
	echo '&sid=';
	echo $_REQUEST['sid'];
	echo '&do=u" '; 
	echo 'method="post">';
	echo '<input type="hidden" name="isChanged" id="isChanged" value="0">';
}


function paintFormBottom()
{
	echo '</form>';
}


function setMarksInfoFromReqArray( $inMarks, $inputArray, $inAssignmentId, $inStudentId ) 
{
	// echo 'amount: '. $inputArray['points_earned_' . $inAssignmentId . '_' . $inStudentId];
	$inMarks->setPointsAchieved( $inputArray['points_earned_' . $inAssignmentId . '_' . $inStudentId] );

	if ( !empty($inputArray['status_' . $inAssignmentId . '_' . $inStudentId]) )
	{
		// echo 'amount: '. $inputArray['status_' . $inAssignmentId . '_' . $inStudentId];
		$inMarks->setStatus( $inputArray['status_' . $inAssignmentId . '_' . $inStudentId] );
	}
	
}


// -----------------
// request variables
// -----------------
// paint current selected sort order
if (!empty($_REQUEST['cid']))
{
	$inboundCourseId = $_REQUEST['cid'];
}
else
{
	// force set to a junk course id
	$inboundCourseId = -1;
}

if (!empty($_REQUEST['aid']))
{
	$inboundAssignmentId = $_REQUEST['aid'];
}
else
{
	// force set to a junk assignment id
	$inboundAssignmentId = -1;
}

if (!empty($_REQUEST['sid']))
{
	$inboundStudentId = $_REQUEST['sid'];
}
else
{
	// force set to a junk student id
	$inboundStudentId = -1;
}

if ($_REQUEST['do'] == 'u')
{
	$doUpdate = true;
}
else
{
	$doUpdate = false;
}



// ----------------------------------------
// main
// ----------------------------------------

$currentUser = new ihcUser( $current_user );
$course = new ihcCourse( $inboundCourseId, $current_user->ID );

echo '<section class="container_12">';

// only paint links if applicable
$currentRole = $currentUser->getUserRole( $course );
if (($currentRole == 'ADMIN') || ($currentRole == 'INSTRUCTOR_THIS_COURSE'))
{

	$courseView = new ihcCourseView();
	$courseView->paintCourseInfo( $course );
	$courseView->paintStudentSelector( $course->getArrayOfStudents(), $course, $scriptFullName, $inboundStudentId );
	paintFormTop( $inboundCourseId );
	$courseView->paintLinkViewAssignments( $course->getCourseId(), $inboundStudentId );
	
	// only display the grid if there's a student selected
	if ($inboundStudentId != -1)
	{
		$courseView->paintMarksEntryTableStart();
		
		// echo '<br />current student id (' . $_REQUEST['sid'] . '). course id (' . $_REQUEST['cid'] . ').<br />';
		$idx = 0;
		while (($currentAssignmentId = $course->getNextAssignmentIdForCourse()) !== false)
		{
			// echo "<br />assignment id: $currentAssignmentId<br />";
			$marks = new ihcStudentMarks( $currentAssignmentId, $inboundStudentId );
			
			if ($doUpdate)
			{
				setMarksInfoFromReqArray( $marks, $_REQUEST, $currentAssignmentId, $inboundStudentId );	
				$marks->updateMarksToDb( $currentAssignmentId, $inboundStudentId );
			}
			
			$marksView = new ihcStudentMarksView();
			$marksView->paintInputMarks( $marks, $inboundStudentId, $idx );
			
			$idx++;
		}
		
		// populate sum row for the specific student
		$course->populateMarksSumRow( $inboundStudentId );
		$courseView->paintMarksEntryTableEnd( $course );	
	}
	
	paintFormBottom();
}
else
{
	echo "<br />You are not authorized to view this course's information. " . 
	      "This is filler text until Josh cleans it up (1).<br />";
}
echo '</section>';