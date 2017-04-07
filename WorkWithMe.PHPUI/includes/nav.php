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
    $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
    $retval = $client->LoadInvitesForUser(array('id'=>$_SESSION["UserId"]));
    $resultArray = $retval->LoadInvitesForUserResult->CUserContact;
    echo '<ul>';
    if (count($resultArray) > 0) echo '<li><b><a href="contactinvite.php">Accept Invites (' . count($resultArray) . ')</a></b></li>';
    echo '<li><a href="updateuser.php">Update Profile</a></li>
          <li><a href="addcontact.php">Add Contacts</a></li>
          <li><a href="creategroup.php">Create a Group</a></li></ul><br/>';
}
?>
<ul>
    <li><a href="index.php">Me <?=$name?></a></li>
    <li>&#8600 My Workplace 1</li>
    <li>&#8600 &#8600 Workplace Subgroup</li>
</ul>