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
            $retval = $client->CreatePost(array('posterId'=>$_SESSION["UserId"],'replyPostId'=>$_POST["postId"],'title'=>$_POST["txtTitle"],'content'=>strip_tags($_POST["txtMessage"],"<br><p><b><i><hr><u>"),'isSticky'=>false));
            $_SESSION["Status"] = "Successfully replied to message!";
            $_SESSION["GoodStatus"] = true;
            $_SESSION["isRedirecting"] = true;
            header("Location:index.php");
        } catch (SoapFault $exception)
        {
            //DoLogin returns null when the login fails
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
<main>
    <p>
        <?php
            echo '<form action="reply.php" method="post"><table id="message" width="99%">
            <tr><td width="100%"><h3>' . $title . '</h3><br/><div id="timestampInfo">Posted by ' . $ownerFullName . ' At ' . $timestamp . '</div><hr/></td></tr>
            <tr><td width="100%">'. $content . '</td></tr>
            </table></form>';

            echo '<form method="post" id="postForm">
            <input type="text" maxlength="50" id="txtTitle" name="txtTitle" required placeholder="Post Title"><br/>
            <textarea name="txtMessage" id="txtMessage" rows="5" required placeholder="enter message here"></textarea>
            <input type="hidden" name="postId" id="postId" value="' . $postId . '">
            <input type="submit" name="btnPost" id="btnPost" value="Post Reply">
            </form>';
        ?>
    </p>
</main>
<div id="rightNav"><?php include './includes/rightnav.php' ?></div>
<footer><?php include './includes/footer.php' ?></footer>
</body>
</html>