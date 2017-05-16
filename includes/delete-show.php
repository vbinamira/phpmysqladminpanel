<?php
// connect to the database
include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.shows.php');
// confirm that the 'id' variable has been set
$delete_show = new SHOW();
if (isset($_POST['showid']) && isset($_POST['ticketid']))
{	
	// get the 'id' variable from the URL
	$ticketid = strip_tags($_POST['ticketid']);
	$showid = strip_tags($_POST['showid']);
  	$delete_show->deleteShow($showid);
  	$delete_show->deleteTickets($ticketid);  
}
?>
