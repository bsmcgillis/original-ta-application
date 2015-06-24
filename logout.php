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

//If the user is logged in, clear their session variables and send them to projectMain
if (isset($_SESSION['user'])){
    session_unset();

    header('Location: projectMain.php');
}
//If the user is not logged in, send them back to the page they were on
else {
    header('Location: ' . $_SESSION['currentPage']);
}
?>



