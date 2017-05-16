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
		if (is_numeric($_POST['id']) && isset($_POST['priority']))
		{
		// get variables from the URL/form and sql injection prevention
			$id = $_POST['id'];
			$prtyid = $_POST['priority'];
			//Loop through array in order to find role id
 			foreach ($prtyid as $prty) {
 				if ($stmt = $user_role->runQuery("UPDATE user_priority_list SET usrpr_id = ?, updated_at=now() WHERE user_id = ?"))
				{
					$stmt->bind_param("ii", $prty, $id);
					$stmt->execute();
					$stmt->close();
					$msg = "<div class='alert alert-success'>
             		<span class='glyphicon glyphicon-info-sign'></span> &nbsp; User Priority Successfully Changed
           		 	</div>";
				}
				// show an error message if the query has an error
				else
				{
					$msg = "<div class='alert alert-danger'>
           					<span class='glyphicon glyphicon-info-sign'></span> &nbsp".$DBcon->connect_error. "!
          					</div>";
				}
 			}
				header('Refresh:1; url='.URL.'edit-user.php?id=$id'); 
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
		}
			// if the 'id' value is not valid, redirect the user back to the view.php page
		else
		{
			header("Location: ../index.php");
		}
	}
}

?>