<?php 
include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.user.php');
$showperuser = new USER();
	if(isset($_POST['contactid']) && isset($_POST['showid'])) {
		$contactid = strip_tags($_POST['contactid']);
		$showid = strip_tags($_POST['showid']);
		$showperuser->addShowtoContact($contactid,$showid);
	}
?>