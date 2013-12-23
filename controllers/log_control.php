<?php
session_start();
if (!isset($_SESSION["isLogin"])){
	include_once "../config.php";
	header('Location:'.$localurl."login.php");
}
?>