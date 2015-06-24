<!--
Author: Blake McGillis
Date: March 21, 2015
Phase: 7
-->

<?php

/*
 * Verify that the user's role array contains the specified role
 */
function verify_role($role)
{
    if (isset($_SESSION['user'])){
        $theUser = $_SESSION['user'];
        if (in_array($role, $theUser->get_role_array())){
            return true;
        }
        else{
            return false;
        }
    }
    else {
        return false;
    }
}

/*
 * Searches through an array of application objects and finds the application object that matches the
 * specified timestamp
 */
function find_app_by_time($appArray, $time_stamp)
{
    foreach ($appArray as $app)
    {
        if ($app->get_time_stamp() == $time_stamp)
        {
            return $app;
        }
    }

    echo "Something went horribly wrong in find_app_by_time";
}

/*
 * Searches through an array of course objects and finds the course object that matches the
 * specified catNum
 */
function find_course_by_catNum($courseArray, $catNum)
{
    foreach ($courseArray as $course)
    {
        if ($course->get_catNum() == $catNum)
        {
            return $course;
        }
    }

    //echo "Course " . $catNum . " not found in find_course_by_catNum<br/>";
    return null;
}

/*
 * Unset all session variables except requestedPage
 */
function clear_session_variables()
{
    if (isset($_SESSION['requestedPage']))
    {
        $requestedPage = $_SESSION['requestedPage'];
    }

    session_unset();

    if (strlen($requestedPage) > 0)
    {
        $_SESSION['requestedPage'] = $requestedPage;
    }
}

/*
 * Takes in an integer-value recommendation level for a TA applicant and returns the corresponding text for
 * that recommendation level
 */
function get_recommendation( $value )
{
    if ($value == 1)
    {
        return "Possible";
    }
    else if ($value == 2)
    {
        return "Recommended";
    }
    else if ($value == 3)
    {
        return "Desired";
    }
    else if ($value == 4)
    {
        return "Confirmed";
    }

    return "Not Interested";
}

/*
 * A function used to know which select option to mark as "selected" in a dropdown list
 */
function option_selected( $got, $want )
{
    if ($got == $want)
    {
        return true;
    }

    return false;
}

?>