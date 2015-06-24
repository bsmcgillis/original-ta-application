<!--
Author: Blake McGillis
Date: March 21, 2015
Phase: 7
-->

<?php
/*
 * Called by appDisplay.php. Validates that application form information has been filled out. Then
 * inserts it to the database.
 */


/*
 * NOTE: The final query (the one that adds this applicant and their requested TA courses to the TA_Applicants
 * table) doesn't check to see if this student already has an application going for this course. So I may end
 * up with duplicates.
 */


/*Contains database variables*/
require 'helper/dbConfig.php';


/* Painstakingly make sure that all elements of the form have been filled out */

//Create an array that holds the dropdown boxes that will need to be checked for completeness
$requiredDropdowns = array('semester_select', 'year_select');

//Loop through all existing prev_ta_select dropdowns and add them to the array
for ($i = 1; $i < 6; $i++)
{
    $selector = "prev_ta_select" . $i;
    if (isset ($_POST[$selector]))
    {
        $requiredDropdowns[] = $selector;
    }
}

//Loop through all existing request_ta_select dropdowns and add them to the array
for ($i = 1; $i < 6; $i++)
{
    $selector = "request_ta_select" . $i;
    if (isset ($_POST[$selector]))
    {
        $requiredDropdowns[] = $selector;
    }
}

//Create an array that holds the textfields that will need to be checked for completeness
$requiredFields = array('uid_text', 'add_info_textarea', 'origin_text');

//Loop through all existing prev_ta_text textfields and add them to the array
for ($i = 1; $i < 6; $i++)
{
    $selector = "prev_ta_text" . $i;
    if (isset ($_POST[$selector]))
    {
        $requiredFields[] = $selector;
    }
}

//Loop through all existing request_ta_text textfields and add them to the array
for ($i = 1; $i < 6; $i++)
{
    $selector = "request_ta_text" . $i;
    if (isset ($_POST[$selector]))
    {
        $requiredFields[] = $selector;
    }
}

/* Now that we have all the fields and dropdowns, we can make sure that they aren't empty or set to default */
$emptyElement = false;
foreach($requiredFields as $field)
{
    if (empty($_POST[$field]))
    {
        $emptyElement = true;
        break;
    }
}

//If none of the textfields was empty, check the dropdowns
if (!$emptyElement)
{
    foreach($requiredDropdowns as $dropdown)
    {
        if ($_POST[$dropdown] == "default")
        {
            $emptyElement = true;
            break;
        }
    }
}

//If an element was empty, tell the user
if ($emptyElement)
{
    $_SESSION['empty_element'] = true;
    header('Location: appForm.php');
}
else {

    /* If all the POST data checks out, start sending it to the database*/
    try {
        $dbh = new PDO("mysql:host=$host;dbname=$dbName;port=$port", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        /* Insert the data*/
        $query = "INSERT INTO Application (semester, year, uID, degree, avail_hours, addit_details,
            promised_aid, grad_origin, grad_ita)
            VALUES (:semester, :year, :uid, :degree, :hours, :add_info_text, :aid,
             :origin_text, :ita)";

        $stmt = $dbh->prepare($query);

        /* Bind all these variables */
        $stmt->bindParam(':semester', $_POST['semester_select'], PDO::PARAM_STR);
        $stmt->bindParam(':year', intval($_POST['year_select']), PDO::PARAM_INT);
        $stmt->bindParam(':uid', $_SESSION['uid'], PDO::PARAM_STR);
        $stmt->bindParam(':degree', $_POST['degree'], PDO::PARAM_STR);
        $stmt->bindParam(':hours', intval($_POST['hours']), PDO::PARAM_INT);
        $stmt->bindParam(':add_info_text', htmlspecialchars($_POST['add_info_textarea']), PDO::PARAM_STR);
        $stmt->bindParam(':aid', intval($_POST['aid']), PDO::PARAM_INT);
        $stmt->bindParam(':origin_text', htmlspecialchars($_POST['origin_text']), PDO::PARAM_STR);
        $stmt->bindParam(':ita', intval($_POST['ita']), PDO::PARAM_INT);

        $stmt->execute();


        //Pull the timestamp from the query that was just sent to the database to tie it with the courses info
        $query = "SELECT time_stamp FROM Application WHERE uID = :uid ORDER BY time_stamp DESC LIMIT 1";

        $stmt = $dbh->prepare($query);

        $stmt->bindParam(':uid', $_SESSION['uid'], PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetch();

        $date = $result['time_stamp'];


        /* Insert Previously TAd Courses Data with the same timestamp from the application insert */
        $query = "INSERT INTO Prev_TA_Courses (uID, department, number, ta_experience, time_stamp) VALUES";
        $department = "CS";
        $uid = $_SESSION['uid'];
        $valueArray = [];

        /* Loop through. If a prev_ta_select element exists, add it and the associated prev_ta_text to the database */
        for ($i = 1; $i < 10; $i++) {
            $selector = "prev_ta_select" . $i;
            $selectorText = "prev_ta_text" . $i;
            if (isset ($_POST[$selector])) {

                array_push($valueArray, $uid, $department, intval($_POST[$selector]),
                    htmlspecialchars($_POST[$selectorText]), $date);

                if ($i > 1) {
                    $query = $query . ",";
                }
                $query = $query . " (?, ?, ?, ?, ?)";
            }
        }

        $stmt = $dbh->prepare($query);


        /* Now, bind all of these things going into the database*/

        //Create a subcounter because every 3rd array item needs to be treated differently because it's an int
        $subCounter = 1;

        /*
         * For each iteration of this loop, I'll need to bind 4 values. I use
         * bindValue instead of bindParameter here because the bindParameter uses a reference to the
         * value which is constantly being replaced in the loop, while bindValue appears to just make
         * a copy of the value at that time
         */
        for ($i = 0; $i < count($valueArray); $i++) {
            $currentValue = $valueArray[$i];

            if ($subCounter == 3) {
                $stmt->bindValue(($i + 1), $currentValue, PDO::PARAM_INT);
            } else {
                $stmt->bindValue(($i + 1), $currentValue, PDO::PARAM_STR);
            }

            //Increment subcounter. If it's 5 set it back to 1
            if ($subCounter == 5) {
                $subCounter = 1;
            } else {
                $subCounter = $subCounter + 1;
            }
        }

        $stmt->execute();


        /*Insert Request TA Courses Data*/
        $query = "INSERT INTO Request_TA_Courses (uID, number, ta_info, time_stamp) VALUES";
        $uid = $_SESSION['uid'];
        $valueArray = [];
        for ($i = 1; $i < 10; $i++) {
            $selector = "request_ta_select" . $i;
            $selectorText = "request_ta_text" . $i;
            if (isset ($_POST[$selector])) {

                array_push($valueArray, $uid, intval($_POST[$selector]), htmlspecialchars($_POST[$selectorText]), $date);

                if ($i > 1) {
                    $query = $query . ",";
                }
                $query = $query . " (?, ?, ?, ?)";

            }
        }

        $stmt = $dbh->prepare($query);

        /*Bind query items*/
        //Use subcounter again because every 2nd array item needs to be treated differently
        $subCounter = 1;

        for ($i = 0; $i < count($valueArray); $i++) {

            $currentValue = $valueArray[$i];

            if ($subCounter == 2) {
                $stmt->bindValue(($i + 1), $currentValue, PDO::PARAM_INT);
            } else {
                $stmt->bindValue(($i + 1), $currentValue, PDO::PARAM_STR);
            }

            //Increment subcounter. If it's 4 set it back to 1
            if ($subCounter == 4) {
                $subCounter = 1;
            } else {
                $subCounter = $subCounter + 1;
            }
        }
        $stmt->execute();



        /* I'll now need to query the database yet again to add this applicant to the TA_Applicants table. */
        $query = "INSERT INTO TA_Applicants (uID, class_request, recommend, semester, year) VALUES";
        $uid = $_SESSION['uid'];
        $valueArray = [];
        for ($i = 1; $i < 10; $i++) {
            $selector = "request_ta_select" . $i;
            $selectorText = "request_ta_text" . $i;
            if (isset ($_POST[$selector])) {

                array_push($valueArray, $uid, intval($_POST[$selector]), 0, strtolower($_POST['semester_select']),
                    intval($_POST['year_select']));

                if ($i > 1) {
                    $query = $query . ",";
                }
                $query = $query . " (?, ?, ?, ?, ?)";

            }
        }

        $stmt = $dbh->prepare($query);

        /*Bind query items*/
        
        for ($i = 0; $i < count($valueArray); $i++) {

            $currentValue = $valueArray[$i];

            //If the value consists of all digits, send it as an int
            if (ctype_digit($currentValue)){
                $stmt->bindValue(($i + 1), $currentValue, PDO::PARAM_INT);
            }
            //Otherwise, send it as a string
            else {
                $stmt->bindValue(($i + 1), $currentValue, PDO::PARAM_STR);
            }
        }
        $stmt->execute();


        $dbh = null;
    }
    catch (PDOException $e)
    {
    print "Error!:" . $e->getMessage() . "<br/>";
    die();
    }
}
?>