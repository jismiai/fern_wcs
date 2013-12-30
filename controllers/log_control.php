<?php
session_start();
if (!isset($_SESSION["isLogin"]) || !($_SESSION["isLogin"]==true)){
	include_once "../config.php";
	header('Location:'.$localurl."login.php");
}
?>