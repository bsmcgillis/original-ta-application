<!--
Author: Blake McGillis
Date: March 21, 2015
Phase: 7
-->

<?php
/*
 * Called by adminCoursList.php. Pulls all courses from the database.
 */

/*Contains database variables*/
require 'helper/dbConfig.php';

try {
    /* Connect to the database */
    $dbh = new PDO("mysql:host=$host;dbname=$dbName;port=$port", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /* Query for info on all courses */
    $query = "SELECT * FROM Pulled_Courses";

    $stmt = $dbh->prepare($query);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //Create an array to hold all of the Course objects
    $courseArray = [];

    foreach($result as $row) {

        //Create a new course object
        $currentCourse = new Course();

        //Set the available variables
        $currentCourse->set_catNum($row['cat_num']);
        $currentCourse->set_title($row['title']);
        $currentCourse->set_component($row['component']);
        $currentCourse->set_instructor($row['instructor']);

        //Add the object to the array
        $courseArray[] = $currentCourse;
    }

    //Put the array of course objects into a session variable
    $_SESSION['course_list'] = $courseArray;


    $dbh = null;
}

catch(PDOException $e)
{
    print "Error!:" .$e->getMessage(). "<br/>";
    die();
}
?>