<?php 
include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.email.php');
$MailChimp = new Email();
if (isset($_POST['cmpgnid']))
{	
	// get the 'id' variable from the URL
	$cmpgnid = strip_tags($_POST['cmpgnid']);
  	$MailChimp->getChecklistbyCampaign($cmpgnid);
}
?>