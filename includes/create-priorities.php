<?php
//always require config file
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');

if(isset($_POST['submit'])) {
 //SQL INJECTION
 $prtyname = strip_tags($_POST['name']);
 $desc = strip_tags($_POST['description']);
 
 $prtyname = $DBcon->real_escape_string($prtyname);
 $desc = $DBcon->real_escape_string($desc);
 //QUERY FOR slug DUPLICATE CHECK
 $check_slug = $DBcon->query("SELECT name FROM user_priorities WHERE name='$prtyname'");
 $count=$check_slug->num_rows;
    //Check if slug exist
    if ($count==0) {
     
     $query = "INSERT INTO user_priorities(name,description,created_at, updated_at) VALUES('$prtyname','$desc',now(),now())";

     if ($DBcon->query($query)) {
            //Alert message
            $msg = "<div class='alert alert-success'>
            <span class='glyphicon glyphicon-info-sign'></span> &nbsp; New Priority Created
            </div>";
        } else {
            $msg = "<div class='alert alert-danger'>
            <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Error in creating priority !
            </div>";
        }
     
    } else {
        $msg = "<div class='alert alert-danger'>
        <span class='glyphicon glyphicon-info-sign'></span> &nbsp; This name is already in use!
       </div>";
      
    }

    $DBcon->close();
}

