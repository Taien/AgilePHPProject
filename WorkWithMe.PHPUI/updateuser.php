<?php
session_start();

if (!isset($_SESSION["UserId"])) header("Location:index.php");

if (isset($_POST["btnUpdate"]))
{
    try {
        $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
        $retval = $client->UpdateUser(array('id'=>$_SESSION["UserId"],'username'=>$_POST["txtUsername"],'password'=>$_POST["txtPassword"],
            'firstName'=>$_POST["txtFName"],'middleInitial'=>$_POST["txtMI"],
            'lastName'=>$_POST["txtLName"],'zip'=>$_POST["txtZip"],
            'address'=>$_POST["txtAddress"],'city'=>$_POST["txtCity"],
            'state'=>$_POST["lstState"],'isAddressPrivate'=>$_POST["chkAddressPrivate"],
            'email'=>$_POST["txtEmail"]));
        if ($retval->UpdateUserResult)
        {
            $retval = $client->DoLogin(array('username'=>$_POST["txtUsername"],'password'=>$_POST["txtPassword"]));
            $_SESSION["Username"] = $_POST["txtUsername"];
            $_SESSION["FirstName"] = $_POST["txtFName"];
            $_SESSION["MiddleInitial"] = $_POST["txtMI"];
            $_SESSION["LastName"] = $_POST["txtLName"];
            $_SESSION["Zip"] = $_POST["txtZip"];
            $_SESSION["Address"] = $_POST["txtAddress"];
            $_SESSION["Email"] = $_POST["txtEmail"];
            $_SESSION["IsAddressPrivate"] = isset($_POST["chkAddressPrivate"]);
        }

    } catch (SoapFault $exception)
    {
        //DoLogin returns null when the login fails
        $_SESSION["Error"] = $exception->getMessage();
    }
}
else
{
    try {
        $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
        $retval = $client->GetCityStateInfo(array('zip'=>$_SESSION["Zip"]));

        $city = $retval->GetCityStateInfoResult->CityName;
        $state = $retval->GetCityStateInfoResult->StateName;

    } catch (SoapFault $exception)
    {
        //DoLogin returns null when the login fails
        $_SESSION["Error"] = $exception->getMessage();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="./styles/base.css">
</head>
<body>
<header><?php include './includes/header.php' ?></header>
<hr/>
<nav><?php include './includes/nav.php' ?></nav>
<main>
    <form action="updateuser.php" method="post">

        <fieldset>
            <legend>Update Your Information</legend><br />

            <label for="txtFName">First Name:</label>
            <input type="text" id="txtFName" name="txtFName" placeholder="first name" autofocus required value="<?=$_SESSION["FirstName"]?>"><br />

            <label for="txtMI">Middle Initial:</label>
            <input type="text" id="txtMI" name="txtMI" placeholder="middle initial" required maxlength="2" value="<?=$_SESSION["MiddleInitial"]?>"><br />

            <label for="txtLName">Last Name:</label>
            <input type="text" id="txtLName" name="txtLName" placeholder="last name" required value="<?=$_SESSION["LastName"]?>"><br />

            <label for="Email">E-mail:</label>
            <input type="email" id="txtEmail" name="txtEmail" placeholder="e-mail" required value="<?=$_SESSION["Email"]?>"><br />

        </fieldset>

        <fieldset>
            <legend>Update Address</legend><br />

            <div id="safetyWarningText">Your address will not be shared with anyone unless you choose to share it.</div>
            <label for="txtAddress">Address:</label>
            <input type="text" name="txtAddress" id="txtAddress" placeholder="address" required value="<?=$_SESSION["Address"]?>"><br />

            <label for="txtCity">City:</label>
            <input type="text" name="txtCity" id="txtCity" placeholder="city" required value="<?=$city?>"><br />

            <label for="lstState" class="dropdown">State:</label>
            <select id="lstState" name="lstState" required>
                <option value="AL" <?=$state == "AL" ? 'selected' : ''?>>Alabama</option>
                <option value="AK" <?=$state == "AK" ? 'selected' : ''?>>Alaska</option>
                <option value="AZ" <?=$state == "AZ" ? 'selected' : ''?>>Arizona</option>
                <option value="AR" <?=$state == "AR" ? 'selected' : ''?>>Arkansas</option>
                <option value="CA" <?=$state == "CA" ? 'selected' : ''?>>California</option>
                <option value="CO" <?=$state == "CO" ? 'selected' : ''?>>Colorado</option>
                <option value="CT" <?=$state == "CT" ? 'selected' : ''?>>Connecticut</option>
                <option value="DE" <?=$state == "DE" ? 'selected' : ''?>>Delaware</option>
                <option value="DC" <?=$state == "DC" ? 'selected' : ''?>>District Of Columbia</option>
                <option value="FL" <?=$state == "FL" ? 'selected' : ''?>>Florida</option>
                <option value="GA" <?=$state == "GA" ? 'selected' : ''?>>Georgia</option>
                <option value="HI" <?=$state == "HI" ? 'selected' : ''?>>Hawaii</option>
                <option value="ID" <?=$state == "ID" ? 'selected' : ''?>>Idaho</option>
                <option value="IL" <?=$state == "IL" ? 'selected' : ''?>>Illinois</option>
                <option value="IN" <?=$state == "IN" ? 'selected' : ''?>>Indiana</option>
                <option value="IA" <?=$state == "IA" ? 'selected' : ''?>>Iowa</option>
                <option value="KS" <?=$state == "KS" ? 'selected' : ''?>>Kansas</option>
                <option value="KY" <?=$state == "KY" ? 'selected' : ''?>>Kentucky</option>
                <option value="LA" <?=$state == "LA" ? 'selected' : ''?>>Louisiana</option>
                <option value="ME" <?=$state == "ME" ? 'selected' : ''?>>Maine</option>
                <option value="MD" <?=$state == "MD" ? 'selected' : ''?>>Maryland</option>
                <option value="MA" <?=$state == "MA" ? 'selected' : ''?>>Massachusetts</option>
                <option value="MI" <?=$state == "MI" ? 'selected' : ''?>>Michigan</option>
                <option value="MN" <?=$state == "MN" ? 'selected' : ''?>>Minnesota</option>
                <option value="MS" <?=$state == "MS" ? 'selected' : ''?>>Mississippi</option>
                <option value="MO" <?=$state == "MO" ? 'selected' : ''?>>Missouri</option>
                <option value="MT" <?=$state == "MT" ? 'selected' : ''?>>Montana</option>
                <option value="NE" <?=$state == "NE" ? 'selected' : ''?>>Nebraska</option>
                <option value="NV" <?=$state == "NV" ? 'selected' : ''?>>Nevada</option>
                <option value="NH" <?=$state == "NH" ? 'selected' : ''?>>New Hampshire</option>
                <option value="NJ" <?=$state == "NJ" ? 'selected' : ''?>>New Jersey</option>
                <option value="NM" <?=$state == "NM" ? 'selected' : ''?>>New Mexico</option>
                <option value="NY" <?=$state == "NY" ? 'selected' : ''?>>New York</option>
                <option value="NC" <?=$state == "NC" ? 'selected' : ''?>>North Carolina</option>
                <option value="ND" <?=$state == "ND" ? 'selected' : ''?>>North Dakota</option>
                <option value="OH" <?=$state == "OH" ? 'selected' : ''?>>Ohio</option>
                <option value="OK" <?=$state == "OK" ? 'selected' : ''?>>Oklahoma</option>
                <option value="OR" <?=$state == "OR" ? 'selected' : ''?>>Oregon</option>
                <option value="PA" <?=$state == "PA" ? 'selected' : ''?>>Pennsylvania</option>
                <option value="RI" <?=$state == "RI" ? 'selected' : ''?>>Rhode Island</option>
                <option value="SC" <?=$state == "SC" ? 'selected' : ''?>>South Carolina</option>
                <option value="SD" <?=$state == "SD" ? 'selected' : ''?>>South Dakota</option>
                <option value="TN" <?=$state == "TN" ? 'selected' : ''?>>Tennessee</option>
                <option value="TX" <?=$state == "TX" ? 'selected' : ''?>>Texas</option>
                <option value="UT" <?=$state == "UT" ? 'selected' : ''?>>Utah</option>
                <option value="VT" <?=$state == "VT" ? 'selected' : ''?>>Vermont</option>
                <option value="VA" <?=$state == "VA" ? 'selected' : ''?>>Virginia</option>
                <option value="WA" <?=$state == "WA" ? 'selected' : ''?>>Washington</option>
                <option value="WV" <?=$state == "WV" ? 'selected' : ''?>>West Virginia</option>
                <option value="WI" <?=$state == "WI" ? 'selected' : ''?>>Wisconsin</option>
                <option value="WY" <?=$state == "WY" ? 'selected' : ''?>>Wyoming</option>
            </select><br />

            <label for="txtZip">Zip:</label>
            <input type="text" name="txtZip" id="txtZip" maxlength="5"
                   placeholder="5 digit zip code" required value="<?=$_SESSION["Zip"]?>"><br />

            <label for="chkAddressPrivate">Keep Address Private:</label>
            <input type="checkbox" name="chkAddressPrivate" id="chkAddressPrivate" value="<?=$_SESSION["IsAddressPrivate"]?>"><br/>

        </fieldset>

        <fieldset>
            <legend>Update Password</legend><br />

            <label for="txtUsername">Username:</label>
            <input type="text" name="txtUsername" id="txtUsername" placeholder="username" required value="<?=$_SESSION["Username"]?>"><br />

            <label for="txtPassword">Password:</label>
            <input type="password" name="txtPassword" id="txtPassword" placeholder="enter password" required minlength="6" maxlength="24"><br />

            <label for="txtPwdVerify">Password Verify:</label>
            <input type="password" name="txtPwdVerify" id="txtPwdVerify" placeholder="re-enter password" required minlength="6" maxlength="24"><br />
        </fieldset>

        <fieldset>
            <legend>Finalize</legend><br />

            <div id="buttons">
                <ul>
                    <li><input type="submit" class="button" id="btnUpdate" name="btnUpdate" value="Update"></li>
                    <li><input type="reset" class="button" id="btnClear" name="btnClear" value="Clear"></li>
                </ul>
            </div>
        </fieldset>

    </form>
</main>
<div id="rightNav"><?php include './includes/rightnav.php' ?></div>
<footer><?php include './includes/footer.php' ?></footer>
</body>
</html>