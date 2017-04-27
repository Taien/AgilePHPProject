<?php
session_start();
    if (!isset($_SESSION["UserId"]))
    {
        $_SESSION["Status"] = "You must be logged in to view that page.";
        $_SESSION["isRedirecting"] = true;
        header("Location:index.php");
    }

    if (!isset($_POST["btnReply"]) && !isset($_POST["btnPost"]))
    {
        $_SESSION["Status"] = "You can only access this page from the reply button.";
        $_SESSION["isRedirecting"] = true;
        header("Location:index.php");
    }
    else
    {
        $postId = $_POST["incomingPostId"];
        $title = $_POST["incomingTitle"];
        $content = $_POST["incomingContent"];
        $ownerUserId = $_POST["incomingOwnerId"];
        $targetGroupId = $_POST["incomingTargetGroupId"];
        $timestamp = $_POST["incomingTimestamp"];
        $ownerFullName = $_POST["incomingOwnerFullName"];
    }

    if (isset($_POST["btnPost"]))
    {
        try{
            $client = new SoapClient("http://wwmservice.azurewebsites.net/WorkWithMeService.svc?wsdl");
            $retval = $client->CreatePost(array('posterId'=>$_SESSION["UserId"],'replyPostId'=>$_POST["postId"],'title'=>"",'content'=>strip_tags($_POST["txtMessage"],"<br><p><b><i><hr><u>"),'isSticky'=>false));
            $_SESSION["Status"] = "Successfully replied to message!";
            $_SESSION["GoodStatus"] = true;
            $_SESSION["isRedirecting"] = true;
            header("Location:index.php");
        } catch (SoapFault $exception)
        {
            $_SESSION["Status"] = "Message failed to post - " . $exception->getMessage();
        }
    }

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>WorkWithMe - Reply to Message</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
            echo '<form action="reply.php" method="post"><table id="message" width="99%">
            <tr><td width="100%"><h3>' . $title . '</h3><br/><div id="timestampInfo">Posted by ' . $ownerFullName . ' At ' . $timestamp . '</div><hr/></td></tr>
            <tr><td width="100%">'. $content . '</td></tr>
            </table></form>';

            if (isset($postId))
            {
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

            echo '<form method="post" id="postForm">
            <textarea name="txtMessage" id="txtMessage" rows="5" required placeholder="enter message here"></textarea>
            <input type="hidden" name="postId" id="postId" value="' . $postId . '">
            <input type="submit" name="btnPost" id="btnPost" value="Post Reply">
            </form>';
        ?>
    </p>
</main>
<footer><?php include './includes/footer.php' ?></footer>
</body>
</html>