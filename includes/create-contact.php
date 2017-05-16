<?php 
include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.user.php');
$contacts = new USER();
	if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['priority'])) 
	{
		$fname = strip_tags($_POST['first_name']);
		$lname = strip_tags($_POST['last_name']);
		$email = strip_tags($_POST['email']);
		$prty = strip_tags($_POST['priority']);
		$contacts->addContact($email,$fname,$lname,$prty);
	}
?>