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

require_once "inc/helper/functions.php";
require 'inc/authentication.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Blake McGillis - TA Application Login</title>
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
        <h1 id="subHeading1">Log In</h1>
    </div>

    <!--Sidebar included using php-->
    <?php include ("inc/sidebar.php"); ?>

    <div id="mainContent">
        <p class="sectionHeading">
            TA Application Log In Page
        </p>
        <?php
        if (isset($_SESSION['registered']) && $_SESSION['registered']){
            ?>
            <p class='center green'>Your account has been created. Please log in!</p>
        <?php
        }
        else if (isset($_SESSION['badUserPass']) && $_SESSION['badUserPass']){
            ?>
            <p class='center red'>Username or Password is incorrect. Try again.</p>
        <?php
        }
        else if (isset($_SESSION['needsLoginApp']) && $_SESSION['needsLoginApp']){
            unset($_SESSION['needsLoginApp']);
            ?>
            <p class='center red'>You must be logged in as an applicant to access that page.<br />
            Please select a page from the Menu.</p>
        <?php
        }
        else if (isset($_SESSION['needsLoginInst']) && $_SESSION['needsLoginInst']){
            unset($_SESSION['needsLoginInst']);
            ?>
            <p class='center red'>You must be logged in as an instructor to access that page.<br />
                Please select a page from the Menu.</p>
        <?php
        }
        else if (isset($_SESSION['needsLoginAdmin']) && $_SESSION['needsLoginAdmin']){
            unset($_SESSION['needsLoginAdmin']);
            ?>
            <p class='center red'>You must be logged in as an administrator to access that page.<br />
                Please select a page from the Menu.</p>
        <?php
        }
        else {
        ?>
        <p class='center'>Please enter your username and password to log in.</p>
        <p class='center'>If you do not have an account, please use the Register Account link found in the sidebar.</p>
        <?php
        }
        ?>

        <!--
            These forms look pretty bad. I should do a two-column thing in here so the
            text and fields all line up
        -->

        <div class="smallForm" id="login">
            <form class="sForm" method="post" action="">
                <p class="inline">Username: </p>
                <input type="text" class="inputs" size=10 id="username" name="username" required/>
                <p></p>
                <p class="inline">Password: </p>
                <input type="password" class="inputs" size=10 id="password" name="password" required/>

                <p class="sForm">
                    <input type="submit" id="log_in" value="Log In"/>
                </p>
            </form>
        </div>
    </div>
</div>
</body>
</html>