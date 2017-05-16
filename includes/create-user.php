<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/class.user.php');

$reg_user = new USER();

if(isset($_POST['submit']))
{
  $uname = strip_tags($_POST['name']);
  $email = strip_tags($_POST['email']);
  $upass = strip_tags($_POST['password']);
  $cname = strip_tags($_POST['company_name']);
  $title = strip_tags($_POST['title']);
  $desc = strip_tags($_POST['description']);
  $code = md5(uniqid(rand()));
 
    if ($_POST["password"] == $_POST["confirmpassword"]) 
      {
           $stmt = $reg_user->runQuery("SELECT email FROM users WHERE email= ? ");
           $stmt->bind_param('s', $email);
           $stmt->execute();
           $stmt->bind_result($checkemail);
          $stmt->fetch();
           if ($email == $checkemail) {
                $msg = "<div class='alert alert-danger'>
                    <span class='glyphicon glyphicon-info-sign'></span> &nbsp; This email is already in use!
                   </div>";
           } 
           else 
           {
                $stmt->close();
                if ($reg_user->register($uname,$email,$upass,$code)) 
                {   
                 $id = $reg_user->lastID();

                 if(isset($id)) 
                  {
                    $stmt = $reg_user->createUserinfo($id,$cname,$title,$desc);
                  } 
                  else 
                  {
                   $msg = "<div class='alert alert-danger'>
                   <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Sorry, Query could not execute...
                   </div>";
                  }  

                 $key = base64_encode($id);
                 $id = $key;
          
                 $message = "     
                    Hello $uname,
                    <br /><br />
                    Welcome to Coding Cage!<br/>
                    To complete your registration  please , just click following link<br/>
                    <br /><br />
                    <a href='http://10.107.1.23:85/verify.php?id=$id&code=$code'>Click HERE to Activate :)</a>
                    <br /><br />
                    Thanks,";
                    
                 $subject = "Confirm Registration";
                    
                 $reg_user->send_mail($email,$message,$subject); 
                 $msg = "
                   <div class='alert alert-success'>
                    <button class='close' data-dismiss='alert'>&times;</button>
                    <span class='glyphicon glyphicon-info-sign'></span>
                    <strong>Success!</strong> Sending confirmation email to $email. 
                     </div>
                   ";
                }  
                else  
                {
                 $msg = "<div class='alert alert-danger'>
                    <span class='glyphicon glyphicon-info-sign'></span> &nbsp; sorry , Query could no execute...
                 </div>";
                } 
           
           }
        } 
        else 
        {
           $msg = "<div class='alert alert-danger'>
              <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Password does not match!
           </div>";
        }
}