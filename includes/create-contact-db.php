<?php 
include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.user.php');
$contacts = new USER();

if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['priority']) && isset($_POST['contactid'])) 
{
	$fname = strip_tags($_POST['first_name']);
	$lname = strip_tags($_POST['last_name']);
	$email = strip_tags($_POST['email']);
	$prty = strip_tags($_POST['priority']);
	$contactid = strip_tags($_POST['contactid']);
	$stmt = $contacts->runQuery("SELECT first_name,last_name,email FROM contacts WHERE first_name = ? AND last_name = ? AND email = ?");
		$stmt->bind_param('sss',$fname,$lname,$email);
		$stmt->execute();
		$stmt->bind_result($chck_fname,$chck_lname,$chck_email);
		$stmt->fetch();
		if ($chck_fname == $fname) {
			echo "First Name is Already Taken";
		}
		elseif ($chck_lname == $lname) {
			echo "Last Name is Already Taken";
		}
		elseif ($chck_email == $email) {
			echo "Email is Already Taken";
		}
		else
		{
			$contacts->addContactDB($fname,$lname,$email,$prty,$contactid);
		}
}
else
{
	
}
?>