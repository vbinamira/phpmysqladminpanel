<?php 
include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.user.php');
$MailChimp = new USER();
if(isset($_POST['contactid']))
{
	$contactid = strip_tags($_POST['contactid']);
	$MailChimp->deleteContact($contactid);
}
?>