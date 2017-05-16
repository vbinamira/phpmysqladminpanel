<?php 
include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.email.php');
$MailChimp = new Email();
if (isset($_POST['cmpgnid'])&& isset($_POST['scheduletime']))
{	
	// get the 'id' variable from the URL
	$cmpgnid = strip_tags($_POST['cmpgnid']);
	$schedule = strip_tags($_POST['scheduletime']);
  	$MailChimp->scheduleCampaign($cmpgnid,$schedule);
}
?>