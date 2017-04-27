<?php

if (isset($_SESSION["Username"]))
{
    $name = "(" . $_SESSION["Username"] . ")"; //replace this with their actual name later
}
else
{
    $name = "";
}

if (isset($_SESSION["UserId"]))
{
    try {
        $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
        $retval = $client->LoadInvitesForUser(array('id' => $_SESSION["UserId"]));
        $inviteResultArray = $retval->LoadInvitesForUserResult->CUserContact;
    } catch (SoapFault $exception)
    {
        $_SESSION["Status"] = "Failed to load invites for user - " . $exception->getMessage();
    }

    echo '<ul>';
    if (count($inviteResultArray) > 0) echo '<li><b><a href="contactinvite.php">Accept Invites (' . count($inviteResultArray) . ')</a></b></li>';
    echo '<li><a href="updateuser.php">Update Profile</a></li>
          <li><a href="viewcontacts.php">View Contacts</a></li>
          <li><a href="addcontact.php">Invite Contacts</a></li>
          <li><a href="creategroup.php">Create a Group</a></li>
          </ul><br/>';
}
?>
<ul>
    <li><a href="index.php">Me <?=$name?></a></li>
    <li>&#8600 My Workplace 1</li>
    <li>&#8600 &#8600 Workplace Subgroup</li>
</ul>