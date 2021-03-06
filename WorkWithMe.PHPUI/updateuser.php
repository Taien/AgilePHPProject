<?php
session_start();

if (isset($_POST["btnUpdate"]))
{
    try {
        $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
        if ($_FILES['inputImage']['size'] != 0)
        {
            $fileName = $_FILES['inputImage']['name'];
            $tmpName  = $_FILES['inputImage']['tmp_name'];
            $fileSize = $_FILES['inputImage']['size'];
            $fileType = $_FILES['inputImage']['type'];

            $fileContent = file_get_contents($_FILES['inputImage']['tmp_name']);

            if(!get_magic_quotes_gpc())
            {
                $fileName = addslashes($fileName);
            }

            //run query
            $retval = $client->UpdateUserWithImage(array('id'=>$_SESSION["UserId"],'username'=>$_POST["txtUsername"],'password'=>$_POST["txtPassword"],
                'firstName'=>$_POST["txtFName"],'middleInitial'=>$_POST["txtMI"],
                'lastName'=>$_POST["txtLName"],'zip'=>$_POST["txtZip"],
                'address'=>$_POST["txtAddress"],'city'=>$_POST["txtCity"],
                'state'=>$_POST["lstState"],'isAddressPrivate'=>isset($_POST["chkAddressPrivate"]),
                'email'=>$_POST["txtEmail"],'imageName'=>$fileName,'imageSize'=>$fileSize,'imageContent'=>$fileContent));

            if ($retval->UpdateUserWithImageResult) //true if update succeeded
            {
                $retval = $client->DoLogin(array('username'=>$_POST["txtUsername"],'password'=>$_POST["txtPassword"]));
                include './includes/storeuserdata.php';
                $_SESSION["Status"] = "Successfully updated user info.";
                $_SESSION["GoodStatus"] = true;
            }
        }
        else
        {
            $retval = $client->UpdateUser(array('id'=>$_SESSION["UserId"],'username'=>$_POST["txtUsername"],'password'=>$_POST["txtPassword"],
                'firstName'=>$_POST["txtFName"],'middleInitial'=>$_POST["txtMI"],
                'lastName'=>$_POST["txtLName"],'zip'=>$_POST["txtZip"],
                'address'=>$_POST["txtAddress"],'city'=>$_POST["txtCity"],
                'state'=>$_POST["lstState"],'isAddressPrivate'=>isset($_POST["chkAddressPrivate"]),
                'email'=>$_POST["txtEmail"]));

            if ($retval->UpdateUserResult) //true if update succeeded
            {
                $retval = $client->DoLogin(array('username'=>$_POST["txtUsername"],'password'=>$_POST["txtPassword"]));
                include './includes/storeuserdata.php';
                $_SESSION["Status"] = "Successfully updated user info.";
                $_SESSION["GoodStatus"] = true;
            }
        }
    } catch (SoapFault $exception)
    {
        //DoLogin returns null when the login fails
        $_SESSION["Status"] = "Error updating your info - " . $exception->getMessage();
    }
}
else
{
    try {
        $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
        $retval = $client->GetCityStateInfo(array('zip'=>$_SESSION["Zip"]));

        $_SESSION["City"] = $retval->GetCityStateInfoResult->CityName;
        $_SESSION["State"] = $retval->GetCityStateInfoResult->StateName;

    } catch (SoapFault $exception)
    {
        //DoLogin returns null when the login fails
        $_SESSION["Status"] = "Error getting city/state info - " . $exception->getMessage();
    }
}

if (!isset($_SESSION["UserId"])) //this needs to be at end of page to override the city/state info error above - exit() messes with the session
{
    $_SESSION["Status"] = "You must be logged in to view that page.";
    $_SESSION["isRedirecting"] = true;
    header("Location:index.php");
}

if (isset($_SESSION["UserImgId"]))
{
    try{
        $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
        $imageretval = $client->GetImageData(array('userImgId'=>$_SESSION["UserImgId"]));
    }
    catch (SoapFault $exception)
    {
        //DoLogin returns null when the login fails
        $_SESSION["Status"] = "Error getting image data - " . $exception->getMessage();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>WorkWithMe - Update User Profile</title>
    <link rel="stylesheet" type="text/css" href="./styles/base.css">
</head>
<body>
<?php include './includes/header.php' ?>
<hr/>
<nav><?php include './includes/nav.php' ?></nav>
<div id="rightNav"><?php include './includes/rightnav.php' ?></div>
<main>
    <form method="post" enctype="multipart/form-data" >

        <fieldset>
            <legend>Update Your Information</legend><br />

            <label for="txtFName">First Name:</label>
            <input type="text" id="txtFName" name="txtFName" placeholder="first name" autofocus required value="<?=$_SESSION["FirstName"]?>"><br />

            <label for="txtMI">Middle Initial:</label>
            <input type="text" id="txtMI" name="txtMI" placeholder="middle initial" required maxlength="2" value="<?=$_SESSION["MiddleInitial"]?>"><br />

            <label for="txtLName">Last Name:</label>
            <input type="text" id="txtLName" name="txtLName" placeholder="last name" required value="<?=$_SESSION["LastName"]?>"><br />

            <label for="Email">E-mail:</label>
            <input type="email" id="txtEmail" name="txtEmail" placeholder="e-mail" required value="<?=$_SESSION["Email"]?>"><br /><br />

            <?php
            if (isset($imageretval->GetImageDataResult))
            {
                echo '<img id="profileimage" src="data:image/jpeg;base64,' . base64_encode($imageretval->GetImageDataResult) . '" width="100" height="100"><br>';
            }
            ?>
            <label for="inputImage">Profile Image:</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="102400">
            <input type="file" name="inputImage" id="inputImage" accept=".jpg">
        </fieldset>

        <fieldset>
            <legend>Update Address</legend><br />

            <div id="safetyWarningText">Your address will not be shared with anyone unless you choose to share it.</div>
            <label for="txtAddress">Address:</label>
            <input type="text" name="txtAddress" id="txtAddress" placeholder="address" required value="<?=$_SESSION["Address"]?>"><br />

            <label for="txtCity">City:</label>
            <input type="text" name="txtCity" id="txtCity" placeholder="city" required value="<?=$_SESSION["City"]?>"><br />

            <label for="lstState" class="dropdown">State:</label>
            <select id="lstState" name="lstState" required>
                <option value="AL" <?=$_SESSION["State"] == "AL" ? 'selected' : ''?>>Alabama</option>
                <option value="AK" <?=$_SESSION["State"] == "AK" ? 'selected' : ''?>>Alaska</option>
                <option value="AZ" <?=$_SESSION["State"] == "AZ" ? 'selected' : ''?>>Arizona</option>
                <option value="AR" <?=$_SESSION["State"] == "AR" ? 'selected' : ''?>>Arkansas</option>
                <option value="CA" <?=$_SESSION["State"] == "CA" ? 'selected' : ''?>>California</option>
                <option value="CO" <?=$_SESSION["State"] == "CO" ? 'selected' : ''?>>Colorado</option>
                <option value="CT" <?=$_SESSION["State"] == "CT" ? 'selected' : ''?>>Connecticut</option>
                <option value="DE" <?=$_SESSION["State"] == "DE" ? 'selected' : ''?>>Delaware</option>
                <option value="DC" <?=$_SESSION["State"] == "DC" ? 'selected' : ''?>>District Of Columbia</option>
                <option value="FL" <?=$_SESSION["State"] == "FL" ? 'selected' : ''?>>Florida</option>
                <option value="GA" <?=$_SESSION["State"] == "GA" ? 'selected' : ''?>>Georgia</option>
                <option value="HI" <?=$_SESSION["State"] == "HI" ? 'selected' : ''?>>Hawaii</option>
                <option value="ID" <?=$_SESSION["State"] == "ID" ? 'selected' : ''?>>Idaho</option>
                <option value="IL" <?=$_SESSION["State"] == "IL" ? 'selected' : ''?>>Illinois</option>
                <option value="IN" <?=$_SESSION["State"] == "IN" ? 'selected' : ''?>>Indiana</option>
                <option value="IA" <?=$_SESSION["State"] == "IA" ? 'selected' : ''?>>Iowa</option>
                <option value="KS" <?=$_SESSION["State"] == "KS" ? 'selected' : ''?>>Kansas</option>
                <option value="KY" <?=$_SESSION["State"] == "KY" ? 'selected' : ''?>>Kentucky</option>
                <option value="LA" <?=$_SESSION["State"] == "LA" ? 'selected' : ''?>>Louisiana</option>
                <option value="ME" <?=$_SESSION["State"] == "ME" ? 'selected' : ''?>>Maine</option>
                <option value="MD" <?=$_SESSION["State"] == "MD" ? 'selected' : ''?>>Maryland</option>
                <option value="MA" <?=$_SESSION["State"] == "MA" ? 'selected' : ''?>>Massachusetts</option>
                <option value="MI" <?=$_SESSION["State"] == "MI" ? 'selected' : ''?>>Michigan</option>
                <option value="MN" <?=$_SESSION["State"] == "MN" ? 'selected' : ''?>>Minnesota</option>
                <option value="MS" <?=$_SESSION["State"] == "MS" ? 'selected' : ''?>>Mississippi</option>
                <option value="MO" <?=$_SESSION["State"] == "MO" ? 'selected' : ''?>>Missouri</option>
                <option value="MT" <?=$_SESSION["State"] == "MT" ? 'selected' : ''?>>Montana</option>
                <option value="NE" <?=$_SESSION["State"] == "NE" ? 'selected' : ''?>>Nebraska</option>
                <option value="NV" <?=$_SESSION["State"] == "NV" ? 'selected' : ''?>>Nevada</option>
                <option value="NH" <?=$_SESSION["State"] == "NH" ? 'selected' : ''?>>New Hampshire</option>
                <option value="NJ" <?=$_SESSION["State"] == "NJ" ? 'selected' : ''?>>New Jersey</option>
                <option value="NM" <?=$_SESSION["State"] == "NM" ? 'selected' : ''?>>New Mexico</option>
                <option value="NY" <?=$_SESSION["State"] == "NY" ? 'selected' : ''?>>New York</option>
                <option value="NC" <?=$_SESSION["State"] == "NC" ? 'selected' : ''?>>North Carolina</option>
                <option value="ND" <?=$_SESSION["State"] == "ND" ? 'selected' : ''?>>North Dakota</option>
                <option value="OH" <?=$_SESSION["State"] == "OH" ? 'selected' : ''?>>Ohio</option>
                <option value="OK" <?=$_SESSION["State"] == "OK" ? 'selected' : ''?>>Oklahoma</option>
                <option value="OR" <?=$_SESSION["State"] == "OR" ? 'selected' : ''?>>Oregon</option>
                <option value="PA" <?=$_SESSION["State"] == "PA" ? 'selected' : ''?>>Pennsylvania</option>
                <option value="RI" <?=$_SESSION["State"] == "RI" ? 'selected' : ''?>>Rhode Island</option>
                <option value="SC" <?=$_SESSION["State"] == "SC" ? 'selected' : ''?>>South Carolina</option>
                <option value="SD" <?=$_SESSION["State"] == "SD" ? 'selected' : ''?>>South Dakota</option>
                <option value="TN" <?=$_SESSION["State"] == "TN" ? 'selected' : ''?>>Tennessee</option>
                <option value="TX" <?=$_SESSION["State"] == "TX" ? 'selected' : ''?>>Texas</option>
                <option value="UT" <?=$_SESSION["State"] == "UT" ? 'selected' : ''?>>Utah</option>
                <option value="VT" <?=$_SESSION["State"] == "VT" ? 'selected' : ''?>>Vermont</option>
                <option value="VA" <?=$_SESSION["State"] == "VA" ? 'selected' : ''?>>Virginia</option>
                <option value="WA" <?=$_SESSION["State"] == "WA" ? 'selected' : ''?>>Washington</option>
                <option value="WV" <?=$_SESSION["State"] == "WV" ? 'selected' : ''?>>West Virginia</option>
                <option value="WI" <?=$_SESSION["State"] == "WI" ? 'selected' : ''?>>Wisconsin</option>
                <option value="WY" <?=$_SESSION["State"] == "WY" ? 'selected' : ''?>>Wyoming</option>
            </select><br />

            <label for="txtZip">Zip:</label>
            <input type="text" name="txtZip" id="txtZip" maxlength="5"
                   placeholder="5 digit zip code" required value="<?=$_SESSION["Zip"]?>"><br />

            <label for="chkAddressPrivate">Keep Address Private:</label>
            <input type="checkbox" name="chkAddressPrivate" id="chkAddressPrivate" value="IsAddressPrivate" <?=$_SESSION["IsAddressPrivate"] ? "checked" : ""?>><br/><br />
        </fieldset>

        <fieldset>
            <legend>Update Password</legend><br />

            <label for="txtUsername">Username:</label>
            <input type="text" name="txtUsername" id="txtUsername" placeholder="username" required value="<?=$_SESSION["Username"]?>"><br />

            <label for="txtPassword">Password:</label>
            <input type="password" name="txtPassword" id="txtPassword" placeholder="enter password" required minlength="6" maxlength="24"><br />

            <label for="txtPwdVerify">Password Verify:</label>
            <input type="password" name="txtPwdVerify" id="txtPwdVerify" placeholder="re-enter password" required minlength="6" maxlength="24"><br /><br />
        </fieldset>

        <fieldset>
            <legend>Finalize</legend><br />

            <div id="buttons">
                <ul>
                    <li><input type="submit" class="button fbCentered fbWideBlue" id="btnUpdate" name="btnUpdate" value="Save My Updates"></li>
                    <li><input type="reset" class="button fbCentered fbWideBlue" id="btnClear" name="btnClear" value="Clear Form"></li>
                </ul>
            </div>
        </fieldset><br />

    </form>
    <form action="deleteaccount.php" method="post">
        <div id="deleteButton">
            <ul>
                <li>
                    <input type="hidden" id="txtUserId" name="txtUserId" value="<?=$_SESSION["UserId"]?>">
                    <input type="submit" class="button" id="btnDelete" name="btnDelete" value="Delete My Account">
                </li>
            </ul>
        </div>
    </form>
</main>
<footer><?php include './includes/footer.php' ?></footer>
</body>
</html>