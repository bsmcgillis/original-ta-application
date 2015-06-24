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

include 'inc/registration.php';
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
    <script src="inc/js/jquery-1.11.2.min.js"></script>
    <script src="inc/js/registerValidate.js"></script>
</head>
<body>
<!--Main content-->
<div id="contentWrapper">
    <div id="headingDiv">
        <h1 id="subHeading1">Register Your Account</h1>
    </div>

    <!--Sidebar included using php-->
    <?php include ("inc/sidebar.php"); ?>

    <div id="mainContent">
        <p class="sectionHeading">
            TA Application Account Registration Page
        </p>
        <p class="center">
            Please enter the requested information to create your account.
        </p>
        <div class="center">
            <p class="somePadding italics whiteBox inBlock">Please note that passwords must be at least 8 characters long, <br />
            contain at least one number, and contain at least one uppercase letter.</p>
        </div>

        <!--
            These forms look pretty bad. I should do a two-column thing in here so the
            text and fields all line up
        -->

        <div class="smallForm" id="register">
            <form class="sForm" method="post" action="" onsubmit="return validateForm();">
                <?php
                    //Since I'll eventually have many of these, I should just include a file here to do this
                    if ($_SESSION['bad_username']){
                        echo "<p class='red'>That username already exists</p>";
                    }
                ?>
                <p class="inline">First Name: </p>
                <input type="text" class="inputs" id="first_name" name="first_name" required/>
                <p></p>
                <p class="inline">Last Name: </p>
                <input type="text" class="inputs" id="last_name" name="last_name" required/>
                <p></p>
                <p class="inline">Username: </p>
                <input type="text" class="inputs" id="username" name="username" required/>
                <p></p>
                <p class="inline">University ID: </p>
                <input type="text" class="inputs" id="uid" name="uid" required/>
                <p></p>
                <p class="inline">Password: </p>
                <input type="password" class="inputs" id="pass1" name="pass1" required/>
                <p></p>
                <p class="inline">Password Again: </p>
                <input type="password" class="inputs" id="pass2" name="pass2" required/>

                <p class="sForm">
                    <input type="submit" value="Register"/>
                </p>
            </form>
        </div>
    </div>
</div>
</body>
</html>