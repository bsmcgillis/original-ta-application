<!--
Author: Blake McGillis
Date: March 21, 2015
Phase: 7
-->

<?php
require "classes/user.php";
require "classes/course.php";
require "classes/application.php";
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Blake McGillis - TA5 README</title>
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
            <h1 id="subHeading1">README</h1>
        </div>
        
        <!--Sidebar included using php-->
        <?php include ("inc/sidebar.php"); ?>
        
		<div id="mainContent">
            <p class="sectionHeading">
                AJAX
            </p>
            <p>
                In my implementation of the Admin Course List, each course for the selected semester is listed in
                a scrollable div and shows the course number, component, and instructor. Upon clicking the "Show
                More Information" link, the extraInfo function found in adminListFunctions.js is fired which
                makes an AJAX call to additCourseInfo.php. That file queries the database for the rest of the
                chosen course's information (enrollment cap, location, units, etc.). It also queries for any
                applicants who would like to TA for that class. Each TA is displayed with their UID, name, and
                recommendation level.
            </p>
            <p>
                Upon changing the applicant's recommendation level, the updateRecommend function (also found in
                adminListFunctions.js) is fired. That function makes an AJAX call to updateTARec.php which
                updates the database with the selected TA's new recommendation level. To let the user know that
                the new recommendation level has been recorded, the text is changed to green.
            </p>
            <p>
                By clicking the "Show More Information" link again, the selected course collapses to the original
                information display. Any course that has already been populated with its additional information
                will not cause another AJAX call when "Show More Information" is clicked. Instead, the information
                will have its visibility set to allow the user to see it.
            </p>
            <p>
                The following courses have TAs associated with them:<br /><br />
                <b>Fall 2015</b><br />
                1410<br />
                2100<br />
                2420<br />
                <br />
                <b>Spring 2015</b><br/>
                2100
            </p>
            <p class="sectionHeading">
                Updates
            </p>
            <p>
                The Application Form has been updated to work with the table of courses scraped from the University
                website for Fall 2015. Also, TA applicants are now corrected added to the TA_Applicants table from
                which the Admin Course List gathers its TA recommendation information. Lastly, while logged in as a
                TA Applicant, the Applicant Home page shows the current status for the applicant for each class they
                have requested to TA. (Additional Applicant Users have been added to the list of user credentials)
            </p>
            <p>
                The Registration page has been updated to let the user know the requirements for an account password.
            </p>

            <p class="sectionHeading">
                Logins
            </p>
             <p>
                 Admin User<br />
                 Username: Globe<br />
                 Password: GreatPass
            </p>
            <p>
                Instructor User (Not a whole lot to see with this one)<br />
                Username: Spider<br />
                Password: Raccoon
            </p>
            <p>
                Applicant User<br />
                Username: Question<br />
                Password: 1Dragoning
            </p>
            <p>
                Applicant User<br />
                Username: DMurph<br />
                Password: Slither
            </p>
            <p>
                Applicant User<br />
                Username: Roddy<br />
                Password: Lemur
            </p>
		</div>
	</div>
</body>
</html>
