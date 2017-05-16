<?php
require_once 'includes/class.user.php';
$user = new USER();

if(empty($_GET['id']) && empty($_GET['code'])) {
    $user->redirect('index.php');
}
  if(isset($_GET['id']) && isset($_GET['code'])) {
      $id = base64_decode($_GET['id']);
      $code = $_GET['code'];
      $statusY = 1;
      $statusN = 0;
      $stmt = $user->runQuery("SELECT id, email_verified FROM users WHERE id = ? AND email_verification_code = ? LIMIT 1");
      $stmt->bind_param('is', $id, $code);
      $stmt->execute();
      $stmt->bind_result($newid, $emailverify);
      $stmt->fetch();
      $row = $stmt->num_rows;
      $stmt->close();
          if($emailverify == $statusN)
            {
              $stmt = $user->runQuery("UPDATE users SET email_verified = ?, updated_at=now() WHERE id = ? ");
              $stmt->bind_param('is', $statusY, $id);
              $stmt->execute(); 
              $stmt->close();
              $msg = " <div class='alert alert-success'>
                <button class='close' data-dismiss='alert'>&times;</button>
                <h4>Success!</h4><p>Your Account is Now Activated : <a href='index.php'>Login here</a></p>
                </div>"; 
            } else {
            $msg = "<div class='alert alert-error'>
            <button class='close' data-dismiss='alert'>&times;</button>
            <h4>Sorry!</h4><p>Your Account is already Activated : <a href='index.php'>Login here</a></p>
            </div>";
          } 
  }
  // Update Html Side
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
    
      <?php if(isset($msg)) { echo $msg; } ?>
    
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
