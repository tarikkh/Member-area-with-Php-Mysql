<?php
    session_start();
    if (isset($_SESSION["connect"])) {
        header("location:../");
    }

    require("src/conDb.php");
    if (!empty($_POST["email"]) && !empty($_POST["password"])){
        $email=$_POST["email"];
        $password=$_POST["password"];
        $password = "bb6765" . sha1($password . "1672") . "25";

        $sql = "SELECT *  From users where email= ? ;";
        $req = $db->prepare($sql) or die(print_r($db->errorInfo()));
        $req->execute(array($email));

        while ($user = $req->fetch()) {

        if ($user["password"] == $password) {

            $name=$user["userName"];
            $_SESSION["connect"]=1;
            $_SESSION["userName"]=$user["userName"];
            
            if (isset($_POST["connect"])) {
                setcookie('log',$user['secret'],time()+365*24*3600,'/',null,false,true);
            }
            header("location:../connection.php/?success=1&id=$name");
            exit();
        }
    }

            header("location:../connection.php/?error=1");
        
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/style.css" type="text/css">
    <title>Connection</title>
</head>

<body>
    <header>
        <h1>Connection</h1>
    </header>
    <div class="container">
        <p id="info">welcome to my site ,if you are not registered <a href="index.php">Register</a></p>
        <?php
            if (isset($_GET["error"])) {

                    echo "<p id='error'>account not found!</p>";
            }elseif (isset($_GET["success"]) && $_GET["id"]) {

                echo "<p id='success'>Hello ".$_GET["id"].", now you are connected</p>";
            }
         ?>
        <div id="form">
            <form action="connection.php" method="post">
                <table>
                    <tr>
                        <td>Email:</td>
                        <td><input type="email" name="email" id="email" placeholder="example@google.com" required   ></td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td><input type="password" name="password" id="password" placeholder="********" required></td>
                    </tr>
                </table>
                <p><label for="connect">
                        <input type="checkbox" name="connect" id="connect">Automatic Connection
                    </label>
                </p>
                <div id="button">
                    <button type="submit">Connection</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>