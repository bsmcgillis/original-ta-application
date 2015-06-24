<!--
Author: Blake McGillis
Date: March 21, 2015
Phase: 7
-->

<?php

/*
 * NOTE: I could add delete buttons to the previously TA'd courses and at least the additional divs for the
 *       requested TA courses (they'll have to choose at least one, obviously). I could also have a question
 *       or button that says "Have you previously worked as a TA?" And when they click it, the initial
 *       previously TA'd div appears.
 */


/*Require the classes and start the session*/
require "classes/user.php";
require "classes/course.php";
require "classes/application.php";
session_start();

/*Require functions file*/
require_once "inc/helper/functions.php";

/*Confirm that the current user has the applicant role*/
if (!verify_role('applicant')){
    $_SESSION['requestedPage'] = 'Location: appForm.php';
    $_SESSION['needsLoginApp'] = true;

    header('Location: login.php');
}

/*Only make the database call if the course_list session variable isn't already set*/
if (!isset($_SESSION['course_list'])) {
    require 'inc/pullCourseData.php';
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Blake McGillis - Application Form</title>
    <meta name="author" content="Blake McGillis">
    <meta name="date" content="March 21, 2015">
    <meta name="phase" content="7">
    <link rel="stylesheet" href="styles/styles.css"/>
    <link rel="stylesheet" href="styles/TAStyles.css"/>
    <script src="inc/js/jquery-1.11.2.min.js"></script>
    <script src="inc/js/appFormValidate.js"></script>
    <script src="inc/js/appFormFunctions.js"></script>


</head>
<body>
    <!--Main content-->
	<div id="contentWrapper">
        <div id="headingDiv">
            <h1 id="subHeading1">TA Application Form</h1>
        </div>
        
        <!--Sidebar-->
        <?php include ("inc/sidebar.php"); ?>
        
        <!--Form main-->
		<div id="mainContent">
            <?php
            if (isset($_SESSION['empty_element']) && $_SESSION['empty_element'] == true)
            {
                echo "<p class='red'>All Fields of the Form Must Be Filled Out</p>";
                $_SESSION['empty_element'] = false;
            }
            ?>
            <p class="sectionHeading">
                Teaching Assistant Requirements
            </p>
            <ol>
                <li>
                    <p>TAs are expected to spend 20 hours per week on TA duties. Students who are
                        TAing should plan to take a lighter load than normal. This is especially
                        true for students new to the University of Utah.
                    </p>

                    <p>TAs must allocate enough time to properly prepare for office hours,
                        including pre-reading and pre-doing the course assignments. When in charge
                        of a discussion section, TAs will be expected to
                        pre-prepare and practice their discussion.</p>

                    <p>TAs will be expected to finish assigned grading promptly and accurately
                        within 7 days of the due date. <span class="italics">Grading takes priority
                        over other academic endeavors.</span></p>
                </li>
                <li>
                    <p>TAs are expected to be in town the week before school starts.</p>
                </li>
                <li>
                    <p>And so on.</p>
                </li>
            </ol>

            
            <!-- Begin Form -->
            <form method="post" action="appDisplay.php" onsubmit="return validateForm();">
        
                <p class="sectionHeading">
                    TA Application Form
                </p>
                <p>
                    For which semester do you wish to be considered for a TA position?
                </p>
                <p>
                    <select id="semester" name="semester_select">
                        <option value="default">Select Semester</option>
                        <option selected="selected" value="Fall">Fall</option>
                        <option value="Spring">Spring</option>
                    </select>
                </p>

                <p>
                    For which year do you wish to be considered for a TA position?
                </p>
                <p>
                    <select id="year" name="year_select">
                        <option value="default">Select Year</option>
                        <option selected="selected" value="2015">2015</option>
                        <option value="2016">2016</option>
                        <option value="2017">2017</option>
                        <option value="2018">2018</option>
                    </select>
                </p>

                <p class="sectionHeading">
                    Personal and Contact Information
                </p>
                <p>
                    Please provide your 8-digit University ID (Not starting with a U, e.g. 00121212).
                </p>
                <p>
                    <?php
                    $uid = $_SESSION['uid'];
                    echo "<input type='text' size=10 id='uid' name='uid_text' value=$uid />"
                    ?>
                </p>
                <p>
                    Are you pursuing a degree from the School of Computing (CS, Computing)?
                </p>
                <p>
                    <input type="radio" id="degree_no" name="degree" value="0" checked/>
                    <label for="degree_no">No</label><br/>
                    <input type="radio" id="degree_yes" name="degree" value="1"/>
                    <label for="degree_yes">Yes</label><br/>
                </p>

                <p class="sectionLabel">Available Hours</p>
                <p>
                    How many hours will you be available to work at your TA position?  (Note:
                    Graduate students are hired to work 20 hours a week.  In general, undergraduate
                    students are also expected to work 20 hours a week.)
                </p>
                <p>
                    <input type="radio" id="hours_10_uGrad" name="hours" value="0" checked/>
                    <label for="hours_10_uGrad">10 - I am an Undergraduate Student</label><br/>
                    <input type="radio" id="hours_20_uGrad" name="hours" value="1"/>
                    <label for="hours_20_uGrad">20 - I am an Undergraduate Student</label><br/>
                    <input type="radio" id="hours_20_grad" name="hours" value="2"/>
                    <label for="hours_20_grad">20 - I am a Graduate Student</label><br/>
                </p>

                <!--Once we start doing JavaScript, I'd like to make it so the user can click
                a button to add another previously TA'd course, which will cause another course
                dropdown and textarea to be added (up to a maximum of 5 or so). But for now, I'll
                just have the two.

                Also, once JavaScript is being used, I'll possibly make it so the last dropdown and
                text field don't have to be filled out.
                -->
                <p class="sectionHeading">
                    Past TA Experience and Requested TA Positions
                </p>
                <p class="sectionLabel">
                    Prior TA Service
                </p>
                <p>
                    Select any course below which you have previously TA'd. For each course that you
                    select, please provide the following information:
                    the name of the instructor, and the semester and year when you TA'd.
                </p>
                <p>
                    Example:
                </p>
                <p>
                    <span class="italics">TA'd for Prof. Parker, Fall 2011</span>
                </p>
                <p>
                    (<span class="bold">Note:</span> If your browser supports it, you can expand the
                    textbox to have more room to write.)
                </p>
                <p>
                    Please select the courses you have previously TA'd for.
                </p>
                <div id="prev_ta1">
                    <select id="prev_ta_list1" class="p_margin" name="prev_ta_select1">
                        <option value="default">Select Previously TA'd Course</option>
                        <?php
                        /*Generating option rows*/
                        foreach($_SESSION['course_list'] as $course){
                            $number = $course->get_catNum();
                            $name = $course->get_title();

                            echo "<option value='$number'>CS $number - $name</option>";
                        }
                        ?>
                    </select>

                    <textarea id="prev_ta_textarea1" class="p_margin" name="prev_ta_text1" rows="4"
                       cols="75">You must select a class from the dropdown. And there must be text in this box</textarea>
                </div>

                <button type="button" id="add_prev_ta" class="block" onclick="addPrevTA(); return false;">Add another previously TA'd course</button>

                <p class="sectionLabel">
                    TA Application - Course Selection
                </p>
                <p>
                    Please select the courses below that you would like to TA for (and feel qualified
                    to TA for). In general, <span class="bold">you are expected to have taken a
                    course before you can TA for it</span>. Please consult the class schedule
                    to make sure you can attend the lecture and labs associated with a course before
                    selecting it.
                </p>
                <p>
                    In the textarea below your selection, please indicate the following information:
                </p>

                <ol>
                    <li>Course Number</li>
                    <li>If you have taken the course,</li>
                </ol>
                <ul>
                    <li>what grade you received</li>
                    <li>who the instructor was.</li>
                </ul>

                <p>
                    Note: <span class="italics">If you did your undergraduate work elsewhere, please
                    indicate that a class has been taken if you think one of your previous classes is
                    very similar to ours.</span>  Check the Computer Science Undergraduate Handbook
                    for course descriptions.  Please specify any course taken at another university.
                </p>
                <p>
                    Examples:
                </p>
                <p>
                    <span class="italics">Taken: A- : From Prof. de St. Germain</span> Or
                </p>
                <p>
                    <span class="italics">Taken at Utah State : B+ : From Prof. Smith</span>
                </p>
                <div id="req_ta1">

                    <select id="request_ta_list1" class="p_margin" name="request_ta_select1">
                        <option value="default">Select Course You'd Like to TA</option>
                        <?php
                        /*Generating option rows*/
                        foreach($_SESSION['course_list'] as $course){
                            $number = $course->get_catNum();
                            $name = $course->get_title();

                            echo "<option value='$number'>CS $number - $name</option>";
                        }
                        ?>
                    </select>

                    <textarea id="request_ta_textarea1" class="p_margin" name="request_ta_text1" rows="4"
                      cols="75">You must select a class from the dropdown. And there must be text in this box</textarea>

                </div>

                <button type="button" id="add_req_ta" class="block" onclick="addReqTA(); return false;">Add another course to TA</button>

                <p class="sectionLabel">
                    Additional Details
                </p>
                <p>
                    Please include a paragraph or two describing:
                </p>
                    <ol>
                        <li>The strengths and abilities you would bring to the job.</li>
                        <li>What programming languages and tools you are familiar with.</li>
                        <li>Any past experience not describe above.</li>
                        <li>Any recommendations from faculty asking for you specifically as a TA.</li>
                        <li>Any additional information you think would help us choose you as a TA.</li>
                    </ol>
                <p>Additionally, please feel free to discuss any prior TA experience outside of the
                    Computer Science department or the University of Utah. Just let us know some
                    information about what you did and where you did it.<br/><br/>
                </p>
                <p>
                    <textarea id="add_info" name="add_info_textarea" rows="10" cols="75">Enter some text</textarea>
                </p>

                <p class="sectionHeading">
                    Questions for Graduate Students
                </p>
                <p class="sectionLabel">
                    Promised Aid
                </p>
                <p>
                    Were you promised financial aid for this coming semester as part of your acceptance
                    package?
                </p>
                <p>
                    <input type="radio" id="aid_no" name="aid" value="0" checked/>
                    <label for="aid_no">No</label><br/>
                    <input type="radio" id="aid_yes" name="aid" value="1"/>
                    <label for="aid_yes">Yes</label><br/>
                </p>


                <p>
                    Are you an international student?
                </p>
                <p>
                    <input type="radio" id="intl_no" name="intl" value="0" checked/>
                    <label for="degree_no">No</label><br/>
                    <input type="radio" id="intl_yes" name="intl" value="1"/>
                    <label for="degree_yes">Yes</label><br/>
                </p>

                <div id="intl" class="dispNone">
                    <p class="sectionHeading">
                        Questions for International Graduate Students
                    </p>
                    <p>
                        What is your country of origin (Where you were born/raised)?
                    </p>
                    <p>
                        <input type="text" size=10 id="origin" name="origin_text" value="N/A"/>
                    </p>
                    <p class="sectionLabel">ITA Status</p>
                    <p>
                        Please indicate your ITA status.
                    </p>
                    <p>
                        Note: For information about the ITA, please speak to the graduate advisor in the
                        SoC front office.  All international students must pass the ITA workshop in order
                        to be considered as Teaching Assistants. Being enrolled in the ITA workshop does
                        not guarantee a TA position.
                    </p>
                    <p>
                        <input type="radio" id="ita_none" name="ita" value="0" checked/>
                        <label for="ita_none">No answer</label><br/>
                        <input type="radio" id="ita_passed" name="ita" value="1"/>
                        <label for="ita_passed">I have taken and passed the ITA workshop.</label><br/>
                        <input type="radio" id="ita_enrolled" name="ita" value="2"/>
                        <label for="ita_enrolled">I am currently enrolled in the ITA workshop.</label><br/>
                        <input type="radio" id="ita_work_yes" name="ita" value="3"/>
                        <label for="ita_work_yes">I would like to attend the ITA workshop.</label><br/>
                        <input type="radio" id="ita_work_no" name="ita" value="4"/>
                        <label for="ita_work_no">I do not plan to attend the ITA workshop.</label><br/>
                    </p>
                </div>
                <p>
                <input type="submit" id="send_form" value="Submit"/>
                </p>
        <!-- End Form -->
        </form>
		</div>
	</div>
</body>
</html>