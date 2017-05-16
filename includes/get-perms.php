<?php 
include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.user.php');
$perms = new USER();
$perms->getAllPerm();
?>