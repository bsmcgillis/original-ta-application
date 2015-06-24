<!--
Author: Blake McGillis
Date: March 21, 2015
Phase: 7
-->

<?php
/**
 * Called by adminListFunctions.js. Gathers additional information for a particular course.
 */

//Grab the variable passed by the AJAX function and store it
$courseNum = $_POST['courseNumber'];
$year = "20" . $_POST['courseYear'];
$semester = $_POST['courseSemester'];


/*Contains database variables*/
require 'helper/dbConfig.php';
/*Contains helpful functions*/
require 'helper/functions.php';

require_once "../classes/course.php";
require_once "../classes/user.php";

try {
    /* Connect to the database */
    $dbh = new PDO("mysql:host=$host;dbname=$dbName;port=$port", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /*Query for the additional information for this class*/
    $query = "SELECT enroll_cap, curr_enroll, seats_avail, units, time, location
              FROM Pulled_Courses
              WHERE cat_num = :catNum";

    $stmt = $dbh->prepare($query);

    $stmt->bindValue(':catNum', $courseNum, PDO::PARAM_STR);

    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //Create a course object to hold this course data
    $thisCourse = new Course();

    foreach($result as $row) {

        //Set the available variables
        $thisCourse->set_enroll_cap($row['enroll_cap']);
        $thisCourse->set_curr_enroll($row['curr_enroll']);
        $thisCourse->set_seats_avail($row['seats_avail']);
        $thisCourse->set_units($row['units']);
        $thisCourse->set_time($row['time']);
        $thisCourse->set_location($row['location']);
    }

    /*
    * Next, we'll need to find the TAs that have requested to TA for this course this semester and year
    */

    $query = "SELECT DISTINCT U.uID, U.first_name, U.last_name, TA.class_request, TA.recommend
              FROM Users U, TA_Applicants TA
              WHERE U.uID = TA.uID AND TA.class_request = :catNum AND semester = :semester AND year = :year
              ORDER BY TA.recommend DESC";

    $stmt = $dbh->prepare($query);

    $stmt->bindValue(':catNum', $courseNum, PDO::PARAM_INT);
    $stmt->bindValue(':semester', $semester, PDO::PARAM_STR);
    $stmt->bindValue(':year', $year, PDO::PARAM_INT);

    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //Create an array to hold all of the User objects
    $taArray = [];

    foreach($result as $row) {

        //Create a new user object
        $currentUser = new User();

        //Set the available variables
        $currentUser->set_uid($row['uID']);
        $currentUser->set_first_name($row['first_name']);
        $currentUser->set_last_name($row['last_name']);
        $currentUser->set_class_request($row['class_request']);
        $currentUser->set_recommend($row['recommend']);

        //Add the object to correct array
        $taArray[] = $currentUser;
    }

    $dbh = null;
}

catch(PDOException $e)
{
    print "Error!:" .$e->getMessage(). "<br/>";
    die();
}

/*
 * Now that all of the information has been gathered from the database, we can use it to generate some html.
 *
 */

//Add the additional course information in its own divs
$html = "<div id='extra_info_$courseNum'>
            <div class='adminBottom'>
                <p class='admin_bot_item_1'>Enrollment Cap: {$thisCourse->get_enroll_cap()}</p>
                <p class='admin_bot_item_2'>Currently Enrolled: {$thisCourse->get_curr_enroll()}</p>
                <p class='admin_bot_item_2'>Seats Available: {$thisCourse->get_seats_avail()}</p>
            </div>
            <div class='adminBottom'>
                <p class='admin_bot_item_3'>Units: {$thisCourse->get_units()}</p>
                <p class='admin_bot_item_2'>Location: {$thisCourse->get_location()}</p>
                <p class='admin_bot_item_4'>Time: {$thisCourse->get_time()}</p>
            </div>";

//Now, add the prospective TAs for this course, if there are any
if (count($taArray) > 0){

    //Each TA gets their own div with their UID, name, and a select list with their recommend level and other possible levels
    foreach ($taArray as $ta){

        $uid = $ta->get_uid();
        $recLevel = $ta->get_recommend();
        $selectID = "select_" . $uid . "_" . $courseNum;

        //Create the string that will be used as the arguments for the updateRecommend function
        $arguments = "\"" . $semester . "\", \"" . $year . "\", \"" . $uid . "\", \"" . $courseNum . "\"";

            $html .= "<div class='adminBottom'>
                    <p class='admin_bot_item_6'>UID: $uid</p>
                    <p class='admin_bot_item_5'>Name: {$ta->get_full_name()} </p>
                    <select class='admin_bot_item_6' id=$selectID
                    onchange='updateRecommend($arguments);'>";
                    for ($i = 0; $i < 5; $i++) {
                        $recommend = get_recommendation($i);
                        if (option_selected($i, $recLevel)) {
                        $html .= "<option value=$i selected>$recommend</option>";
                        } else {
                        $html .= "<option value=$i>$recommend</option>";
                        }
                    }

        $html .= "</select></div>";
    }
}
//If the course has no TAs, add the information
else {
    $html .= "<div class='adminBottom'>
                <p class='admin_bot_item_6 hidden'></p>
                <p class='admin_bot_item_5'>No Prospective TAs</p>
                <p class='admin_bot_item_6 hidden'></p>
              </div>";
}

$html .= "</div>";

//Echo the html so that the AJAX call can complete
echo $html;

?>