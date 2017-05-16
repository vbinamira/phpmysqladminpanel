<?php
//ALWAYS
session_start();
include_once ($_SERVER['DOCUMENT_ROOT'].'/includes/class.privileged.php');
//if session id no longer exist return back to index
$user_home = new PrivilegedUser();

if(!$user_home->is_logged_in())
{
 $user_home->redirect('index.php');
}

//Query to get logged in user info to show in home page
$stmt = $user_home->runQuery("SELECT name, created_at FROM users WHERE id = ".$_SESSION['userSession']);
$stmt->execute();
$stmt->bind_result($name, $createdAt);
$stmt->fetch();
$stmt->close();

//Query to get logged in user info to show in home page
$stmt = $user_home->runQuery("SELECT company_name, title FROM user_infos WHERE user_id = ".$_SESSION['userSession']);
$stmt->execute();
$stmt->bind_result($cname, $title);
$stmt->fetch();
$stmt->close();