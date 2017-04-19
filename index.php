<?php
session_start();


    if (isset($_POST["btnLogin"]))
    {
        try {
            $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
            $retval = $client->DoLogin(array('username'=>$_POST["txtUsername"],'password'=>$_POST["txtPassword"]));
            //var_dump($retval);
            //echo $retval->DoLoginResult->Id;
            $_SESSION["UserId"] = $retval->DoLoginResult->Id;
            $_SESSION["Username"] = $retval->DoLoginResult->Username;
            $_SESSION["FirstName"] = $retval->DoLoginResult->FirstName;
            $_SESSION["MiddleInitial"] = $retval->DoLoginResult->MiddleInitial;
            $_SESSION["LastName"] = $retval->DoLoginResult->LastName;
            $_SESSION["Zip"] = $retval->DoLoginResult->Zip;
            $_SESSION["Address"] = $retval->DoLoginResult->Address;
            $_SESSION["IsAddressPrivate"] = $retval->DoLoginResult->IsAddressPrivate;
            $_SESSION["Email"] = $retval->DoLoginResult->Email;
            $_SESSION["Error"] = "You have successfully logged in.";
        } catch (SoapFault $exception)
        {
            //DoLogin returns null when the login fails
            $_SESSION["Error"] = "Login failed, username/password combination is invalid.";
        }

    }
    elseif (isset($_POST["btnLogout"]))
    {
        $_SESSION["UserId"] = null;
        $_SESSION["Username"] = null;
        $_SESSION["FirstName"] = null;
        $_SESSION["MiddleInitial"] = null;
        $_SESSION["LastName"] = null;
        $_SESSION["Zip"] = null;
        $_SESSION["Address"] = null;
        $_SESSION["IsAddressPrivate"] = null;
        $_SESSION["Email"] = null;
        $_SESSION["Error"] = "You have been logged out.";
    }
    elseif (isset($_POST["btnPost"]))
    {
        try{
            $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
            $retval = $client->CreatePost(array('posterId'=>$_SESSION["UserId"],'title'=>$_POST["txtTitle"],'content'=>strip_tags($_POST["txtMessage"],"<br><p><b><i><hr><u>"),'isSticky'=>false));
        } catch (SoapFault $exception)
        {
            //DoLogin returns null when the login fails
            $_SESSION["Error"] = "Message failed to post for some reason: " . $exception->getMessage();
        }
    }
    ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=isset($_SESSION["UserId"]) ? 'WorkWithMe - My Messages' : 'WorkWithMe'?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="./styles/base.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>
<header><?php include './includes/header.php' ?></header>
<hr/>
<nav><?php include './includes/nav.php' ?></nav>
<div id="rightNav"><?php include './includes/rightnav.php' ?></div>
    
<main>
    <p>
        <?php
            if (isset($_SESSION["UserId"]))
            {
                echo '<form method="post" id="postForm">
                      <input type="text" maxlength="50" id="txtTitle" name="txtTitle" required placeholder="Post Title"><br/>
                      <textarea name="txtMessage" id="txtMessage" rows="5" required placeholder="enter message here"></textarea>
                      <input type="submit" name="btnPost" id="btnPost" value="Post Message">
                      </form>';

                try {
                    $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
                    $retval = $client->GetPostsForUser(array('userId'=>$_SESSION["UserId"]));
                    $resultArray = $retval->GetPostsForUserResult->CPost;
                } catch (SoapFault $exception)
                {
                    //DoLogin returns null when the login fails
                    $_SESSION["Error"] = "Failed to retrieve posts for user. Details: " . $exception->getMessage();
                }

                $numOfResults = count($resultArray);

                for ($i = 0; $i < $numOfResults; $i++)
                {
                    if ($numOfResults == 1)
                    {
                        $title = $resultArray->Title;
                        $content = $resultArray->Content;
                        $ownerUserId = $resultArray->OwnerUserId; //get the username from this later
                        $targetGroupId = $resultArray->TargetGroupId;
                        $timestamp = $resultArray->TimeStamp;
                        $ownerFullName = $resultArray->OwnerFullName;
                    }
                    else
                    {
                        $title = $resultArray[$i]->Title;
                        $content = $resultArray[$i]->Content;
                        $ownerUserId = $resultArray[$i]->OwnerUserId; //get the username from this later
                        $targetGroupId = $resultArray[$i]->TargetGroupId;
                        $timestamp = $resultArray[$i]->TimeStamp;
                        $ownerFullName = $resultArray[$i]->OwnerFullName;
                    }

                    echo '<form action="reply.php" method="post"></form><table id="message" width="99%">
                          <tr><td width="100%" colspan="2"><h3>' . $title . '</h3><br/><div id="timestampInfo">Posted by ' . $ownerFullName . ' At ' . $timestamp . '</div><hr/></td></tr>
                          <tr><td width="85%">'. $content . '</td>
                          <td width="15%"><input type="submit" value="Reply" id="btnReply" name="btnReply"/></td></tr>
                          </table></form>';
                }
            }
            else
            {
                include './includes/home.php';
            }
        ?>
    </p>
</main>

<footer><?php include './includes/footer.php' ?></footer>
</body>
</html>