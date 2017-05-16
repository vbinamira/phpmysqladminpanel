<?php 
include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.user.php');
$roles = new USER();
$roles->getAllPrty();
?>