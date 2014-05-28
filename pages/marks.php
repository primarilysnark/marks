<?php

// ----------------------------------------
// includes
// ----------------------------------------
require_once "ihcUser.php";
require_once "ihcCourseMulti.php";
require_once "ihcCourse.php";
//require_once "ihcAssignment.php";
//require_once "ihcStudentMarks.php";

require_once "ihcCourseView.php";
//require_once "ihcAssignmentView.php";
//require_once "ihcStudentMarksView.php";


// ----------------------------------------
// script functions
// ----------------------------------------
function paintFormTop()
{
	echo '<form action="';
	echo get_option('home');
	echo '/dashboard" method="post">';
	
}

function paintFormBottom()
{
	echo '<br /><br /><input type="submit" value="         Submit         ">';
		
	echo '</form>';
}

// ----------------------------------------
// main
// ----------------------------------------


// -----------------
// request variables
// -----------------

// -----------------
// paint property
// header
// -----------------

?>
<section class="container_12">
	<article class="grid_12" id="search-article">
		<header class="blog-header">
			<h1>Marks</h1>
		</header>
	</article>
	<article class="grid_12">
		<section class="invoice-table-header"></section>
			<section class="invoice-table-container">
				<table>

<?php

// logged in users will use $current_user->ID
$currentUser = new ihcUser( $current_user );
$noCoursesFound = true;

$courseMulti = new ihcCourseMulti( $currentUser );
// set up the list of properties associated with the give user
// only paint links if applicable
$courseMulti->populateCourseIdList();
			
while (($currentCourseId = $courseMulti->getNextCourseIdForUser()) !== false)
{
	$course = new ihcCourse( $currentCourseId, $current_user->ID);
	
	$currentRole = $currentUser->getUserRole( $course );
	if ($currentRole !== 'NO_ACCESS')
	{
		$course->populateCourse();
		//$course->populateCourseSumRow();
		
		$courseView = new ihcCourseView( $current_user->ID );
		//$courseView->paintLinkViewAssignments( $course->getCourseId() );
		$courseView->paintCourseDashboardRow( $course, $currentRole );
		//$courseView->paintCourseSum( $course );
		$noCoursesFound = false;
	}
}

if ($noCoursesFound)
{
	echo '<tr><td colspan="4" style="text-align: center; font-size: 16px;">No Marks Found</td></tr>';
}

?>
				</table>
			</form>
		</section>
	</article>
</section>