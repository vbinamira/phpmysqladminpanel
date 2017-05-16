<?php
//always require config file
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');

if(isset($_POST['submit'])) {
 //SQL INJECTION
 $pname = strip_tags($_POST['name']);
 $slug = strip_tags($_POST['slug']);
 $desc = strip_tags($_POST['description']);
 
 $pname = $DBcon->real_escape_string($pname);
 $slug = $DBcon->real_escape_string($slug);
 $desc = $DBcon->real_escape_string($desc);
 //QUERY FOR slug DUPLICATE CHECK
 $check_slug = $DBcon->query("SELECT slug FROM permissions WHERE slug='$slug'");
 $count=$check_slug->num_rows;
    //Check if slug exist
    if ($count==0) {
     
     $query = "INSERT INTO permissions(name,slug,description,created_at, updated_at) VALUES('$pname','$slug','$desc',now(),now())";

     if ($DBcon->query($query)) {
            //Alert message
            $msg = "<div class='alert alert-success'>
            <span class='glyphicon glyphicon-info-sign'></span> &nbsp; New Permission Created
            </div>";
        } else {
            $msg = "<div class='alert alert-danger'>
            <span class='glyphicon glyphicon-info-sign'></span> &nbsp; error in creating permission !
            </div>";
        }
     
    } else {
        $msg = "<div class='alert alert-danger'>
        <span class='glyphicon glyphicon-info-sign'></span> &nbsp; This slug is already in use!
       </div>";
      
    }

    $DBcon->close();
}