<!--
Author: Blake McGillis
Date: March 21, 2015
Phase: 7
-->

<?php

//Require the class before the session_start call
require "classes/user.php";
require "classes/course.php";
require "classes/application.php";
session_start();

require_once "inc/helper/functions.php";

if (!verify_role('administrator')){
    $_SESSION['requestedPage'] = 'Location: adminCoursList.php';
    $_SESSION['needsLoginAdmin'] = true;

    header('Location: login.php');
}

//If this page was reached via GET, set up the session variables and variables
if (isset($_GET['course_num']))
{
    $courseNumGet = $_SESSION['course_num_get'] = $_GET['course_num'];
    $courseNameGet = $_SESSION['course_name_get'] = $_GET['course_name'];
}
//Otherwise, get it from the session variable
else
{
    $courseNumGet = $_SESSION['course_num_get'];
    $courseNameGet = $_SESSION['course_name_get'];
}

//If this page was reached via POST, require updateCourse
if (isset($_POST['course_num']))
{
    require "inc/updateCourse.php";
}

//Now call pullSingleCourse.php to get the instructors and interested TAs
require "inc/pullSingleCourse.php";

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Blake McGillis - Course Update</title>
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
        <h1 id="subHeading1">Course Update</h1>
    </div>

    <!--Sidebar included using php-->
    <?php include ("inc/sidebar.php"); ?>

    <div id="mainContent">
        <p class="sectionHeading">
            <?php
            echo "$courseNumGet - $courseNameGet";
            ?>
        </p>
        <p class="center">
            <?php
            echo "Update information for $courseNumGet - $courseNameGet";
            ?>
        </p>
        <div id="twoBox">
            <!-- Begin Form -->
            <form method="post" action="courseUpdate.php">

                <!--Send a hidden input to get the course number -->
                <?php
                    echo "<input type='hidden' name='course_num' value=$courseNumGet />";
                ?>
                <div id="boxOne" class="innerBox">
                    <p class="sectionLabel center">Select an Instructor</p>
                    <p class="center">
                        <select id="assignInstructor" name="instructor_select">
                        <option value="default">Instructors</option>
                        <?php
                        /*Generating option rows*/
                        foreach($_SESSION['instructor_list'] as $instructor)
                        {
                            $name = $instructor->get_full_name();
                            $uid = $instructor->get_uid();

                            echo "<option value=$uid>$name</option>";
                        }
                        ?>
                        </select>
                    </p>
                    <?php
                    $instructor = $_SESSION['course_instructor'];
                    if (strlen($instructor) > 0)
                    {

                        echo "<p class='center'>Assigned Instructor: $instructor";
                    }
                    ?>
                </div>
                <div id="boxTwo" class="innerBox">
                    <p class="sectionLabel center">Manage TA Status</p>
                    <div id="adminTAList">
                        <?php
                        if (count($_SESSION['ta_applicant_list']) > 0)
                        {
                            foreach ($_SESSION['ta_applicant_list'] as $ta) {
                                $name = $ta->get_full_name();
                                $uid = $ta->get_uid();
                                $recLevel = $ta->get_recommend();
                                echo "
                                <div class='inlineGroup'>
                                    <p class='adminTA'>$name</p>
                                    <p class='adminTASelect'>
                                        <select id='taRec' name=$uid>";
                                for ($i = 0; $i < 5; $i++) {
                                    $recommend = get_recommendation($i);
                                    if (option_selected($i, $recLevel)) {
                                        echo "<option value=$i selected>$recommend</option>";
                                    } else {
                                        echo "<option value=$i>$recommend</option>";
                                    }
                                }
                                echo "
                                        </select>
                                    </p>
                                </div>

                            ";
                            }
                        }
                        else
                        {
                            echo "<p class='center'>No TAs available</p>";
                        }
                        ?>
                    </div>
                </div>
                <div id="updateTextBox">
                    <p class="center">
                        Please enter any information you have regarding this course.
                    </p>
                    <p>
                        <textarea id="courseUpdateText" name="course_update_text" rows="10" cols="60">This doesn't actually go anywhere yet.
                        </textarea>
                    </p>
                </div>
                <div id="centerUpdate">
                    <input type="submit" value="Update"/>
                </div>
            <!-- End Form -->
            </form>
        </div>

    </div>
</div>
</body>
</html>