<?php 
include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.email.php');
$MailChimp = new Email();
	if(isset($_POST['fromname']) && isset($_POST['replyto']) && isset($_POST['title']) && isset($_POST['subject']) && isset($_POST['segment'])) 
	{
		$fromname = strip_tags($_POST['fromname']);
		$replyto = strip_tags($_POST['replyto']);
		$title = strip_tags($_POST['title']);
		$subject = strip_tags($_POST['subject']);
		$segmentid = strip_tags($_POST['segment']);
		$MailChimp->createCampaign($subject,$replyto,$fromname,$title,$segmentid);
	}
?>