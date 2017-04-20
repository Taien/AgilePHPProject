<?php
session_start();

//send user back to index if not logged in
if (!isset($_SESSION["UserId"])) header("Location:index.php");

if (isset($_POST["btnAccept"]))
{
    $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
    $retval = $client->UpdateUserContact(array('id'=>$_POST["txtInviteId"],'originUserId'=>$_POST["txtOwnerId"],'targetUserId'=>$_POST["txtTargetId"],'inviteStatusId'=>2));
    $_SESSION["Error"] = "You have successfully added " . $_POST["txtOwnerFullName"] . " to your contact list.";
}
else if (isset($_POST["btnDecline"]))
{
    $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
    $retval = $client->UpdateUserContact(array('id'=>$_POST["txtInviteId"],'originUserId'=>$_POST["txtOwnerId"],'targetUserId'=>$_POST["txtTargetId"],'inviteStatusId'=>1));
    $_SESSION["Error"] = "You have declined to add " . $_POST["txtOwnerFullName"] . " to your contact list.";
}
else if (isset($_POST["btnBlock"]))
{
    $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
    $retval = $client->UpdateUserContact(array('id'=>$_POST["txtInviteId"],'originUserId'=>$_POST["txtOwnerId"],'targetUserId'=>$_POST["txtTargetId"],'inviteStatusId'=>3));
    $_SESSION["Error"] = "You have BLOCKED " . $_POST["txtOwnerFullName"] . ".";
}

$client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
$retval = $client->LoadInvitesForUser(array('id'=>$_SESSION["UserId"]));
$inviteResultArray = $retval->LoadInvitesForUserResult->CUserContact;
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>WorkWithMe - Invites</title>
    <link rel="stylesheet" type="text/css" href="./styles/base.css">
</head>
<body>
<header><?php include './includes/header.php' ?></header>
<hr/>
<nav><?php include './includes/nav.php' ?></nav>
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
<div id="rightNav"><?php include './includes/rightnav.php' ?></div>
<footer><?php include './includes/footer.php' ?></footer>
</body>
</html>