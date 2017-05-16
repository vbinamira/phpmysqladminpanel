<?php
//always require config file
require_once ($_SERVER['DOCUMENT_ROOT']."/includes/class.shows.php");
$create_show = new SHOW;
if(isset($_POST['submit'])) {
 //SQL INJECTION
 $shname = strip_tags($_POST['showname']);
 $shtime = strip_tags($_POST['showtime']);
 $shdate = strip_tags($_POST['showdate']);
 $ticket = strip_tags($_POST['tickets']);
 $ticketleft = $ticket;
 //QUERY FOR shtime DUPLICATE CHECK
 $check_shtime = $create_show->runQuery("SELECT * FROM shows WHERE show_name='$shname' AND show_times='$shtime' AND show_date='$shdate'");
 $count = $check_shtime->num_rows;
    //Check if shtime exist
    if ($count==0) 
    {
        if ($create_show->createShow($shname,$shtime,$shdate)) 
        {
            $show_id = $create_show->lastID();
            if(isset($show_id)) 
            {
                if($create_show->createTotalTicket($show_id,$ticketleft,$ticket)) 
                {
                    $msg = "<div class='alert alert-success'>
                    <span class='glyphicon glyphicon-info-sign'></span> &nbsp; New Show Created
                    </div>";
                } 
            }
            else 
            {
            $msg = "<div class='alert alert-danger'>
            <span class='glyphicon glyphicon-info-sign'></span> &nbsp; error in creating show !
            </div>";
            }
        }   
    } 
    else 
    {
        $msg = "<div class='alert alert-danger'>
        <span class='glyphicon glyphicon-info-sign'></span> &nbsp; A show for this time already exist!
       </div>";
    }
}