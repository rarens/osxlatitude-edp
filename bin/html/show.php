<?php
//This file is used to show "pages" in EDP

	$i = $_GET['i'];
	include_once "../config.inc.php";
	include_once "header.inc.php";
	include_once "include/functions.edpweb.inc.php";

	include "$i"; 
?>


