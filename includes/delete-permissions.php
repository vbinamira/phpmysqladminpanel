<?php
// connect to the database
include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.user.php');
// confirm that the 'id' variable has been set
$delete_perm = new USER();
if (isset($_POST['permid']))
{ 
  $id = $_POST['permid'];
  $delete_perm->deletePermission($id); 
}
?>