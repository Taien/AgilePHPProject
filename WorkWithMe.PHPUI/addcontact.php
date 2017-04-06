<?php
session_start();

if (!isset($_SESSION["UserId"])) header("Location:index.php");

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
            <form method="post">
                <fieldset>
                    <legend>Add User To Contacts</legend><br />
                    <label for="memberList">Member ID/Email:</label>
                    <input type="text" id="txtUser" name="txtUser" placeholder="member's user ID or email address" required><br /><br />

                    <input type="button" id="btnSearch" name="btnSearch" value="Search for Users"> <! -- adds user to database -->
                </fieldset><br />
            </form>
        </main>
        <div id="rightNav"><?php include './includes/rightnav.php' ?></div>
        <footer><?php include './includes/footer.php' ?></footer>
    </body>
</html>