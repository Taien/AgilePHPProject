<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>WorkWithMe - Add Users To Group</title>
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
                    <legend>Add Users To Group</legend><br />
                    <label for="memberList">Member ID/Email:</label>
                    <input type="text" id="addMember" name="addMember" placeholder="member's user ID or email address" required><br /><br />

                    <input type="button" class="button" id="AddMember" name="AddMember" value="Add a Member"> <!-- adds user to database -->
                </fieldset><br />
            </form>
        </main>
        <footer><?php include './includes/footer.php' ?></footer>
    </body>
</html>