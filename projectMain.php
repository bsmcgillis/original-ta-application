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
    <title>Blake McGillis - TA Application Main</title>
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
        <h1 id="subHeading1">TA Application</h1>
    </div>

    <!--Sidebar included using php-->
    <?php include ("inc/sidebar.php"); ?>

    <div id="mainContent">
        <p class="sectionHeading">
            TA Application Main Page
        </p>
        <p class="center">
            Welcome to the TA University of Utah TA Application main page.
        </p>
        <p class="center">
            Please log in and select the page you would like to visit.<br /><br />
        </p>
        <p class="center">
            If you do not have an account, please use the Create Account link found in the sidebar.
        </p>
    </div>
</div>
</body>
</html>