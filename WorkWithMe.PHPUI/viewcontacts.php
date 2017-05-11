<?php
session_start();

if (!isset($_SESSION["UserId"]))
{
    $_SESSION["Status"] = "You must be logged in to view that page.";
    $_SESSION["isRedirecting"] = true;
    header("Location:index.php");
}
/*
if (isset($_POST["btnSearch"]))
{
    if (!isset($_POST["txtUser"]) || empty($_POST["txtUser"]))
    {
        $_SESSION["Status"] = "You must provide a username.";
    }
    else {
        try {
            $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
            $retval = $client->SearchForUser(array('searchString' => $_POST["txtUser"], 'originUserId' => $_SESSION["UserId"]));
            $resultArray = $retval->SearchForUserResult->CUser;
        } catch (SoapFault $exception)
        {
            $_SESSION["Status"] = "Failed to search for user - " . $exception->getMessage();
        }
    }
}
else if (isset($_POST["btnAddContact"])) {
    try {
        $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
        $retval = $client->CreateUserContact(array('originUserId' => $_SESSION["UserId"], 'targetUserId' => $_POST["txtId"], 'inviteStatusId' => 0));
        $_SESSION["Status"] = "You have invited " . $_POST["txtName"] . " to be your contact.";
        $_SESSION["GoodStatus"] = true;
    } catch (SoapFault $exception)
    {
        $_SESSION["Status"] = "Failed to invite contact - " . $exception->getMessage();
    }
}*/

if (!isset($_POST["btnViewInfo"]))
{
    try {
        $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
        $retval = $client->LoadContactsForUser(array('id' => $_SESSION["UserId"]));
        $resultArray = $retval->LoadContactsForUserResult->CUserContact;
    } catch (SoapFault $exception)
    {
        $_SESSION["Status"] = "Error loading contacts - " . $exception->getTraceAsString() . ' - ' . $exception->getMessage();
    }
}
else //viewing contact info
{

}

?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>WorkWithMe - Invite A Contact</title>
        <link rel="stylesheet" type="text/css" href="./styles/base.css">
    </head>
    <body>
        <?php include './includes/header.php' ?>
        <hr/>
        <nav><?php include './includes/nav.php' ?></nav>
        <div id="rightNav"><?php include './includes/rightnav.php' ?></div>
        <main>
            <?php
                if (!isset($_POST["btnViewInfo"]))
                {
                    $numOfResults = count($resultArray);

                    if ($numOfResults == 0)
                    {
                        echo "Sorry - you don't have any contacts yet.  Try adding some users!";
                    }
                    else
                    {
                        echo '<fieldset>
                             <legend>Your Current Contacts</legend>';

                        for ($i = 0; $i < $numOfResults; $i++)
                        {
                            if ($numOfResults == 1)
                            {
                                $id = $resultArray->Id;
                                $ownerUserId = $resultArray->OwnerUserId;
                                $targetUserId = $resultArray->TargetUserId;
                                $inviteStatusId = $resultArray->InviteStatusId;
                                $ownerFullName = $resultArray->OwnerUserFullName;
                                $targetFullName = $resultArray->TargetUserFullName;
                                $inviteStatusDescription = $resultArray->InviteStatusDescription;
                            }
                            else
                            {
                                $id = $resultArray[$i]->Id;
                                $ownerUserId = $resultArray[$i]->OwnerUserId;
                                $targetUserId = $resultArray[$i]->TargetUserId;
                                $inviteStatusId = $resultArray[$i]->InviteStatusId;
                                $ownerFullName = $resultArray[$i]->OwnerUserFullName;
                                $targetFullName = $resultArray[$i]->TargetUserFullName;
                                $inviteStatusDescription = $resultArray[$i]->InviteStatusDescription;
                            }

                            if ($ownerUserId == $_SESSION["UserId"])   //person requesting is the owner of the invite
                            {
                                echo '    <table class="contactList">
                                          <tr>
                                              <td width="20%"> 
                                                  ' .  $targetFullName . '
                                              </td>
                                              <td width="80%">
                                                  <form method="post">
                                                      <input type="hidden" id="txtId" name="txtId" value="' . $id . '">
                                                      <input type="hidden" id="txtName" name="txtName" value="' . $targetFullName . '">
                                                      <input type="hidden" id="txtContactId" name="txtContactId" value="' . $targetUserId . '">
                                                      <input type="submit" class="fbCentered fbWideBlue" id="btnViewInfo" name="btnViewInfo" value="View Contact Info">
                                                      <input type="submit" class="fbCentered fbWideRed" id="btnDeleteContact" name="btnDeleteContact" value="Delete Contact">
                                                      <input type="submit" class="fbCentered fbWideRed" id="btnBlockContact" name="btnBlockContact" value="Delete/Block Contact">
                                                  </form>
                                              </td>
                                          </tr>
                                      </table>';
                            }
                            else      //the person requesting their contacts is the target - not the owner - of the invite
                            {
                                echo '    <table class="contactList">
                                          <tr>
                                              <td width="20%"> 
                                                  ' .  $ownerFullName . '
                                              </td>
                                              <td width="80%">
                                                  <form method="post">
                                                      <input type="hidden" id="txtId" name="txtId" value="' . $id . '">
                                                      <input type="hidden" id="txtName" name="txtName" value="' . $ownerFullName . '">
                                                      <input type="hidden" id="txtContactId" name="txtContactId" value="' . $ownerUserId . '">
                                                      <input type="submit" class="fbCentered fbWideBlue" id="btnViewInfo" name="btnViewInfo" value="View Contact Info">
                                                      <input type="submit" class="fbCentered fbWideRed" id="btnDeleteContact" name="btnDeleteContact" value="Delete Contact">
                                                      <input type="submit" class="fbCentered fbWideRed" id="btnBlockContact" name="btnBlockContact" value="Delete/Block Contact">
                                                  </form>
                                              </td>
                                          </tr>
                                      </table>';
                            }
                        }
                        echo '</fieldset>';
                    }
                }


            /*if (isset($_POST["btnSearch"]))
            {
                $numOfResults = count($resultArray);

                if ($numOfResults == 0)
                {
                    echo "Sorry - your search returned no results that aren't already invited or on your list.";
                }
                else
                {
                    echo '<fieldset>
                             <legend>Select User</legend>';

                    for ($i = 0; $i < $numOfResults; $i++)
                    {
                        if ($numOfResults == 1)
                        {
                            $id = $resultArray->Id;
                            $username = $resultArray->Username;
                            $firstName = $resultArray->FirstName;
                            $middleInitial = $resultArray->MiddleInitial;
                            $lastName = $resultArray->LastName;
                            $email = $resultArray->Email;
                        }
                        else
                        {
                            $id = $resultArray[$i]->Id;
                            $username = $resultArray[$i]->Username;
                            $firstName = $resultArray[$i]->FirstName;
                            $middleInitial = $resultArray[$i]->MiddleInitial;
                            $lastName = $resultArray[$i]->LastName;
                            $email = $resultArray[$i]->Email;
                        }

                        echo '<form method="post"><input type="hidden" id="txtId" name="txtId" value="' . $id . '">
                          <input type="hidden" id="txtName" name="txtName" value="' . $firstName . ' ' . $lastName . '">' .
                            $firstName . ' ' . $lastName . ' (' . $email . ') '
                            . '<input type="submit" id="btnAddContact" name="btnAddContact" value="Invite Contact">
                          </form><br/>';
                    }
                    echo '</fieldset>';
                }
            }
            else
            {
                echo '<form method="post">
                         <fieldset>
                             <legend>Search My Contacts</legend><br />
                             <label for="memberList">Member ID/Email:</label>
                             <input type="text" id="txtUser" name="txtUser" placeholder="member\'s user ID or email address" required><br /><br />

                             <input type="submit" id="btnSearch" name="btnSearch" value="Search My Contacts"> <! -- adds user to database -->
                         </fieldset><br />
                      </form>';
            }*/
            ?>

        </main>
        
        <footer><?php include './includes/footer.php' ?></footer>
    </body>
</html>