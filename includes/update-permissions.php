<?php
include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.user.php');
// if the 'id' variable is set in the URL, we know that we need to edit a record
$user_role = new USER();
if (isset($_GET['id']))
{
	// if the form's submit button is clicked, we need to process the form
	if (isset($_POST['submit']))
	{
	// make sure the 'id' in the URL is valid 
		if (is_numeric($_POST['id']))
		{
		// get variables from the URL/form and sql injection prevention
			$id = strip_tags($_POST['id']);
			$pname = strip_tags($_POST['name']);
			$slug = strip_tags($_POST['slug']);
			$desc = strip_tags($_POST['description']);
 			
 			$id = $DBcon->real_escape_string($id);
 			$pname = $DBcon->real_escape_string($pname);
 			$slug = $DBcon->real_escape_string($slug);
 			$desc = $DBcon->real_escape_string($desc);
			// if everything is fine, update the record in the database
				if ($stmt = $user_role->runQuery("UPDATE permissions SET name = ?, slug = ?, description = ?, updated_at=now() WHERE id=?"))
				{
					$stmt->bind_param("sssi", $pname, $slug, $desc,  $id );
					$stmt->execute();
					$stmt->close();
					$msg = "<div class='alert alert-success'>
             		<span class='glyphicon glyphicon-info-sign'></span> &nbsp; Permission Successfully Updated
           		 	</div>";
				}
				// show an error message if the query has an error
				else
				{
					$msg = "<div class='alert alert-danger'>
           					<span class='glyphicon glyphicon-info-sign'></span> &nbsp".$DBcon->connect_error. "!
          					</div>";
				}

				// redirect the user once the form is updated
				header('Refresh:3; url='.URL.'permissions.php');
			
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
			if($stmt = $user_role->runQuery("SELECT id, name, slug, description FROM permissions WHERE id=?"))
			{
				$stmt->bind_param("i", $id);
				$stmt->execute();

				$stmt->bind_result($id, $pname, $slug, $desc);
				$stmt->fetch();
				// show the form
				$stmt->close();
			}
			// show an error if the query has an error
			else
			{
				$msg = "<div class='alert alert-danger'>
           					<span class='glyphicon glyphicon-info-sign'></span> &nbsp".$DBcon->connect_error. "!
          					</div>";
			}
		}
		// if the 'id' value is not valid, redirect the user back to index page
		else
		{
			header("Location: index.php");
		}
	}
}

?>