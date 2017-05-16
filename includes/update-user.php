<?php
require ($_SERVER['DOCUMENT_ROOT'].'/includes/class.user.php');
// if the 'id' variable is set in the URL, we know that we need to edit a record
$user_info = new USER();

if (isset($_GET['id']))
{
	// if the form's submit button is clicked, we need to process the form
	if (isset($_POST['change-info']))
	{
	// make sure the 'id' in the URL is valid
		if (is_numeric($_POST['id']))
		{
		// get variables from the URL/form and sql injection prevention
			$id = strip_tags($_POST['id']);
			$uname = strip_tags($_POST['name']);
 			$email = strip_tags($_POST['email']);
 			$cmpnyname = strip_tags($_POST['company_name']);
 			$title = strip_tags($_POST['title']);
 			$desc = strip_tags($_POST['description']);

			// if everything is fine, update the record in the database
				if ($stmt = $user_info->runQuery("UPDATE users SET name = ?, email = ? updated_at=now() WHERE id=?"))
				{
					$stmt->bind_param("ssi", $uname, $email, $id );
					$stmt->execute();
					$stmt->close();
					$msg = "<div class='alert alert-success'>
             		<span class='glyphicon glyphicon-info-sign'></span> &nbsp; Profile Successfully Updated
           		 	</div>";
				}
				// show an error message if the query has an error
				else
				{
					$msg = "<div class='alert alert-danger'>
           					<span class='glyphicon glyphicon-info-sign'></span> &nbsp Users table not updated!
          					</div>";
				}
				
				if ($stmt = $user_info->runQuery("UPDATE user_infos SET company_name = ?, title = ?, description = ?,  updated_at=now() WHERE user_id= ? "))
				{
					$stmt->bind_param("sssi", $cmpnyname, $title, $desc, $id );
					$stmt->execute();
					$stmt->close();
					$msg = "<div class='alert alert-success'>
             		<span class='glyphicon glyphicon-info-sign'></span> &nbsp; Profile Successfully Updated
           		 	</div>";
           		 	header("refresh:3;edit-user.php?id=$id");
				}
				// show an error message if the query has an error
				else
				{
					$msg = "<div class='alert alert-danger'>
           					<span class='glyphicon glyphicon-info-sign'></span> &nbsp User Info Table not updated!
          					</div>";
				}
				/* redirect the user once the form is updated
				header("Refresh:1; url=http://localhost/user-list.php");*/
		}
		// if the 'id' variable is not valid, show an error message
		else
		{
			$msg = "<div class='alert alert-danger'>
             		<span class='glyphicon glyphicon-info-sign'></span> &nbsp; GET id not valid
           		 	</div>";
		}
	}
	// if the form hasn't been submitted yet, get the info from the database and show the form
	else
	{
	// make sure the 'id' value is valid
		if (is_numeric($_GET['id']) && $_GET['id'] > 0)
		{
		// get 'id' from URL to query
		$id = $_GET['id'];
			// get the recod from the database
			if($stmt = $user_info->runQuery("SELECT id, name, email, password FROM users WHERE id=?"))
			{
				$stmt->bind_param("i", $id);
				$stmt->execute();

				$stmt->bind_result($id, $uname, $email, $password);
				$stmt->fetch();
				// show the form
				$stmt->close();

			}
			// show an error if the query has an error
			else
			{
				$msg = "<div class='alert alert-danger'>
           					<span class='glyphicon glyphicon-info-sign'></span> &nbsp Could not get users table!
          					</div>";
			}

			if($stmt = $user_info->runQuery("SELECT company_name, title, description FROM user_infos WHERE user_id=?"))
			{
				$stmt->bind_param("i", $id);
				$stmt->execute();
				$stmt->bind_result($cmpnyname, $title, $desc);
				$stmt->fetch();
				// show the form
				$stmt->close();
			}
			// show an error if the query has an error
			else
			{
				$msg = "<div class='alert alert-danger'>
           					<span class='glyphicon glyphicon-info-sign'></span> &nbsp Could not get user_infos table!
          					</div>";
			}
		}
		// if the 'id' value is not valid, redirect the user back to the view.php page
		else
		{
			header("Location: ../index.php");
		}
	}
}

?>