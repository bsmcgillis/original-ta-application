<!--
Author: Blake McGillis
Date: March 21, 2015
Phase: 7
-->

<?php
/*
 * Called by register.php. Ensures that registration data is correct and inserts it into the database
 */

/*Contains database variables*/
require 'helper/dbConfig.php';


//Make sure this page was reached via POST and make sure all fields are filled out
if (isset ($_POST['first_name'], $_POST['last_name'], $_POST['username'], $_POST['uid'],
    $_POST['pass1'], $_POST['pass2'])) {

    if (empty($_POST['first_name'])) {
        echo "No first name entered";
    } else if (empty($_POST['last_name'])) {
        echo "No last name entered";
    } else if (empty($_POST['username'])) {
        echo "No username entered";
    } else if (empty($_POST['uid'])) {
        echo "No uid entered";
    } else if (empty($_POST['pass1'])) {
        echo "No password entered";
    } else if (empty($_POST['pass2'])) {
        echo "Password was not entered again";
    } else {
        if ($_POST['pass1'] != $_POST['pass2']) {
            echo "Passwords did not match";
        } //If everything looks good, send the information to the database
        else {

            try {
                $dbh = new PDO("mysql:host=$host;dbname=$dbName;port=$port", $user, $pass);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //First, I need to first make sure that the username isn't already in use!
                $query = "SELECT username FROM Users WHERE username = :username";

                $stmt = $dbh->prepare($query);

                $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);

                $stmt->execute();

                $result = $stmt->fetch();

                //If the username already exists, set a session variable.
                if ($result['username'] == $_POST['username']){
                    echo "That username already exists!";
                    $_SESSION['bad_username'] = true;
                }
                else {

                    //Then I can add stuff to the database
                    $query = "INSERT INTO Users (username, uID, first_name, last_name, password)
                    VALUES (:username, :uid, :first_name, :last_name, :password)";

                    $stmt = $dbh->prepare($query);

                    //Create a hash of the password
                    $passHash = hash('sha256', $_POST['pass1']);

                    // Bind all the variables
                    $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
                    $stmt->bindParam(':uid', $_POST['uid'], PDO::PARAM_STR);
                    $stmt->bindParam(':first_name', $_POST['first_name'], PDO::PARAM_STR);
                    $stmt->bindParam(':last_name', $_POST['last_name'], PDO::PARAM_STR);
                    $stmt->bindParam(':password', $passHash, PDO::PARAM_STR);

                    $stmt->execute();

                    //Set a session variable to show that this user is registered
                    $_SESSION['registered'] = true;
                    $_SESSION['bad_username'] = false;

                    //Next, I'll assign this user the role of 'applicant'
                    //But first, I have to grab the new user's userid
                    $query = "SELECT idUsers FROM Users WHERE username = :username";

                    $stmt = $dbh->prepare($query);

                    $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);

                    $stmt->execute();

                    $result = $stmt->fetch();


                    $idUser = $result['idUsers'];
                    $role = "applicant";


                    //Now, I'll assign the user's role
                    $query = "INSERT INTO Roles (idUsers, role) VALUES (:idUsers, :role)";

                    $stmt = $dbh->prepare($query);

                    $stmt->bindParam(':idUsers', $idUser, PDO::PARAM_STR);
                    $stmt->bindParam(':role', $role, PDO::PARAM_STR);

                    $stmt->execute();

                    //Next, send the user to the log in page to log in
                    header('Location: login.php');
                }
            } catch (PDOException $e) {
                print "Error!:" . $e->getMessage() . "<br/>";
                die();
            }
        }
    }
}
?>