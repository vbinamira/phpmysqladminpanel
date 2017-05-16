<?php 
	include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.user.php');
	$contact = new USER();
	// Needs to be update
	if(isset($_GET['id'])) {
		$id = $_GET['id'];
		$contact->getContactShowsbyID($id);
	}
?>