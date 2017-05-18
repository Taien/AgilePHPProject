<?php
$_SESSION["UserId"] = $retval->DoLoginResult->Id;
$_SESSION["Username"] = $retval->DoLoginResult->Username;
$_SESSION["FirstName"] = $retval->DoLoginResult->FirstName;
$_SESSION["MiddleInitial"] = $retval->DoLoginResult->MiddleInitial;
$_SESSION["LastName"] = $retval->DoLoginResult->LastName;
$_SESSION["Zip"] = $retval->DoLoginResult->Zip;
$_SESSION["Address"] = $retval->DoLoginResult->Address;
$_SESSION["IsAddressPrivate"] = $retval->DoLoginResult->IsAddressPrivate;
$_SESSION["Email"] = $retval->DoLoginResult->Email;
$_SESSION["UserImgId"] = $retval->DoLoginResult->UserImgId;