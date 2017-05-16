<?php
// connect to the database
include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.user.php');
// confirm that the 'id' variable has been set
$delete_role = new USER();
if (isset($_POST['roleid']))
{ 
  $id = $_POST['roleid'];
  $delete_role->deleteRole($id); 
}

?>