<?php
//ALWAYS
session_start();
include_once ($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');
//if session id no longer exist return back to index
if (!isset($_SESSION['userSession'])) {
 header("Location: index.php");
}
//Query to get logged in user info to show in home page
$query = $DBcon->query("SELECT * FROM users WHERE id=".$_SESSION['userSession']);
$userRow=$query->fetch_array();

?>