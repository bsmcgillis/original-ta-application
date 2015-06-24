<!--
Author: Blake McGillis
Date: March 21, 2015
Phase: 7
-->

<?php
/*
 * Called by appDisplay.php. Pulls all of the user's applications as well as their recommendation levels.
 */


/*Contains database variables*/
require 'helper/dbConfig.php';

try {
    /* Connect to the database */
    $dbh = new PDO("mysql:host=$host;dbname=$dbName;port=$port", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /* Query for all applications with this UID */
    $query = "SELECT * FROM Application WHERE uID = :uid ORDER BY time_stamp DESC";

    $stmt = $dbh->prepare($query);

    $stmt->bindParam(':uid', $_SESSION['uid'], PDO::PARAM_STR);

    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //Create an array to hold all of the Application objects
    $appArray = [];

    foreach ($result as $row) {

        //Create a new application object
        $currentApp = new Application();

        //Assign all available variables
        $currentApp->set_semester($row['semester']);
        $currentApp->set_year($row['year']);
        $currentApp->set_uid($row['uID']);
        $currentApp->set_degree($row['degree']);
        $currentApp->set_avail_hours($row['avail_hours']);
        $currentApp->set_addit_details($row['addit_details']);
        $currentApp->set_promised_aid($row['promised_aid']);
        $currentApp->set_grad_origin($row['grad_origin']);
        $currentApp->set_grad_ita($row['grad_ita']);
        $currentApp->set_time_stamp($row['time_stamp']);

        //Add the current application to the array
        $appArray[] = $currentApp;
    }

    /* Query for info for previously TA'd courses and add it to the correct Application object */
    $prevQuery = "SELECT Courses_Info.number, Courses_Info.name, Courses_Info.blurb,
	Courses_Info.department, Prev_TA_Courses.ta_experience, Prev_TA_Courses.time_stamp
	FROM (Courses_Info JOIN Prev_TA_Courses
	ON Courses_Info.number = Prev_TA_Courses.number)
	WHERE uID = :uid";

    $prevStmt = $dbh->prepare($prevQuery);

    $prevStmt->bindParam(':uid', $_SESSION['uid'], PDO::PARAM_STR);

    $prevStmt->execute();

    $prevResult = $prevStmt->fetchAll(PDO::FETCH_ASSOC);


    /* Add the Previously TA'd courses to the correct Application object */
    foreach($prevResult as $prev)
    {
        //Find the application that corresponds to this course by the time_stamp
        $currentApp = find_app_by_time($appArray, $prev['time_stamp']);

        //Add the course to the application object
        $currentApp->add_prev_ta($prev['department'], $prev['number'], $prev['name'], $prev['ta_experience']);
    }


    /* Query for info for requested courses to TA and add it to the Application object as well */
    $reqQuery = "SELECT Courses_Info.number, Courses_Info.name, Courses_Info.blurb,
	Courses_Info.department, Request_TA_Courses.ta_info, Request_TA_Courses.time_stamp
	FROM (Courses_Info JOIN Request_TA_Courses
	ON Courses_Info.number = Request_TA_Courses.number)
	WHERE uID = :uid";

    $reqStmt = $dbh->prepare($reqQuery);

    $reqStmt->bindParam(':uid', $_SESSION['uid'], PDO::PARAM_STR);

    $reqStmt->execute();

    $reqResult = $reqStmt->fetchAll(PDO::FETCH_ASSOC);

    /* Add the Requested TA courses to the correct Application object */
    foreach($reqResult as $req)
    {
        //Find the application that corresponds to this course by the time_stamp
        $currentApp = find_app_by_time($appArray, $req['time_stamp']);

        //Add the course to the application object
        $currentApp->add_request_ta($req['number'], $req['name'], $req['ta_info']);
    }

    //Now that everything has been added to all the Application objects, put the array into a session variable
    $_SESSION['applications'] = $appArray;



    /* Pull the student's recommend level for existing TA applications */
    $query = "SELECT class_request, recommend FROM TA_Applicants WHERE uID = :uid;";

    $stmt = $dbh->prepare($query);

    $stmt->bindParam(':uid', $_SESSION['uid'], PDO::PARAM_STR);

    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //Get the User object for this user from the session variable
    $user = $_SESSION['user'];

    //Clear out any existing status arrays for the user
    $user->clear_app_status_array();

    //Now assign the user's app status for their current applications
    foreach($result as $row)
    {
        $user->add_app_status($row['class_request'], $row['recommend']);
    }

    $dbh = null;
}

catch(PDOException $e)
{
    print "Error!:" .$e->getMessage(). "<br/>";
    die();
}
?>