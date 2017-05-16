<?php 
include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.user.php');
$MailChimp = new USER();
$MailChimp->getGroups();
?>