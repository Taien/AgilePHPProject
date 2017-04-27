<?php
session_start();

if (!isset($_SESSION["UserId"])) //send user back to index if not logged in
{
    $_SESSION["Status"] = "You must be logged in to view that page.";
    $_SESSION["isRedirecting"] = true;
    header("Location:index.php");
}

//process button presses
if (isset($_POST["btnAccept"]))
{
    try
    {
        $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
        $retval = $client->UpdateUserContact(array('id'=>$_POST["txtInviteId"],'originUserId'=>$_POST["txtOwnerId"],'targetUserId'=>$_POST["txtTargetId"],'inviteStatusId'=>2));
        $_SESSION["Status"] = "You have successfully added " . $_POST["txtOwnerFullName"] . " to your contact list.";
        $_SESSION["GoodStatus"] = true;
    } catch (SoapFault $exception)
    {
        $_SESSION["Status"] = "Failed to add user to contacts - " . $exception->getMessage();
    }
}
else if (isset($_POST["btnDecline"]))
{
    try {
        $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
        $retval = $client->UpdateUserContact(array('id' => $_POST["txtInviteId"], 'originUserId' => $_POST["txtOwnerId"], 'targetUserId' => $_POST["txtTargetId"], 'inviteStatusId' => 1));
        $_SESSION["Status"] = "You have declined to add " . $_POST["txtOwnerFullName"] . " to your contact list.";
    } catch (SoapFault $exception)
    {
        $_SESSION["Status"] = "Failed to decline invite - " . $exception->getMessage();
    }
}
else if (isset($_POST["btnBlock"]))
{
    try {
        $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
        $retval = $client->UpdateUserContact(array('id' => $_POST["txtInviteId"], 'originUserId' => $_POST["txtOwnerId"], 'targetUserId' => $_POST["txtTargetId"], 'inviteStatusId' => 3));
        $_SESSION["Status"] = "You have BLOCKED " . $_POST["txtOwnerFullName"] . ".";
    } catch (SoapFault $exception)
    {
        $_SESSION["Status"] = "Failed to block user - " . $exception->getMessage();
    }
}

try //retrieve user invites
{
    $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
    $retval = $client->LoadInvitesForUser(array('id'=>$_SESSION["UserId"]));
    $inviteResultArray = $retval->LoadInvitesForUserResult->CUserContact;
} catch (SoapFault $exception)
{
    $_SESSION["Status"] = "Failed to load user invites - " . $exception->getMessage();
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
    <?php
    $numOfResults = count($inviteResultArray);

    for ($i = 0; $i < $numOfResults; $i++)
    {
        if ($numOfResults == 1)
        {
            $inviteId = $inviteResultArray->Id;
            $ownerUserId = $inviteResultArray->OwnerUserId;
            $targetUserId = $inviteResultArray->TargetUserId;
            $inviteStatusId = $inviteResultArray->InviteStatusId;
            $inviteStatusDescription = $inviteResultArray->InviteStatusDescription;
            $ownerUserFullName = $inviteResultArray->OwnerUserFullName;
            $targetUserFullName = $inviteResultArray->TargetUserFullName;
        }
        else
        {
            $inviteId = $inviteResultArray[$i]->Id;
            $ownerUserId = $inviteResultArray[$i]->OwnerUserId;
            $targetUserId = $inviteResultArray[$i]->TargetUserId;
            $inviteStatusId = $inviteResultArray[$i]->InviteStatusId;
            $inviteStatusDescription = $inviteResultArray[$i]->InviteStatusDescription;
            $ownerUserFullName = $inviteResultArray[$i]->OwnerUserFullName;
            $targetUserFullName = $inviteResultArray[$i]->TargetUserFullName;
        }

        echo '<form method="post"><table id="message" width="99%">
              <tr><td width="80%">You have a contact invite from ' . $ownerUserFullName . '.</td></tr>
              <tr><td width="20%">
              <input type="hidden" value="' . $inviteId . '" id="txtInviteId" name="txtInviteId">
              <input type="hidden" value="' . $ownerUserId . '" id="txtOwnerId" name="txtOwnerId">
              <input type="hidden" value="' . $ownerUserFullName . '" id="txtOwnerFullName" name="txtOwnerFullName">
              <input type="hidden" value="' . $targetUserId . '" id="txtTargetId" name="txtTargetId">
              <input type="submit" value="Accept" id="btnAccept" name="btnAccept"/>
              <input type="submit" value="Decline" id="btnDecline" name="btnDecline"/>
              <input type="submit" value="Block" id="btnBlock" name="btnBlock"/></td></tr>
              </table></form>';
    }



    ?>
</main>
<footer><?php include './includes/footer.php' ?></footer>
</body>
</html>