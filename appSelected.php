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

require_once "inc/helper/functions.php";

if (!verify_role('applicant')){
    $_SESSION['requestedPage'] = 'Location: appForm.php';
    $_SESSION['needsLoginApp'] = true;

    header('Location: login.php');
}

/*Find the application selected by the user*/
$selectedApp = find_app_by_time($_SESSION['applications'], $_GET['time']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Blake McGillis - Application Display</title>
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
        <h1 id="subHeading1">Your TA Application</h1>
    </div>

    <!--Sidebar included using php-->
    <?php include ("inc/sidebar.php"); ?>

    <!--Form main has quite a few sections, so I've tried to format them readably with white
        space-->
    <div id="mainContent">

        <p class="sectionHeading">
            Application Results
        </p>
        <p class="sectionHeadingText">
            Here are the results of your TA Application. There would probably be some additional
            information here about how to follow up on your TA application. And how you could
            change some of the information if you needed to.
        </p>

        <p class="sectionHeading">
            TA Application Form
        </p>
        <p>
            For which semester do you wish to be considered for a TA position?
        </p>
        <p class="displayPage" id="semester">
            <?php echo $selectedApp->get_semester() ?>
        </p>

        <p>
            For which year do you wish to be considered for a TA position?
        </p>
        <p class="displayPage" id="year">
            <?php echo $selectedApp->get_year() ?>
        </p>

        <p class="sectionHeading">
            Personal and Contact Information
        </p>
        <p>
            Please provide your 8-digit University ID (Not starting with a U, e.g. 00121212).
        </p>
        <p class="displayPage" id="uid">
            <?php echo $selectedApp->get_uid() ?>
        </p>
        <p>
            Are you pursuing a degree from the School of Computing (CS, Computing)?
        </p>
        <p class="displayPage" id="degree">
            <?php
            if ($selectedApp->get_degree() == 1){
                echo "Yes";
            }
            else {
                echo "No";
            }
            ?>
        </p>

        <p class="sectionLabel">Available Hours</p>
        <p>
            How many hours will you be available to work at your TA position?  (Note:
            Graduate students are hired to work 20 hours a week.  In general, undergraduate
            students are also expected to work 20 hours a week.)
        </p>
        <p class="displayPage" id="avail_hours">
            <?php
            if ($selectedApp->get_avail_hours() == 0){
                echo "10";
            }
            else {
                echo "20";
            }
            ?>
        </p>

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
        <!-- Insert previously TA'd courses info -->
        <?php
        foreach ($selectedApp->get_prev_ta_array() as $prev) {
            $prevDept = $prev->get_department();
            $prevNumber = $prev->get_number();
            $prevName = $prev->get_name();
            $prevExper = $prev->get_ta_experience();

            echo "<p class='displayPage'>$prevDept $prevNumber - $prevName </p><p></p>
                                <textarea rows='4' cols='50' readonly
                                class='displayPageText'>$prevExper</textarea><p></p>";
        }
        ?>
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
        <p>
        <ol>
            <li>Course Number
            <li>If you have taken the course,</li>
        </ol>
        <ul>
            <li>what grade you received</li>
            <li>who the instructor was.</li>
        </ul>
        </p>
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
        <!-- Insert previously TA'd courses info -->
        <?php
        foreach ($selectedApp->get_request_ta_array() as $req) {
            $reqDept = "CS";
            $reqNumber = $req->get_number();
            $reqName = $req->get_name();
            $reqExper = $req->get_ta_info();

            echo "<p class='displayPage'>$reqDept $reqNumber - $reqName </p><p></p>
                                <textarea rows='4' cols='50' readonly
                                class='displayPageText'>$reqExper</textarea><p></p>";
        }
        ?>

        <p class="sectionLabel">
            Additional Details
        </p>
        <p>
            Please include a paragraph or two describing:
        </p>
        <p>
        <ol>
            <li>The strengths and abilities you would bring to the job.</li>
            <li>What programming languages and tools you are familiar with.</li>
            <li>Any past experience not describe above.</li>
            <li>Any recommendations from faculty asking for you specifically as a TA.</li>
            <li>Any additional information you think would help us choose you as a TA.
            </li>
        </ol>
        </p>
        <p>Additionally, please feel free to discuss any prior TA experience outside of the
            Computer Science department or the University of Utah. Just let us know some
            information about what you did and where you did it.<br/><br/>
        </p>
        <?php
        echo "<textarea rows='4' cols='50' readonly
                    class='displayPageText' id='addit_details'>{$selectedApp->get_addit_details()}</textarea><p></p>"
        ?>
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
        <p class="displayPage" id="aid">
            <?php
            if ($selectedApp->get_promised_aid() == 1){
                echo "Yes";
            }
            else {
                echo "No";
            }
            ?>
        </p>

        <?php
        if($selectedApp->get_grad_origin() == "N/A")
        {
            echo "<div id='intl' class='dispNone'>";
        }
        else
        {
            echo "<div id='intl'>";
        }
        ?>
            <p class="sectionHeading">
                Questions for International Graduate Students
            </p>
            <p>
                What is your country of origin (Where you were born/raised)?
            </p>
            <p class="displayPage" id="origin">
                <?php echo $selectedApp->get_grad_origin() ?>
            </p>
            <p class="sectionLabel">
                ITA Status
            </p>
            <p>
                Please indicate your ITA status.
            </p>
            <p>
                Note: For information about the ITA, please speak to the graduate advisor in the
                SoC front office.  All international students must pass the ITA workshop in order
                to be considered as Teaching Assistants. Being enrolled in the ITA workshop does
                not guarantee a TA position.
            </p>
            <p class="displayPage" id="ita">
                <?php
                if ($selectedApp->get_grad_ita() == 0){
                    echo "No answer";
                }
                elseif ($selectedApp->get_grad_ita() == 1){
                    echo "I have taken and passed the ITA workshop.";
                }
                elseif ($selectedApp->get_grad_ita() == 2){
                    echo "I am currently enrolled in the ITA workshop.";
                }
                elseif ($selectedApp->get_grad_ita() == 3){
                    echo "I would like to attend the ITA workshop.";
                }
                else {
                    echo "I do not plan to attend the ITA workshop.";
                }
                ?>
            </p>
        </div>
    </div>
</div>
</body>
</html>