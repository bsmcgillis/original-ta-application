<!--
Author: Blake McGillis
Date: March 21, 2015
Phase: 7
-->

<?php
/*
 * Called by virtually all page files. Displays the available pages for the user as well as the user's name.
 */

require_once "helper/functions.php";

//Remember what the current page is in case a user is not logged in but still clicks log out
$_SESSION['currentPage'] = basename($_SERVER['PHP_SELF']);

/*Set up the user variable with the user's session*/
$theUser = $_SESSION['user'];
?>

<div id="sidebar">
    <p class="sectionLabel">Project</p>
    <a href="TA.html" class="sidebar">TA 7</a>


    <p class="sectionLabel">Application Links</p>
    <p class="subsectionLabel">Main</p>
    <p><a href="projectMain.php" class="sidebar">TA Application Home</a></p>
    <p><a href="README.php" class="sidebar">README</a></p>
    <?php
    if (verify_role('applicant')) {
        ?>
        <p class="subsectionLabel">Applicant</p>
        <p><a href="appDisplay.php" class="sidebar">Applicant Home</a></p>
        <p><a href="appForm.php" class="sidebar">Application Form</a></p>
    <?php
    }
    if (verify_role('administrator')) {
        ?>
        <p class="subsectionLabel">Administrator</p>
        <p><a href="adminCoursList.php" class="sidebar">Admin Course List</a></p>
    <?php
    }
    ?>

    <p class="sectionLabel">Account</p>
    <?php
    if (isset($_SESSION['user'])){
        echo "<p class='sidebar'>{$theUser->get_full_name()}</p>";
    }
    else {
        ?>
        <p><a href="login.php" class="sidebar">Log In</a></p>
        <p><a href="register.php" class="sidebar">Register Account</a></p>
    <?php
    }
    ?>
    <p><a href="logout.php" class="sidebar">Log Out</a></p>
</div>