<?php
// connect to the database
include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.user.php');
// confirm that the 'id' variable has been set
$delete_prty = new USER();
if (isset($_POST['prtyid']))
{ 
  $id = $_POST['prtyid'];
  $delete_prty->deletePriority($id); 
}
?>