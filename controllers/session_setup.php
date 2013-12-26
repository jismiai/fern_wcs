<?php
session_start();
$_SESSION["isLogin"] = true;
$_SESSION["name"] = $customer->firstName;
$_SESSION["email"] = $customer->email;
$_SESSION["customerID"] = $customer->internalId;
?>