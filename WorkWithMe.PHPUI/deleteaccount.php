<?php
session_start();

if (!isset($_SESSION["UserId"])) //send user back to index if not logged in
{
    $_SESSION["Status"] = "You must be logged in to view that page.";
    $_SESSION["isRedirecting"] = true;
    header("Location:index.php");
}
if (isset($_POST["btnDeleteAccount"])) //user has confirmed account deletion and entered password and username
{
    try{
        $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
        $retval = $client->DeleteUser(array('username'=>$_POST["txtUsername"],'password'=>$_POST["txtPassword"]));
        if ($retval->DeleteUserResult)
        {
            $_SESSION["Status"] = "Your account has been deleted. Thank you for using WorkWithMe.";
            $_SESSION["UserId"] = null;
            $_SESSION["GoodStatus"] = true;
            $_SESSION["isRedirecting"] = true;
            header("Location:index.php");
        }
        else
        {
            $_SESSION["Status"] = "Failed to delete account for some reason.  Contact support.";
            $_SESSION["UserId"] = null;
            $_SESSION["isRedirecting"] = true;
            header("Location:index.php");
        }
    }
    catch (SoapFault $exception)
    {
        $_SESSION["Status"] = "Failed to delete account - " . $exception->getMessage();
    }

}
else if (!isset($_POST["txtUserId"])) //send user back to index if not logged in
{
    $_SESSION["Status"] = "You can only access that page from the Update Profile page.";
    $_SESSION["isRedirecting"] = true;
    header("Location:index.php");
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>WorkWithMe - Invites</title>
    <link rel="stylesheet" type="text/css" href="./styles/base.css">
</head>
<body>
<?php include './includes/header.php' ?>
<hr/>
<nav><?php include './includes/nav.php' ?></nav>
<div id="rightNav"><?php include './includes/rightnav.php' ?></div>
<main>
    <form method="post">
        <fieldset>
            <legend>Delete Your Account</legend><br />
            <p>To verify that you would like to delete your account, please enter your username and password below:</p><br>
            <label for="txtUsername">Username:</label>
            <input type="text" name="txtUsername" id="txtUsername" placeholder="username" required><br />

            <label for="txtPassword">Password:</label>
            <input type="password" name="txtPassword" id="txtPassword" placeholder="enter password" required minlength="6" maxlength="24"><br />

            <p>WARNING:  Account deletion is permanent and irreversible.  Please be absolutely sure you want to delete your account before clicking the button below.
                This will delete all information associated with your account, including posts, pictures, and your contacts list.</p>
            <div id="deleteButton"><ul><li><input type="submit" id="btnDeleteAccount" name="btnDeleteAccount" value="PERMANENTLY Delete Account"></li></ul></div> <! -- adds user to database -->
        </fieldset><br />
    </form>
</main>
<footer><?php include './includes/footer.php' ?></footer>
</body>
</html>