<?php
session_start();

if (!isset($_SESSION["UserId"])) header("Location:index.php");

if (isset($_POST["btnSearch"]))
{
    if (!isset($_POST["txtUser"]) || empty($_POST["txtUser"]))
    {
        $_SESSION["Error"] = "You must provide a username.";
    }
    else
    {
        $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
        $retval = $client->SearchUser(array('searchString'=>$_POST["txtUser"]));
        $resultArray = $retval->SearchUserResult->CUser;
    }
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>WorkWithMe - Add A Contact</title>
        <link rel="stylesheet" type="text/css" href="./styles/base.css">
    </head>
    <body>
        <header><?php include './includes/header.php' ?></header>
        <hr/>
        <nav><?php include './includes/nav.php' ?></nav>
        <main>
            <?php

            if (isset($_POST["btnSearch"]))
            {
                $numOfResults = count($resultArray);

                echo '<form method="post">
                             <fieldset>
                             <legend>Select User</legend><br />';

                for ($i = 0; $i < $numOfResults; $i++)
                {
                    if ($numOfResults == 1)
                    {
                        $id = $resultArray->Id;
                        $username = $resultArray->Username;
                        $firstName = $resultArray->FirstName;
                        $middleInitial = $resultArray->MiddleInitial;
                        $lastName = $resultArray->LastName;
                        $email = $resultArray->Email;
                    }
                    else
                    {
                        $id = $resultArray[$i]->Id;
                        $username = $resultArray[$i]->Username;
                        $firstName = $resultArray[$i]->FirstName;
                        $middleInitial = $resultArray[$i]->MiddleInitial;
                        $lastName = $resultArray[$i]->LastName;
                        $email = $resultArray[$i]->Email;
                    }

                    echo '<input type="hidden" id="txtId" name="txtId" value="' . $id . '">' .
                         $firstName . ' ' . $lastName . ' (' . $email . ') '
                         . '<input type="submit" id="btnAddContact" name="btnAddContact" value="Add Contact"><br/>';
                }
                echo '</fieldset>
                          </form>';
            }
            else
            {
                echo '<form method="post">
                         <fieldset>
                             <legend>Add User To Contacts</legend><br />
                             <label for="memberList">Member ID/Email:</label>
                             <input type="text" id="txtUser" name="txtUser" placeholder="member\'s user ID or email address" required><br /><br />

                             <input type="submit" id="btnSearch" name="btnSearch" value="Search for Users"> <! -- adds user to database -->
                         </fieldset><br />
                      </form>';
            }
            ?>

        </main>
        <div id="rightNav"><?php include './includes/rightnav.php' ?></div>
        <footer><?php include './includes/footer.php' ?></footer>
    </body>
</html>