<?php
	include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.user.php');
	$user_comps = new USER();
	if(isset($_POST['contactid']) && isset($_POST['showid']) && isset($_POST['alctd_tickets']) && isset($_POST['current_allocated'])) 
	{
		$contactid = strip_tags($_POST['contactid']);
		$showid = strip_tags($_POST['showid']);
		$alctd_tickets = strip_tags($_POST['alctd_tickets']);
		$current_allocated = strip_tags($_POST['current_allocated']);
		$user_comps->addComps($contactid,$showid,$alctd_tickets);
		if($current_allocated != 0) 
		{
			$user_comps->addTotalComps($current_allocated,$showid);
			$user_comps->subtractTotalComps($alctd_tickets,$showid);
		}
		else 
		{
			$user_comps->subtractTotalComps($alctd_tickets,$showid);
		}
	}
?>