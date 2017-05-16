<?php
// if the 'id' variable is set in the URL, we know that we need to edit a record
$change_pass = new USER();
if (isset($_POST['change-pass']))
{
	// make sure the 'id' in the URL is valid
	if (is_numeric($_POST['id']))
	{
		$id = strip_tags($_POST['id']);
		$newpass = strip_tags($_POST['newpwd']);
		$cpass = strip_tags($_POST['cnfmpwd']);
		if ($cpass!==$newpass) 
		{
			$newpass= md5($newpass);
			$stmt = $change_pass->runQuery("UPDATE users SET password = ?, updated_at = now() WHERE id = ?");
			$stmt->bind_param('si', $newpass, $id);
			$stmt->execute();
			$stmt->close();
			
			$msgpass = "<div class='alert alert-success'>
			  <button class='close' data-dismiss='alert'>&times;</button>
			  <span class='glyphicon glyphicon-info-sign'></span>
			  <strong>Success!</strong><p>Password Changed</p>
			  </div>";
			header("refresh:3;edit-user.php?id=$id");
		} 
		else 
		{
       		$msgpass = "<div class='alert alert-danger'>
          	<span class='glyphicon glyphicon-info-sign'></span> &nbsp; Password does not match !
       		</div>";
   		}
	}
}


