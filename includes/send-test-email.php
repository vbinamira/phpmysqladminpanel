<?php 
include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.email.php');
$MailChimp = new Email();
if (isset($_POST['cmpgnid'])&& isset($_POST['testmail']))
{	
	// get the 'id' variable from the URL
	$cmpgnid = strip_tags($_POST['cmpgnid']);
	$testmail = strip_tags($_POST['testmail']);
  	$MailChimp->sendTest($cmpgnid,$testmail);
}
?>