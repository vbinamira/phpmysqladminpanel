<?php
// connect to the database
include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.user.php');
// confirm that the 'id' variable has been set
$delete_user = new USER();
if (isset($_POST['usrid']))
{ 
  $id = $_POST['usrid'];
  $delete_user->deleteUser($id); 
}
?>
