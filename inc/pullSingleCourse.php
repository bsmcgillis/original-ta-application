<!--
Author: Blake McGillis
Date: March 21, 2015
Phase: 7
-->

<?php
/*
 * Called by courseUpdate.php. Queries the database for the instructor for a class as well as the potential
 * instructors and potential TAs.
 */

/*Contains database variables*/
require 'helper/dbConfig.php';

try {
    /* Connect to the database */
    $dbh = new PDO("mysql:host=$host;dbname=$dbName;port=$port", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /* Query for all instructors */
    $query = "SELECT U.uID, U.first_name, U.last_name
              FROM Users U, Roles R
              WHERE U.idUsers = R.idUsers AND R.role = 'instructor'";

    $stmt = $dbh->prepare($query);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //Create an array to hold all of the User objects
    $userArray = [];

    foreach($result as $row) {

        //Create a new user object
        $currentUser = new User();

        //Set the available variables
        $currentUser->set_uid($row['uID']);
        $currentUser->set_first_name($row['first_name']);
        $currentUser->set_last_name($row['last_name']);

        //Add the object to the array
        $userArray[] = $currentUser;
    }

    //Put the array of user objects into a session variable
    $_SESSION['instructor_list'] = $userArray;

    /* Query for an instructor for this course */
    $query = "SELECT instructor FROM Courses WHERE number = :course_number";

    $stmt = $dbh->prepare($query);

    $stmt->bindParam(':course_number', intval($_SESSION['course_num_get']), PDO::PARAM_INT);

    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //Create an array to hold all of the User objects
    $userArray = [];

    foreach($result as $row) {

        $instructor = $row['instructor'];
    }

    //Put the array of user objects into a session variable
    $_SESSION['course_instructor'] = $instructor;


    /* Now query for all the applicants to this course */
    $query = "SELECT DISTINCT U.uID, U.first_name, U.last_name, TA.class_request, TA.recommend, TA.assigned
              FROM Users U, TA_Applicants TA
              WHERE U.uID = TA.uID AND TA.class_request = :course_number ORDER BY TA.recommend DESC";

    $stmt = $dbh->prepare($query);

    $stmt->bindParam(':course_number', intval($_SESSION['course_num_get']), PDO::PARAM_INT);

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
        $currentUser->set_assigned($row['assigned']);


        //Add the object to correct array
        $taArray[] = $currentUser;
    }

    //Put the array of user objects into a session variable
    $_SESSION['ta_applicant_list'] = $taArray;

    $dbh = null;
}

catch(PDOException $e)
{
    print "Error!:" .$e->getMessage(). "<br/>";
    die();
}
?>