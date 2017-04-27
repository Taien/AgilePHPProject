<?php
session_start();

if (!isset($_SESSION["UserId"])) //send user back to index if not logged in
{
    $_SESSION["Status"] = "You must be logged in to view that page.";
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
<main>

</main>
<div id="rightNav"><?php include './includes/rightnav.php' ?></div>
<footer><?php include './includes/footer.php' ?></footer>
</body>
</html>