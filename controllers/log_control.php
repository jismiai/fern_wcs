<?php
session_start();
if( $_SESSION['last_activity'] < time()-$_SESSION['expire_time'] ) { //have we expired?
    //redirect to logout.php
	session_destroy();
}

if (!isset($_SESSION["isLogin"]) || !($_SESSION["isLogin"]==true)){
	include_once "../config.php";
	header('Location:'.$localurl."login.php");
}
$_SESSION['last_activity'] = time();
?>