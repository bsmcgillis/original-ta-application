<!--
Author: Blake McGillis
Date: March 21, 2015
Phase: 7
-->

<?php
/*
 * Called by courseUpdate.php. Checks to see if an instructor was selected and whether any TA recommendation
 * levels have been changed. Also updates the Courses table with the number of confirmed TAs.
 */


/*Contains database variables*/
require 'helper/dbConfig.php';

try {
    /* Connect to the database */
    $dbh = new PDO("mysql:host=$host;dbname=$dbName;port=$port", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /* If an instructor was selected, update the Course table */
    if ($_POST['instructor_select'] != "default")
    {
        $query = "UPDATE Courses C SET C.instructor = (
              SELECT CONCAT(U.first_name, ' ', U.last_name) FROM Users U
              WHERE U.uID = :inst_uid) WHERE C.number = :course_number";

        $stmt = $dbh->prepare($query);

        $stmt->bindParam(':inst_uid', $_POST['instructor_select'], PDO::PARAM_STR);
        $stmt->bindParam(':course_number', intval($_SESSION['course_num_get']), PDO::PARAM_INT);

        $stmt->execute();
    }


    /* Check each TA's new recommend level. If it's different from the old one, update the database. */
    foreach($_SESSION['ta_applicant_list'] as $ta)
    {
        $uid = $ta->get_uid();
        $oldRec = $ta->get_recommend();
        $newRec = $_POST[$uid];
        if ($newRec != $oldRec)
        {
            $query = "UPDATE TA_Applicants
                  SET recommend = :newRec
                  WHERE uID = :app_uid
                  AND class_request = :course_number";

            $stmt = $dbh->prepare($query);

            $stmt->bindParam(':newRec', intval($newRec), PDO::PARAM_INT);
            $stmt->bindParam(':app_uid', $uid, PDO::PARAM_STR);
            $stmt->bindParam(':course_number', intval($_SESSION['course_num_get']), PDO::PARAM_INT);

            $stmt->execute();
        }
    }


    /* Then make sure to keep the Courses table updated with how many TAs have been confirmed*/
    if ($_POST['ta_select'] != "default")
    {
        $query = "UPDATE Courses C
                  SET C.TAs = (SELECT COUNT(*)
                  FROM TA_Applicants TA
                  WHERE TA.class_request = :course_number AND TA.recommend = 4)
                  WHERE C.number = :course_number; ";

        $stmt = $dbh->prepare($query);

        $stmt->bindParam(':course_number', intval($_SESSION['course_num_get']), PDO::PARAM_INT);
        $stmt->bindParam(':course_number', intval($_SESSION['course_num_get']), PDO::PARAM_INT);

        $stmt->execute();
    }


    $dbh = null;
}

catch(PDOException $e)
{
    print "Error!:" .$e->getMessage(). "<br/>";
    die();
}
?>