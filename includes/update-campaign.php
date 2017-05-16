<?php  
include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.email.php');
$MailChimp = new Email();
if(isset($_POST['cmpgnid']) && isset($_POST['htmlcode']))
{
	$cmpgnid = strip_tags($_POST['cmpgnid']);
	$htmlcode = $_POST['htmlcode'];
	$MailChimp->updateContent($cmpgnid,$htmlcode);
}
?>