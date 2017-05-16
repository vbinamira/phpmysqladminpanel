<?php
// connect to the database
include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.user.php');
// confirm that the 'id' variable has been set
$delete_user_show = new USER();
if (isset($_POST['showid']) && isset($_POST['contactid']) && isset($_POST['alctd_tickets']))
{ 
  $contactid = strip_tags($_POST['contactid']);
  $showid = strip_tags($_POST['showid']);
  $alctd_tickets = strip_tags($_POST['alctd_tickets']);
  $delete_user_show->addTotalComps($alctd_tickets,$showid);
  $delete_user_show->removeContactShows($showid,$contactid); 
}
?>
