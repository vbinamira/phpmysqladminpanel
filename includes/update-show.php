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
			$shname = strip_tags($_POST['showname']);
			$shtime = strip_tags($_POST['showtime']);
			$shdate = strip_tags($_POST['showdate']);
			$ticket = strip_tags($_POST['tickets']);
 			
 			//check to see if there are duplicates
 			$check_shtime = $user_role->runQuery("SELECT * FROM shows WHERE show_name='$shname' AND show_times='$shtime' AND show_date='$shdate'");
 			$count=$check_shtime->num_rows;

 			if ($count==0){

 				// if everything is fine, update the record in the database
				if ($stmt = $user_role->runQuery("UPDATE shows SET show_name = ?, show_times = ?, show_date = ?, updated_at=now() WHERE id=?"))
				{
					$stmt->bind_param("sssi", $shname, $shtime, $shdate,  $id);
					$stmt->execute();
					$stmt->close();

					if ($stmt = $user_role->runQuery("UPDATE tickets SET total_tickets = ?, updated_at=now() WHERE show_id = ? "))
					{
					$stmt->bind_param("ii", $ticket,  $id );
					$stmt->execute();
					$stmt->close();
					$msg = "<div class='alert alert-success'>
             		<span class='glyphicon glyphicon-info-sign'></span> &nbsp; Show Successfully Updated
           		 	</div>";
					}
					// show an error message if the query has an error
					else
					{
					$msg = "<div class='alert alert-danger'>
           					<span class='glyphicon glyphicon-info-sign'></span> &nbsp There's something wrong, unable to process request!
          					</div>";
					}	
				}
				// redirect the user once the form is updated
				header('Refresh:1; url='.URL.'shows.php');
 			} else {
 				 $msg = "<div class='alert alert-danger'>
 				 <span class='glyphicon glyphicon-info-sign'></span> &nbsp; A show for this time already exist!
 				</div>";
 			}
			
		}
		// if the 'id' variable is not valid, show an error message
		else
		{
			$msg = "<div class='alert alert-danger'>
             		<span class='glyphicon glyphicon-info-sign'></span> &nbsp; No reference available
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
			// get the record from the database
			if($stmt = $DBcon->prepare("SELECT id, show_name, show_times, show_date FROM shows WHERE id=?"))
			{
				$stmt->bind_param("i", $id);
				$stmt->execute();
				$stmt->bind_result($id, $shname, $shtime, $shdate);
				$stmt->fetch();
				$stmt->close();

				if($stmt = $DBcon->prepare("SELECT  total_tickets FROM tickets WHERE show_id=?"))
				{
				$stmt->bind_param("i", $id);
				$stmt->execute();
				$stmt->bind_result($ticket);
				$stmt->fetch();
				$stmt->close();
				}
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