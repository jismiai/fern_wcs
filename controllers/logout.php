<?php
session_start();
session_destroy();
include_once "../templates/head_tag.php";
header('Location:'.$localurl."login.php");
?>