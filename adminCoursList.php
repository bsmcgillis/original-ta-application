<!--
Author: Blake McGillis
Date: March 21, 2015
Phase: 7
-->

<?php

/*
 * NOTE: The courses with TAs section is not complete. It shouldn't be too bad to implement, though. I just need to
 * modify the query in pullCourseData to put any course that has two TAs in another array. Then populate the Courses
 * with TAs section with that array. That of course assumes that all courses can have no more than 2 TAs which I
 * validate in the courseUpdate page. But that can be done later.
 */


//Require the class before the session_start call
require "classes/user.php";
require "classes/course.php";
require "classes/application.php";
session_start();

//I may want to start using this
//set_include_path("/var/www/html/TA4");

require_once "inc/helper/functions.php";

if (!verify_role('administrator')){
    $_SESSION['requestedPage'] = 'Location: adminCoursList.php';
    $_SESSION['needsLoginAdmin'] = true;

    header('Location: login.php');
}

/**
 * NOTE: I'll need to modify the existing pullCourseData.php file to pull the stuff from the new table. If no semester
 * or year has been selected (like when the user first visits the page) I'll pull the courses for the current semester.
 *
 * I'll also need to create another database file for when the user selects a semester and year and chooses to
 * populate the list. I'll first clear the current table (which I may not be able to do. I'll have to check to see if
 * I have those privileges on this DB account), then, based on the user's semester and year, I'll fill the table back
 * up and allow the newly-modified pullCourseData.php file to run.
 */

//Get correct data into the database
require 'inc/courseScrape.php';

//Then pull that data from the database
require 'inc/pullCourseData.php';

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Blake McGillis - Administrator Course List</title>
    <meta name="author" content="Blake McGillis">
    <meta name="date" content="March 21, 2015">
    <meta name="phase" content="7">
    <link rel="stylesheet" href="styles/styles.css"/>
    <link rel="stylesheet" href="styles/TAStyles.css"/>
    <script src="inc/js/jquery-1.11.2.min.js"></script>
    <script src="inc/js/adminListFunctions.js"></script>
</head>
<body>
    <!--Main content-->
	<div id="contentWrapper">
        <div id="headingDiv">
            <h1 id="subHeading1">Admin Course List</h1>
        </div>
        
        <!--Sidebar included using php-->
        <?php include ("inc/sidebar.php"); ?>

		<div id="mainContent">
            <p class="sectionHeading">
                Incomplete Courses
            </p>
            <p class="center">
                The following courses are still in need of an instructor or at least one TA.
            </p>
            <div id="sem_year_select">
                <p class="center">
                    Please Select a Year and Semester
                </p>
                <div id="form_section">
                    <form method="post" action="">
                    <select id="year" name="year_select" class="inline">
                        <option value="08">2008</option>
                        <option value="09">2009</option>
                        <option value="10">2010</option>
                        <option value="11">2011</option>
                        <option value="12">2012</option>
                        <option value="13">2013</option>
                        <option value="14">2014</option>
                        <option selected="selected" value="15">2015</option>
                    </select>
                    <select id="semester" name="semester_select" class="inline">
                        <option value="4">Spring</option>
                        <option value="6">Summer</option>
                        <option selected="selected" value="8">Fall</option>
                    </select>
                    <input type="submit" id="send_form" value="Submit"/>
                    </form>
                </div>
                <div id="display_section">
                    <?php
                    if ($chosenSemester != "" && $chosenYear != "")
                    {
                        //Translate the semester
                        if($chosenSemester == "4")
                        {
                            $thisSemester = "Spring";
                        }
                        else if($chosenSemester == "6")
                        {
                            $thisSemester = "Summer";
                        }
                        else if($chosenSemester == "8")
                        {
                            $thisSemester = "Fall";
                        }
                    }
                    else
                    {
                        $chosenSemester = "8";
                        $thisSemester = "Fall";
                        $chosenYear = "15";
                    }

                    echo "<p class='sem_year'>$thisSemester 20$chosenYear";
                    ?>
                </div>
            </div>
            <div class="listBox courseList">
                <!-- Insert PHP to generate course names -->
                <?php
                foreach($_SESSION['course_list'] as $course){
                    $courseNum = $course->get_catNum();
                    $courseName = $course->get_title();
                    echo "<div class='adminContainer'>
                            <div class='adminTop'>
                                <p class='admin1Cours'>
                                    $courseNum - $courseName <br/>
                                    <span class='italics'>Component: {$course->get_component()}</span>
                                </p>
                                <p class='admin1Link'>
                                    <!-- the javascript:; makes the link go nowhere and doesn't refresh the page -->
                                    <a href='javascript:;' onclick='extraInfo($courseNum, $chosenSemester, $chosenYear);'>
                                        Show More Information
                                    </a>
                                </p>
                            </div>
                            <div id='course_$courseNum' class='adminBottom'>
                                <p class='course1Inst'>Instructor: {$course->get_instructor()}</p>
                            </div>
                          </div>";
                }

                ?>
            </div>
            
            <p class="sectionHeading">
                Courses With TAs
            </p>
            <p class="center">
                The following courses have been assigned instructors and all required TAs.
            </p>
            <p class="center red">Under Construction</p>
            <div class="listBox">
                <div class='adminContainer'>
                    <div class='admin2Top'>
                        <p class='admin2Cours'>CS 5600 - Intro to Computer Graphics</p>
                        <p class='admin2Inst'>Instructor: So and so</p>
                    </div>
                    <div class='admin2Bottom'>
                        <p class='admin2TAs'>TA: Jerry Maloney</p>
                        <p class='admin2TAs'>TA: Gertrude Stein</p>
                        <p class='admin2TAs'>TA: Rodney Robertson</p>
                        <p class='admin2TAs'>TA: James Garner</p>
                        <p class='admin2TAs'>TA: Sylvia Plath</p>
                    </div>
                </div>

                <p>CS 5610/6610 - Interactive Computer Graphics</p>
                <p>CS 5630/6630 - Scientific Visualization</p>
                <p>CS 5650 - Perception for Graphics</p>
                <p>CS 5710/6710 - Digital VLSI Design</p>
                <p>CS 5720/6720 - Analog Integrated Cir Design</p>
                <p>CS 5740/6740 - CAD of Digital Circuits</p>
                <p>CS 5745/6745 - Testing and Verif of Dig Cirs</p>
                <p>CS 5750/6750 - Synth and Ver of Async VLSI</p>
                <p>CS 5780/6780 - Embedded System Design</p>
                <p>CS 5785/6785 - Adv Embedded Software</p>
                <p>CS 5789 - Embedded Systs and Kinetic Art</p>
            </div>
		</div>
	</div>
</body>
</html>