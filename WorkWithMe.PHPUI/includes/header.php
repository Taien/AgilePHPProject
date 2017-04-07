<h1><a href="./index.php"><img src="./images/wwm_logo_v2.png"> WorkWithMe.com</a></h1>

<?php

if (isset($_SESSION["UserId"]))
{
    echo '<form method="post" action="./index.php"><ul>
          <li>Logged in as ' . $_SESSION["FirstName"] . ' ' . $_SESSION["MiddleInitial"] . '. ' . $_SESSION["LastName"] . '</li>
          <li><input type="submit" id="btnLogout" name="btnLogout" value="Logout"></li>
          </ul></form>';
}
else
{
    echo '<form method="post" action="./index.php"><ul>
          <li><input type="text" id="txtUsername" name="txtUsername" placeholder="Username" required></li>
          <li><input type="password" id="txtPassword" name="txtPassword" placeholder="Password" required></li>
          <li><input type="submit" id="btnLogin" name="btnLogin" value="Login"></li>
          <li><a href="signup.php"> Register</a></li>
          </ul></form>';
}

if (isset($_SESSION["Error"]))
{
    echo '<div id="errorText">' . $_SESSION["Error"] . '</div>';
    $_SESSION["Error"] = null;
}

?>
