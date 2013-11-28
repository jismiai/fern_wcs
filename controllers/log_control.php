<?php
session_start();
if (!isset($_SESSION["isLogin"])){
	include_once "../templates/head_tag.php";
	header('Location:'.$localurl."login.php");
}
?>