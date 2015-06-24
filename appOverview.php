<!--
Author: Blake McGillis
Date: March 21, 2015
Phase: 7
-->

<?php


/*
 * NOTE: Here is the idea for this page:
 *
 * Design:
 * 1. I'll need three boxes. One for confirmed TAs, one for considered TAs, and one for withdrawn TAs.
 * 2. The boxes could contain blocks similar to those one the adminCoursList page. They'll need to contain each
 *    applicant's name, UID, the course number and name they'd like to TA for and their recommendation level
 *    (unless they're in the confirmed box).
 *
 * Database
 * The information for this page exists in several different places. So, populating the data will take a few queries.
 * 1. Get all the individual UIDs in the TA_Application table. Store them in User objects.
 * 2. Query the TA_Application table again to get the courses and recommendation levels for each of the applicants.
 * 3. For each applicant's course number, I'll need to query the Courses table to get the course name (I should also
 *    add course_name to the App_Status subclass.
 * 4. Lastly, I'll need to query the Users table to get the names for each of the applicants.
 */

/*Require classes and start the session*/
require "classes/user.php";
require "classes/course.php";
require "classes/application.php";
session_start();

/*Require the functions file*/
require_once "inc/helper/functions.php";

/*Verify that the current user has the applicant role*/
if (!verify_role('applicant')){
    $_SESSION['requestedPage'] = 'Location: appForm.php';
    $_SESSION['needsLoginApp'] = true;

    header('Location: login.php');
}

?>