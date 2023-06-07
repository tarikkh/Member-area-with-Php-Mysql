<?php
session_start();
require("src/conDb.php");
if (!empty($_POST["userName"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["passwordRet"])) {
    // variables
    $userName   = $_POST["userName"];
    $email      = $_POST["email"];
    $password   = $_POST["password"];
    $passwordCon = $_POST["passwordRet"];
    //check if password the same as confirmation password
    if ($password != $passwordCon) {
        header("location:i../?error=1&pass=1");
        exit();
    }

    //check if email in database

    $sql = "SELECT count(*) as numberEmail From users where email= ?";
    $req = $db->prepare($sql) or die(print_r($db->errorInfo()));
    $req->execute(array($email));

    while ($email_verification = $req->fetch()) {

        if ($email_verification["numberEmail"] != 0) {

            header("location:../?error=1&email=1");
            exit();
        }
    }
    // Hash
    $key = sha1($email) . time();
    $key = sha1($key) . time() . time();
    //crypte password
    $password = "bb6765" . sha1($password . "1672") . "25";

    // inser values in database
    $sql = "INSERT INTO users(userName , email , password , secret) Values(?,?,?,?);";
    $req = $db->prepare($sql) or die(print_r($db->errorInfo()));
    $req->execute(array($userName, $email, $password, $key));
    header("location:../?success=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/style.css" type="text/css">
    <title>Inscription</title>
</head>

<body>
    <header>
        <h1>Inscription</h1>
    </header>
    <div class="container">
        <?php 
            if (!isset($_SESSION["connect"])) {?>
        <p id="info">welcome to my site to see more subscribe, Otherwise <a href="connection.php">log in</a></p>

        <?php
        if (isset($_GET["error"])) {

            if (isset($_GET["pass"])) {
                echo "<p id='error'>The passwords are not the same</p>";
            } elseif (isset($_GET["email"])) {
                echo "<p id='error'>This email address is already taken</p>";
            }
        } elseif (isset($_GET["success"])) {
            echo "<p id='success'>registration correctly taken into account</p>";
        }
        ?>

        <div id="form">
            <form action="index.php" method="post">
                <table>
                    <tr>
                        <td>User name:</td>
                        <td><input type="text" name="userName" id="userName" placeholder="James" required></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><input type="email" name="email" id="email" placeholder="example@google.com" required></td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td><input type="password" name="password" id="password" placeholder="********" required></td>
                    </tr>
                    <tr>
                        <td>Retype password:</td>
                        <td><input type="password" name="passwordRet" id="passwordRet" placeholder="********" required></td>
                    </tr>
                </table>
                <div id="button">
                    <button type="submit">Registration</button>
                </div>
            </form>
        </div>
        <?php }  else {?>
            <p id="info">Hello  <?= $_SESSION["userName"]?>
            <br>
            <a href="disconnect.php">disconnect</a>
        </p>
        <?php }?>
    </div>
</body>

</html>