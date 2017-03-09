<?php
session_start();


    if (isset($_POST["btnLogin"]))
    {
        try {
            $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
            $retval = $client->DoLogin(array('username'=>$_POST["txtUsername"],'password'=>$_POST["txtPassword"]));
            //var_dump($retval);
            //echo $retval->DoLoginResult->Id;
            $_SESSION["UserId"] = $retval->DoLoginResult->Id;
            $_SESSION["Username"] = $retval->DoLoginResult->Username;
            $_SESSION["FirstName"] = $retval->DoLoginResult->FirstName;
            $_SESSION["MiddleInitial"] = $retval->DoLoginResult->MiddleInitial;
            $_SESSION["LastName"] = $retval->DoLoginResult->LastName;
            $_SESSION["Zip"] = $retval->DoLoginResult->Zip;
            $_SESSION["Address"] = $retval->DoLoginResult->Address;
            $_SESSION["IsAddressPrivate"] = $retval->DoLoginResult->IsAddressPrivate;
            $_SESSION["Error"] = "You have successfully logged in.";

        } catch (SoapFault $exception)
        {
            //DoLogin returns null when the login fails
            $_SESSION["Error"] = "Login failed, username/password combination is invalid.";
            //invalid login - handle it somehow
        }

    }
    elseif (isset($_POST["btnLogout"]))
    {
        $_SESSION["UserId"] = null;
        $_SESSION["Username"] = null;
        $_SESSION["FirstName"] = null;
        $_SESSION["MiddleInitial"] = null;
        $_SESSION["LastName"] = null;
        $_SESSION["Zip"] = null;
        $_SESSION["Address"] = null;
        $_SESSION["IsAddressPrivate"] = null;
        $_SESSION["Error"] = "You have been logged out.";
    }?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Work With Me</title>
    <link rel="stylesheet" type="text/css" href="./styles/base.css">
</head>
<body>
<header><?php include './includes/header.php' ?></header>
<hr/>
<nav><?php include './includes/nav.php' ?></nav>
<main>
    <p>
        <?php
            if (isset($_SESSION["UserId"]))
            {
                echo "<h2>" . $_SESSION["FirstName"] . " " . $_SESSION["MiddleInitial"] . ". " . $_SESSION["LastName"] . "'s Information (temp page)</h2>";
                echo "<br/><b>Username: </b> " . $_SESSION["Username"];
                echo "<br/><b>Id: </b> " . $_SESSION["UserId"];
                echo "<br/><b>Address: </b> " . $_SESSION["Address"];
                echo "<br/><b>Zip: </b> " . $_SESSION["Zip"];
                echo "<br/><b>IsAddressPrivate: </b> ";
                if ($_SESSION["IsAddressPrivate"]) echo "Yes";
                else echo "No";
            }
            else
            {
                echo "<h2>Welcome to our cool messaging/project managing thing!!</h2><br />";
                echo "<div id=\"introText\">Here's a bunch of features!</div><br />";
                echo "<ul>";
                $i = 0;
                while ($i < 20)
                {
                    echo "<li>Feature number $i</li>";
                    $i++;
                }
                echo "</ul><br />";
                echo "<img src=\"images/cat.jpg\" alt=\"placeholder\" /><br />";
                echo "<h2>I can be placeholder for cool javascript image slideshow?</h2>";
            }
        ?>
    </p>
</main>
<div id="rightNav"><?php include './includes/rightnav.php' ?></div>
<footer><?php include './includes/footer.php' ?></footer>
</body>
</html>