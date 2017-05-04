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
            $_SESSION["Status"] = "You have successfully logged in.";
            $_SESSION["GoodStatus"] = true;
        } catch (SoapFault $exception)
        {
            //DoLogin returns null when the login fails
            $_SESSION["Status"] = "Login failed, username/password combination is invalid. - " . $exception->getMessage();
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
        $_SESSION["Status"] = "You have been logged out.";
        $_SESSION["GoodStatus"] = true;
    }
    elseif (isset($_POST["btnPost"]))
    {
        try{
            $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
            $retval = $client->CreatePost(array('posterId'=>$_SESSION["UserId"],'targetGroupId'=>null,'replyPostId'=>null,'title'=>$_POST["txtTitle"],'content'=>strip_tags($_POST["txtMessage"],"<br><p><b><i><hr><u>"),'isSticky'=>false));
            $_SESSION["Status"] = "Successfully posted message!";
            $_SESSION["GoodStatus"] = true;
        } catch (SoapFault $exception)
        {
            //DoLogin returns null when the login fails
            $_SESSION["Status"] = "Message failed to post - " . $exception->getMessage();
        }
    }

    if (!isset($currentOffset))
    {
        $currentOffset = 0;
    }

    if (isset($_POST["btnPrev"]))
    {
        $currentOffset = $_POST["txtOffset"];
        if ($currentOffset > 0)
            $currentOffset -= 10;
        if ($currentOffset < 0) $currentOffset = 0;
    }
    else if (isset($_POST["btnNext"]))
    {
        $currentOffset = $_POST["txtOffset"];
        $currentOffset += 10;
    }
    ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=isset($_SESSION["UserId"]) ? 'WorkWithMe - My Messages' : 'WorkWithMe'?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" type="text/css" href="./styles/base.css">
</head>
<body>
<?php include './includes/header.php' ?>
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

                    if ($currentOffset > 0)
                    {
                        $retval = $client->GetOffsetPostsForUser(array('userId'=>$_SESSION["UserId"],'offset'=>$currentOffset));
                        $resultArray = $retval->GetOffsetPostsForUserResult->CPost;
                    }
                    else
                    {
                        $retval = $client->GetPostsForUser(array('userId'=>$_SESSION["UserId"]));
                        $resultArray = $retval->GetPostsForUserResult->CPost;
                    }
                } catch (SoapFault $exception)
                {
                    $_SESSION["Status"] = "Failed to retrieve posts for user - " . $exception->getMessage();
                }

                $numOfResults = count($resultArray);

                echo '<form method="post" id="prevNextForm"><input type="submit" name="btnPrev" id="btnPrev" value="Previous 10" ' . ($currentOffset == 0 ? 'disabled' : "") . '>
                      <input type="submit" name="btnNext" id="btnNext" value="Next 10" ' . ($numOfResults < 10 ? 'disabled': "") . '>
                      <input type="hidden" name="txtOffset" id="txtOffset" value="' . $currentOffset . '"></form>';

                for ($i = 0; $i < $numOfResults; $i++)
                {
                    if ($numOfResults == 1)
                    {
                        $postId = $resultArray->Id;
                        $title = $resultArray->Title;
                        $content = $resultArray->Content;
                        $ownerUserId = $resultArray->OwnerUserId; //get the username from this later
                        $targetGroupId = $resultArray->TargetGroupId;
                        $timestamp = $resultArray->TimeStamp;
                        $ownerFullName = $resultArray->OwnerFullName;
                    }
                    else
                    {
                        $postId = $resultArray[$i]->Id;
                        $title = $resultArray[$i]->Title;
                        $content = $resultArray[$i]->Content;
                        $ownerUserId = $resultArray[$i]->OwnerUserId; //get the username from this later
                        $targetGroupId = $resultArray[$i]->TargetGroupId;
                        $timestamp = $resultArray[$i]->TimeStamp;
                        $ownerFullName = $resultArray[$i]->OwnerFullName;
                    }

                    include './includes/timestring.php'; //sets $timeString, which decodes the timestamp

                    echo '<form action="reply.php" method="post"><table id="message" width="99%">
                        <tr><td width="100%" colspan="2"><h3>' . $title . '</h3><br/><div id="timestampInfo">Posted by ' . $ownerFullName . ' On ' . $timeString . '</div><hr/></td></tr>
                        <tr><td width="85%">'. $content . '</td>
                        <td width="15%">
                            <input type="hidden" value="' . $postId . '" id="incomingPostId" name="incomingPostId"/>
                            <input type="hidden" value="' . $title . '" id="incomingTitle" name="incomingTitle"/>
                            <input type="hidden" value="' . $content . '" id="incomingContent" name="incomingContent"/>
                            <input type="hidden" value="' . $ownerUserId . '" id="incomingOwnerId" name="incomingOwnerId"/>
                            <input type="hidden" value="' . $targetGroupId . '" id="incomingTargetGroupId" name="incomingTargetGroupId"/>
                            <input type="hidden" value="' . $timestamp . '" id="incomingTimestamp" name="incomingTimestamp"/>
                            <input type="hidden" value="' . $ownerFullName . '" id="incomingOwnerFullName" name="incomingOwnerFullName"/>
                            <input type="submit" value="Reply" id="btnReply" name="btnReply"/>
                        </td></tr>
                        </table></form>';

                    //display replies under post
                    try {
                        $replyRetval = $client->GetRepliesForPost(array('postId'=>$postId));
                        $replyResultArray = $replyRetval->GetRepliesForPostResult->CPost;
                    } catch (SoapFault $exception)
                    {
                        $_SESSION["Status"] = "Failed to retrieve replies for post - " . $exception->getMessage();
                    }

                    $numOfReplies = count($replyResultArray);
                    if ($numOfReplies > 0)
                    {
                        echo '<table id="replyMessage" width="96%">
                              <tr><td width="50" id="replyBar"></td>
                              <td id="replyContent">';
                        for ($j = 0; $j < $numOfReplies; $j++)
                        {
                            if ($numOfReplies == 1)
                            {
                                $replyContent = $replyResultArray->Content;
                                $timestamp = $replyResultArray->TimeStamp;
                                $replyOwnerFullName = $replyResultArray->OwnerFullName;
                            }
                            else
                            {
                                $replyContent = $replyResultArray[$j]->Content;
                                $timestamp = $replyResultArray[$j]->TimeStamp;
                                $replyOwnerFullName = $replyResultArray[$j]->OwnerFullName;
                            }

                            include './includes/timestring.php';

                            echo '<table width="100%">
                            <tr><td width="100%"><div id="timestampInfo">Posted by ' . $replyOwnerFullName . ' On ' . $timeString . '</div></td></tr>
                            <tr><td width="100%">'. $replyContent . '</td></tr>
                            </table>';
                        }

                        echo '</td></tr></table>';
                    }
                }

                echo '<form method="post" id="prevNextForm"><input type="submit" name="btnPrev" id="btnPrev" value="Previous 10" ' . ($currentOffset == 0 ? 'disabled' : "") . '>
                      <input type="submit" name="btnNext" id="btnNext" value="Next 10" ' . ($numOfResults < 10 ? 'disabled': "") . '>
                      <input type="hidden" name="txtOffset" id="txtOffset" value="' . $currentOffset . '"></form>';
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