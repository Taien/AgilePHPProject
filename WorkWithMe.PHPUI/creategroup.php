<?php
session_start();
if (!isset($_SESSION["UserId"]))
{
    $_SESSION["Status"] = "You must be logged in to view that page.";
    $_SESSION["isRedirecting"] = true;
    header("Location:index.php");
}
else
{
    try {
        $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
        $resultArray = $client->GetGroupTypes(array())->GetGroupTypesResult->CGroupType;
    } catch (SoapFault $exception)
    {
        $_SESSION["Status"] = "Failed to retrieve group types - " . $exception->getMessage();
    }
}

if (isset($_POST["btnCreateGroup"])) {
    try {
        $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
        $createResult = $client->CreateGroup(array('name' => $_POST['txtGroupName'], 'description' => $_POST['txtGroupDescription'], 'grouptype' => $_POST['txtGroupType'], 'owneruserid' => $_SESSION['UserId'], 'ownergroupid' => null, 'canpostdefault' => isset($_POST['chkPostDefault']), 'caninvitedefault' => isset($_POST['chkInviteDefault']), 'candeletedefault' => isset($_POST['chkDeleteDefault'])));
        //$createResult = $client->CreateGroup(array('name' => 'Test', 'description' => 'Test', 'grouptype' => 'Test', 'owneruserid' => $_SESSION['UserId'], 'ownergroupid' => null, 'canpostdefault' => isset($_POST['chkPostDefault']), 'caninvitedefault' => isset($_POST['chkInviteDefault']), 'candeletedefault' => isset($_POST['chkDeleteDefault'])));
        $_SESSION["Status"] = "The group has been successfully created!";
        $_SESSION["GoodStatus"] = true;
        $_SESSION["isRedirecting"] = true;
        header("Location:index.php");
    } catch (SoapFault $exception) {
        $_SESSION["Status"] = "Failed to create group - " . $exception->getMessage();
    }
}

?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>WorkWithMe - Create A Group</title>
        <link rel="stylesheet" type="text/css" href="./styles/base.css">
    </head>
    <body>
        <?php include './includes/header.php' ?>
        <hr/>
        <nav><?php include './includes/nav.php' ?></nav>
        <div id="rightNav"><?php include './includes/rightnav.php' ?></div>
        <main>
            <form method="post">
                <fieldset>
                    <legend>Make a New Group</legend><br />

                    <label for="txtGroupName">Group Name:</label>
                    <input type="text" id="txtGroupName" name="txtGroupName" placeholder="enter group name" autofocus required><br/>

                    <label for="txtGroupDescription">Group Description:</label>
                    <input type="text" id="txtGroupDescription" name="txtGroupDescription" placeholder="enter group description" required><br />

                    <label for="txtGroupType">Group Type</label>
                    <select id="txtGroupType" name="txtGroupType">
                        <?php
                            $numOfResults = count($resultArray);

                            for ($i = 0; $i < $numOfResults; $i++)
                            {
                                if ($numOfResults <= 1)
                                {
                                    $id = $resultArray->Id;
                                    $description = $resultArray->Description;
                                }
                                else{
                                    $id = $resultArray[$i]->Id;
                                    $description = $resultArray[$i]->Description;
                                }

                                echo '<option value="' . $id . '">' . $description . '</option>';
                            }
                        ?>
                    </select><br/>

                    <label for="chkPostDefault">New Users Can Post:</label>
                    <input type="checkbox" name="chkPostDefault" id="chkPostDefault" value="CanPostDefault" checked><br/>

                    <label for="chkInviteDefault">New Users Can Invite:</label>
                    <input type="checkbox" name="chkInviteDefault" id="chkInviteDefault" value="CanInviteDefault" checked><br/>

                    <label for="chkDeleteDefault">New Users Can Delete:</label>
                    <input type="checkbox" name="chkDeleteDefault" id="chkDeleteDefault" value="CanDeleteDefault"><br/>

                    <br /><input type="submit" class="button" id="btnCreateGroup" name="btnCreateGroup" value="Create Group"> <!-- adds group to database and goes to addusers.php -->

                </fieldset><br />
            </form>
        </main>
        <footer><?php include './includes/footer.php' ?></footer>
    </body>
</html>
