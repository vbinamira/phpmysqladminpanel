<?php
session_start();
require_once 'includes/class.user.php';
$user = new USER();

if(isset($_POST['submit']))
{
 $email = $_POST['email'];
 
 $stmt = $user->runQuery("SELECT id FROM users WHERE email = ? LIMIT 1");
 $stmt->bind_param('s', $email);
 $stmt->execute();
 $stmt->bind_result($id);
 $stmt->fetch();
 $row = $stmt->num_rows;
 $stmt->close();
 
 if($id != 0) {
    $id = base64_encode($id);
    $code = md5(uniqid(rand()));
    $stmt = $user->runQuery("UPDATE users SET email_verification_code = ?, updated_at=now() WHERE email= ? ");
    $stmt->bind_param('ss', $code, $email);  
    $stmt->execute();
    $stmt->close();
    $message= "
          Hello , $email
          <br /><br />
          We got a request to reset your password, if you did this then just click the following link to reset your password, if not just ignore this email,
          <br /><br />
          Click Following Link To Reset Your Password 
          <br /><br />
          <a href='http://10.107.1.23:85/resetpass.php?id=$id&code=$code'>click here to reset your password</a>
          <br /><br />
          thank you :)
          ";
     $subject = "Password Reset";
     
     $user->send_mail($email,$message,$subject);
     
     $msg = "<div class='alert alert-success'>
        <button class='close' data-dismiss='alert'>&times;</button>
        <span class='glyphicon glyphicon-info-sign'></span>
        Password Reset email sent!
         </div>";
 } else {
    $msg = "<div class='alert alert-danger'>
        <button class='close' data-dismiss='alert'>&times;</button>
        <span class='glyphicon glyphicon-info-sign'></span>
        <strong>Sorry!</strong>&nbspThat email doesn't exist!
         </div>";
  }
 
 }
 

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="node_modules/admin-lte/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="node_modules/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="node_modules/ionicons/dist/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="node_modules/admin-lte/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="node_modules/admin-lte/plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="/"><b>Admin</b>LTE</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Forgot Password</p>
    <?php if(isset($msg)) { echo $msg; } 
      else {
         echo "<div class=\"alert alert-info\">
         <span class='glyphicon glyphicon-info-sign'></span>
    <strong>Enter your email address</strong><p> You will receive a link to create a new password via email!</p>
    </div>";  
      }
    ?>
    <form method="post">
      <div class="form-group has-feedback">
        <input name="email" id="email" type="email" class="form-control" placeholder="Email" required>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-12">
          <button name="submit" type="submit" class="btn btn-primary btn-block btn-flat">Send Reset Password Request</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    <a href="/" class="text-center">Go Back</a>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<div id="message" class="col-md-12"></div>
<!-- jQuery 2.2.3 -->
<script src="node_modules/admin-lte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="node_modules/admin-lte/bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="node_modules/admin-lte/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
