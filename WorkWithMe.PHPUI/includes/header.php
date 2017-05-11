<header><a href="./index.php"><img src="./images/wwm_logo_v2.png"></a><h1>WorkWithMe.com</h1>

<?php

if (isset($_SESSION["UserId"]))
{
    echo '<form method="post" action="./index.php"><div id="log"><ul>
             
              <li><input type="submit" id="btnLogout" name="btnLogout" value="Logout"></li>
              </ul></div></form>';

    echo '<div id="name">Logged in as ' . $_SESSION["FirstName"] . ' ' . $_SESSION["MiddleInitial"] . '. ' . $_SESSION["LastName"] . '</div>';
}
else
{
    echo '<form method="post" action="./index.php"><div id="log"><ul>
          <li><input type="text" id="txtUsername" name="txtUsername" placeholder="Username" required></li>
          <li><input type="password" id="txtPassword" name="txtPassword" placeholder="Password" required></li><br />
          <li><input type="submit" id="btnLogin" name="btnLogin" value="Login"></li>
          <li><input type="submit" id="btnRegister" name="btnRegister" value="Register"></li>
          </ul></div></form>';
}

echo "</header>";
if (isset($_SESSION["isRedirecting"])) $_SESSION["isRedirecting"] = null;
else
{
    if (isset($_SESSION["Status"])) {
        if (isset($_SESSION["GoodStatus"])) {
            echo '<hr/><div id="statusText">' . $_SESSION["Status"] . '</div>';
            $_SESSION["Status"] = null;
            $_SESSION["GoodStatus"] = null;
        } else {
            echo '<hr/><div id="errorText">' . $_SESSION["Status"] . '</div>';
            $_SESSION["Status"] = null;
        }
    }
}
?>
