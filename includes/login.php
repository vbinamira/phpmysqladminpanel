<?php
require_once 'class.role.php';
require_once 'class.privileged.php';
//ALWAYS PUT SESSION START 
session_start();
$user_login = new PrivilegedUser();
//Create new instance of privilegedUser class

if($user_login->is_logged_in()!="")
{	
	$user_login->redirect('home.php');
}

if(isset($_POST['submit']))
{
 $email = strip_tags($_POST['email']);
 $password = strip_tags($_POST['password']);
  
 if($user_login->login($email,$password))
 {
  	$user_login->redirect('home.php');
 }
}
?>