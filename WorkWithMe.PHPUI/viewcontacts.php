<?php
session_start();

if (!isset($_SESSION["UserId"]))
{
    $_SESSION["Status"] = "You must be logged in to view that page.";
    $_SESSION["isRedirecting"] = true;
    header("Location:index.php");
}

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
    try {
        $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
        $retval = $client->GetUser(array('id' => $_POST["txtContactId"]));
        $result = $retval->GetUserResult;
        if ($result->UserImgId != null)
            $imageretval = $client->GetImageData(array('userImgId'=>$result->UserImgId));
        if (!$result->IsAddressPrivate)
        {
            $cityretval = $client->GetCityStateInfo(array('zip'=>$result->Zip));

            $city = $cityretval->GetCityStateInfoResult->CityName;
            $state = $cityretval->GetCityStateInfoResult->StateName;
        }
    } catch (SoapFault $exception)
    {
        $_SESSION["Status"] = "Error loading contacts - " . $exception->getTraceAsString() . ' - ' . $exception->getMessage();
    }

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
                else
                {
                    echo '<fieldset>
                              <legend>Contact\'s Info</legend>
                              <table class="userInfoTable" width="100%">
                                  <tr class="userInfoRow">
                                      <td>';
                                      if (isset($imageretval->GetImageDataResult))
                                      {
                                          echo '<img id="profileimage" src="data:image/jpeg;base64,' . base64_encode($imageretval->GetImageDataResult) . '" width="200" height="200"><br>';
                                      }
                    echo             '</td>
                                      <td width="99%">';
                                          echo $result->FirstName . ' ';
                                          if ($result->MiddleInitial != null) echo $result->MiddleInitial . '. ';
                                          echo $result->LastName . '<br>';
                                          if (!$result->IsAddressPrivate)
                                          {
                                              echo $result->Address . '<br>' . $city . ", " . $state . ' ' . $result->Zip . '<br>';
                                          }
                                          echo $result->Email;
                    echo             '</td>
                                  </tr>
                              </table>                             
                             
                             
                          </fieldset>';

                }
            ?>

        </main>
        
        <footer><?php include './includes/footer.php' ?></footer>
    </body>
</html>