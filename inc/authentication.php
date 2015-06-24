<!--
Author: Blake McGillis
Date: March 21, 2015
Phase: 7
-->

<?php
/*
 * Called by login.php. Makes sure that the page was reached by POST. Confirms that the fields were all filled out.
 * Then validates the username and password. Once logged in, sends the user to their requested page or the
 * project main page.
 */

session_start();

/*Contains database variables*/
require 'helper/dbConfig.php';



/* Make sure this page was reached via POST and make sure all fields are filled out */
if (isset ($_POST['username'], $_POST['password'])) {

    if (empty($_POST['username'])) {
        echo "No username entered";
    } else if (empty($_POST['password'])) {
        echo "No password entered";
    } else {
            //If everything is filled in, check the username and password
        try {
            $dbh = new PDO("mysql:host=$host;dbname=$dbName;port=$port", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //Grab password to verify it. Also grab idUsers, first name, last name and UID to use later
            $query = "SELECT password, idUsers, first_name, last_name, uID FROM Users WHERE username = :username";

            $stmt = $dbh->prepare($query);

            $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);

            $stmt->execute();

            //If the username exists, check the password
            if ($stmt->rowCount() > 0){
                $result = $stmt->fetch();

                $dbPassword = $result['password'];

                if ($dbPassword == hash('sha256', $_POST['password'])){

                    //Now that the user is good to log in, unset all session variables except requestedPage
                    clear_session_variables();

                    //Then populate the user object
                    $user = new User();
                    $user->set_uid($result['uID']);
                    $user->set_first_name($result['first_name']);
                    $user->set_last_name($result['last_name']);

                    //Store the user's UID in a session variable for easy access
                    $_SESSION['uid'] = $result['uID'];


                    //Now assign the user their roles
                    $query = "SELECT role FROM Roles WHERE idUsers = :idUser";

                    $stmt = $dbh->prepare($query);

                    $stmt->bindParam(':idUser', intval($result['idUsers']), PDO::PARAM_INT);

                    $stmt->execute();

                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    //Now add the user's roles to the User object
                    foreach ($result as $row)
                    {
                        echo "I'm adding this: " . $row['role'];

                        $user->add_role($row['role']);
                    }

                    //Add the user to a session variable and get their full name in one too
                    $_SESSION['user'] = $user;
                    $_SESSION['full_name'] = $user->get_full_name();

                    //If the user was trying to access a page, send them there
                    if ( isset($_SESSION['requestedPage'])){
                        $requestedPage = $_SESSION['requestedPage'];
                        unset($_SESSION['requestedPage']);
                        header($requestedPage);
                    }
                    //Otherwise, send them to projectMain
                    else {
                        header('Location: projectMain.php');
                    }
                }
                //If the password is wrong, tell the user
                else {
                    $_SESSION['registered'] = false;
                    $_SESSION['badUserPass'] = true;
                }
            }
            //If the username doesn't exist, tell the user to try again
            else {
                $_SESSION['registered'] = false;
                $_SESSION['badUserPass'] = true;
            }
            $dbh = null;
        }

        catch (PDOException $e) {
            print "Error!:" . $e->getMessage() . "<br/>";
            die();
        }
    }
}
?>