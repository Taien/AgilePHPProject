<?php
session_start();

?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Page Title Here</title>
        <link rel="stylesheet" type="text/css" href="./styles/base.css">
    </head>
    <body>
        <header><?php include './includes/header.php' ?></header>
        <hr/>
        <nav><?php include './includes/nav.php' ?></nav>
        <main>
            <form method="post">
                <fieldset>
                    <legend>Make a New Group</legend><br />

                    <label for="txtGroupName">Group Name:</label>
                    <input type="text" id="txtGroupName" name="txtGroupName" placeholder="enter group name" autofocus required>

                    <label for="txtGroupDescription">Group Description:</label>
                    <input type="text" id="txtGroupDescription" name="txtGroupDescription" placeholder="enter group description" required><br /><br />

                    <label for="txtGroupMembers">Group Members:</label>
                    <input type="text" id="txtGroupMembers" name="txtGroupMembers" placeholder="enter group members" autofocus required>

                    <input type="submit" class="button" id="btnCreateGroup" name="btnCreateGroup" value="Create Group"> <!-- adds group to database and goes to addusers.php -->

                </fieldset><br />
            </form>
        </main>
        <div id="rightNav"><?php include './includes/rightnav.php' ?></div>
        <footer><?php include './includes/footer.php' ?></footer>
    </body>
</html>
