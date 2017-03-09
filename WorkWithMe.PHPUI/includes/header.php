<h1><a href="./index.php"><img src="./images/wwm_logo.png"> WorkWithMe.com</a></h1>

<?php

if (isset($_SESSION["UserId"]))
{
    echo "<form method=\"post\" action=\"./index.php\"><ul>";
    echo "<li>Logged in as " . $_SESSION["FirstName"] . " " . $_SESSION["MiddleInitial"] . ". " . $_SESSION["LastName"] . "</li>";
    echo "<li><input type=\"submit\" onclick=\"index.php\" id=\"btnLogout\" name=\"btnLogout\" value=\"Logout\"></li>";
    echo "</ul></form>";
    if (isset($_SESSION["Error"]))
    {
        echo "<div id=\"errorText\">" . $_SESSION["Error"] . "</div>";
        $_SESSION["Error"] = null;
    }
}
else
{
    echo "<form method=\"post\" action=\"./index.php\"><ul>";
    echo "<li><input type=\"text\" id=\"txtUsername\" name=\"txtUsername\" placeholder=\"Username\"></li>";
    echo "<li><input type=\"password\" id=\"txtPassword\" name=\"txtPassword\" placeholder=\"Password\"></li>";
    echo "<li><input type=\"submit\" onclick=\"index.php\" id=\"btnLogin\" name=\"btnLogin\" value=\"Login\"></li>";
    echo "<li><a href=\"signup.php\"> Register</a></li>";
    echo "</ul></form>";
    if (isset($_SESSION["Error"]))
    {
        echo "<div id=\"errorText\">" . $_SESSION["Error"] . "</div>";
        $_SESSION["Error"] = null;
    }
}?>
