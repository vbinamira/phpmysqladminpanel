<?php 
include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.email.php');
$MailChimp = new Email();
$MailChimp->getScheduledCampaign();
?>