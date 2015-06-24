<!--
Author: Blake McGillis
Date: March 21, 2015
Phase: 7
-->

<?php

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

/*Only attempt to process POST data, if this page was reached via POST*/
if (isset($_POST['semester_select']))
{
    require 'inc/sendAppData.php';
}

/*Pull all of this user's applications from the database*/
require 'inc/pullAppData.php';

/*Set up the appArray variable with the user's applications*/
$appArray = $_SESSION['applications'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Blake McGillis - Applicant Home Page</title>
    <meta name="author" content="Blake McGillis">
    <meta name="date" content="March 21, 2015">
    <meta name="phase" content="7">
    <link rel="stylesheet" href="styles/styles.css"/>
    <link rel="stylesheet" href="styles/TAStyles.css"/>
</head>
<body>
<!--Main content-->
<div id="contentWrapper">
    <div id="headingDiv">
        <h1 id="subHeading1">Applicant Home Page</h1>
    </div>

    <!--Sidebar included using php-->
    <?php include ("inc/sidebar.php"); ?>

    <div id="mainContent">
        <p class="sectionHeading">
            TA Application List
        </p>
        <p class="center">
            Please select an application you'd like to view.
        </p>
        <div class="listBox">
            <!-- Insert PHP to generate application list -->
            <?php
            if (count($appArray) > 0)
            {
                $_SESSION['appStatus'] = 'Pending'; //<--I think I can DELETE this line
                foreach($appArray as $app)
                {
                    $date = $app->get_time_stamp();
                    $formattedDate = date("F j, Y, g:i a", strtotime($date));
                    $name = $_SESSION['full_name'];

                    echo "<p><a href='appSelected.php?time=$date'>$name TA Application -- $formattedDate </a></p>";
                }
            }
            else
            {
                $_SESSION['appStatus'] = 'No Applications';
                echo "<p>No applications to display</p>";
            }
            ?>
        </div>
        <div id="statusBox">
            <?php
            $user = $_SESSION['user'];
            $status_array = $user->get_app_status_array();
            if(count($status_array) > 0) {
                foreach ($status_array as $status) {
                    if ($status->get_recommend() == 4) {
                        $statusColor = "fancyGreen";
                        $statusText = "Confirmed!";
                    } else {
                        $statusColor = "fancyYellow";
                        $statusText = "Pending";
                    }

                    echo "<p class='statusItem'>CS {$status->get_course()}: <span class=$statusColor>$statusText</span></p>";
                }
            }
            else
            {
                echo "<p class='statusItem'>No Pending Applications</p>";
            }
            ?>

        </div>
    </div>
</div>
</body>
</html>

