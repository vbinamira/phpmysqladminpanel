<?php 
include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.user.php');
$shows = new USER();
$shows->getUsers();
?>