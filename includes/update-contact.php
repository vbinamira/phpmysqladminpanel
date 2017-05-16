<?php 
include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.user.php');
$MailChimp = new USER();
if(isset($_POST['hash']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['priority'])) {

	$firstname = strip_tags($_POST['first_name']);
	$lastname = strip_tags($_POST['last_name']);
	$email = strip_tags($_POST['email']);
	$priority = strip_tags($_POST['priority']);
	$MailChimp->updateContacts($hash,$email,$firstname,$lastname,$priority);
}
?>