<!--
Author: Blake McGillis
Date: March 21, 2015
Phase: 7
-->

<?php
/**
 * Called by adminListFunctions.js. Updates a prospective TA's recommendation level in the database
 */

//Grab the variable passed by the AJAX function and store it
$courseNum = $_POST['courseNumber'];
$year = $_POST['courseYear'];
$semester = $_POST['courseSemester'];
$uid = $_POST['studentUID'];
$newVal = $_POST['newRecLevel'];


/*Contains database variables*/
require 'helper/dbConfig.php';

try {
    /* Connect to the database */
    $dbh = new PDO("mysql:host=$host;dbname=$dbName;port=$port", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /*Query for the additional information for this class*/
    $query = "UPDATE TA7.TA_Applicants SET recommend = :recLevel
              WHERE uID = :uid AND semester = :semester AND year = :year AND class_request = :courseNum;";

    $stmt = $dbh->prepare($query);

    $stmt->bindValue(':recLevel', $newVal, PDO::PARAM_INT);
    $stmt->bindValue(':uid', $uid, PDO::PARAM_STR);
    $stmt->bindValue(':semester', $semester, PDO::PARAM_STR);
    $stmt->bindValue(':year', $year, PDO::PARAM_INT);
    $stmt->bindValue(':courseNum', $courseNum, PDO::PARAM_INT);

    $stmt->execute();

    $dbh = null;
}

catch(PDOException $e)
{
    print "Error!:" .$e->getMessage(). "<br/>";
    die();
}