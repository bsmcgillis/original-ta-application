<!--
Author: Blake McGillis
Date: March 21, 2015
Phase: 7
-->


<?php


/**
 * This file pulls all of the courses from the selected semester and year and then adds
 * those courses to the database
 *
 */


/**
 * Get the semester and year if this was reached via POST
 */
$semYearCode = "";
$chosenSemester = "";
$chosenYear = "";

if(isset($_POST['year_select']) && isset($_POST['semester_select']))
{
    if(!empty($_POST['year_select']))
    {
        $semYearCode = $_POST['year_select'];
        $chosenYear = $_POST['year_select'];
    }
    else
    {
        $semYearCode = "15";
    }

    if(!empty($_POST['semester_select']))
    {
        $semYearCode .= $_POST['semester_select'];
        $chosenSemester = $_POST['semester_select'];
    }
    else
    {
        $semYearCode .= "4";
    }
}
//If this page was not reached via POST, just use fall of 2015
else
{
    $semYearCode = "158";
}

require 'helper/dbConfig.php';

/**
 * Open a socket to the acs web page and pull all the HTML
 */

// Open the socket
$fp = fsockopen("128.110.208.39", 80, $errno, $errstr, 500);

// Prepare the GET request
$out = "GET /uofu/stu/scheduling?term=1" . $semYearCode . "&dept=CS&classtype=g HTTP/1.1\r\n";
$out .= "Host: www.acs.utah.edu\r\n";
$out .= "Connection: Close\r\n\r\n";

// Send the GET request
fwrite($fp, $out);

// Make sure the connection was successful
if (!$fp)
{
    $content = " offline ";
    echo $errno;
    echo $errstr;
}
else
{
    //Store the HTML in $page
    $page = "";
    while (!feof($fp))
    {
        $page .= fgets($fp, 1000);
    }

    // Close the socket
    fclose($fp);

    /**
     * Now pull the table we need from the HTML and use the table data to populate an array of course objects
     */

    //Create an array to hold all of the course objects
    $courseArray = Array();

    // Create a DOMDocument object to hold the HTML
    $doc = new DOMDocument();

    // Get rid of any blank spaces in $page
    $page = str_replace('&nbsp;', '', $page);

    // Load the HTML into the DOMDocument object
    $doc->loadHTML( $page );

    // Get all the tables from the page and grab the rows from the fourth table
    $table = $doc->getElementsByTagName('table');
    $rows = $table->item(4)->getElementsByTagName('tr');


    $lastCatNum = "";

    // Loop through each row
    foreach ($rows as $row)
    {

        //Get all of the cells for this row
        $cells = $row->getElementsByTagName('td');

        //Ignore any non-full rows
        if($cells->length < 10)
        {
            continue;
        }

        //Ignore any lecture rows or blank rows
        $thisComponent = $cells->item(5)->nodeValue;
        if($thisComponent == "Laboratory" || $thisComponent == "Discussion" || $thisComponent == ""
            || $thisComponent == "Component")
        {
            continue;
        }

        // Grab the catNum from this row
        $thisCatNum = $cells->item(3)->nodeValue;

        //Make sure this isn't a duplicate or blank row
        if ($thisCatNum == "" || $thisCatNum == $lastCatNum)
        {
            continue;
        }
        else
        {
            $lastCatNum = $thisCatNum;
        }

        //Create a course object and populate it with the course information
        $currentCourse = new Course();

        //Loop through the cells of the row
        for ($i = 0; $i < $cells->length; $i++)
        {
            if($i % 16 == 3)
            {
                $currentCourse->set_catNum(trim($cells->item($i)->nodeValue));
            }
            if($i % 16 == 5)
            {
                $currentCourse->set_component(trim($cells->item($i)->nodeValue));
            }
            if($i % 16 == 6)
            {
                $currentCourse->set_units(trim($cells->item($i)->nodeValue));
            }
            if($i % 16 == 9)
            {
                $currentCourse->set_time(trim($cells->item($i)->nodeValue));
            }
            if($i % 16 == 10)
            {
                $currentCourse->set_location(trim($cells->item($i)->nodeValue));
            }
            if($i % 16 == 12)
            {
                $currentCourse->set_instructor(trim($cells->item($i)->nodeValue));
            }
        }

        //Now add this course object to the array
        $courseArray[] = $currentCourse;

    }

}

/**
 * Okay, now that the first chunk of information has been added to the courses, it's time to add the rest
 */



// Open another socket to a different acs web page
$fp = fsockopen("128.110.208.39", 80, $errno, $errstr, 500);


// Prepare the GET request again to pull the data.
$out = "GET /uofu/stu/scheduling/crse-info?term=1" . $semYearCode . "&subj=CS HTTP/1.1\r\n";
$out .= "Host: www.acs.utah.edu\r\n";
$out .= "Connection: Close\r\n\r\n";

// Send the GET request
fwrite($fp, $out);

// Make sure it worked
if (!$fp)
{
    $content = " offline ";
    echo $errno;
    echo $errstr;
}
else
{
    // Pull the page and store it in a variable
    $page = "";
    while (!feof($fp))
    {
        $page .= fgets($fp, 1000);
    }

    // Close the socket
    fclose($fp);

    $doc = new DOMDocument();

    // Get rid of any blank spaces in $page
    $page = str_replace('&nbsp;', '', $page);

    // Load the HTML into the DOMDocument object
    $doc->loadHTML( $page );

    // Get all of the tables out of the HTML and grab the first one
    $table = $doc->getElementsByTagName('table');
    $rows = $table->item(0)->getElementsByTagName('tr');


    $ignoreFirst = false;
    $lastCatNum = "";

    // Loop through the rows of the table
    foreach ($rows as $row)
    {
        // Ignore the first row
        if(!$ignoreFirst)
        {
            $ignoreFirst = true;
            continue;
        }

        // Grab all the cells from this row
        $cells = $row->getElementsByTagName('td');

        // Grab the catNum from this row
        $thisCatNum = $cells->item(2)->nodeValue;

        //Make sure this isn't a duplicate or blank row
        if ($thisCatNum == "" || $thisCatNum == $lastCatNum)
        {
            continue;
        }
        else
        {
            $lastCatNum = $thisCatNum;
        }

        // Use the catNum to find the course object for this course
        $currentCourse = find_course_by_catNum($courseArray, $thisCatNum);

        // If no matching course was found, continue
        if($currentCourse == null)
        {
            continue;
        }

        // If the $currentCourse object is not null, populate it with the rest of the information
        for ($i = 0; $i < $cells->length; $i++)
        {

            if($i % 8 == 4)
            {
                $currentCourse->set_title(trim($cells->item($i)->nodeValue));
            }
            if($i % 8 == 5)
            {
                $currentCourse->set_enroll_cap(trim($cells->item($i)->nodeValue));
            }
            if($i % 8 == 6)
            {
                $currentCourse->set_curr_enroll(trim($cells->item($i)->nodeValue));
            }
            if($i % 8 == 7)
            {
                $currentCourse->set_seats_avail(trim($cells->item($i)->nodeValue));
            }
        }
    }

}

/**
 * Now, populate the database with the new course objects
 */
try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbName;port=$port", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /* First, delete the courses currently in the database */
    $query  = "DELETE FROM Pulled_Courses";

    // Prepare and execute the statement
    $stmt = $dbh->prepare($query);
    $stmt->execute();

    /* Next add all of the new courses to the database */
    $query = "INSERT INTO Pulled_Courses (cat_num, title, enroll_cap, curr_enroll, seats_avail, component, units,
              time, location, instructor) VALUES";

    $valueArray = [];

    //Loop through courseArray
    for ($i = 0; $i < count($courseArray); $i++) {

        //pull the currentCourse from the array
        $currentCourse = $courseArray[$i];

        //Push all the course variables onto the valueArray
        array_push($valueArray, $currentCourse->get_catNum(), $currentCourse->get_title(),
            $currentCourse->get_enroll_cap(), $currentCourse->get_curr_enroll(), $currentCourse->get_seats_avail(),
            $currentCourse->get_component(), $currentCourse->get_units(), $currentCourse->get_time(),
            $currentCourse->get_location(), $currentCourse->get_instructor());

        // Add the wildcards to the query to bind later
        if ($i > 0) {
            $query = $query . ",";
        }
        $query = $query . " (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }

    //Prepare the query
    $stmt = $dbh->prepare($query);

    // Now bind all of the values
    for ($i = 0; $i < count($valueArray); $i++) {

        //Grab this value out of the valueArray
        $currentValue = $valueArray[$i];

        //Bind the wildcard with it
        $stmt->bindValue(($i + 1), $currentValue, PDO::PARAM_STR);

    }

    $stmt->execute();
}
catch (PDOException $e)
{
    print "Error!:" . $e->getMessage() . "<br/>";
    die();
}

?>
