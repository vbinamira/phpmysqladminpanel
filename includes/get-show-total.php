<?php 
include ($_SERVER['DOCUMENT_ROOT'].'/includes/class.shows.php');
$shows = new SHOW();
$shows->getshowtickets();
?>