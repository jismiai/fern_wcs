<?php
session_start();
$_SESSION["isLogin"] = true;
$_SESSION["firstname"] = $customer->firstName;
$_SESSION["lastname"] = $customer->lastName;

$_SESSION["email"] = $customer->email;
$_SESSION["customerID"] = $customer->internalId;
$_SESSION["entityID"] = explode(" ",$customer->entityId);
$_SESSION["entityID"] = $_SESSION["entityID"][0];

$_SESSION['last_activity'] = time();
$_SESSION['expire_time'] = 30*60;
?>