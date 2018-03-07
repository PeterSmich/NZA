<!DOCTYPE html>
<?php
  ob_start();
  session_start();
  if(!isset($_SESSION['valid'])){
    $_SESSION['valid'] = false;
  }
  if( $_SESSION['valid'] == true){
    header("Location:" . $_SESSION['redirect_url']);
  }

  // Load the driver
  require_once("dist/rdb/rdb.php");

  // Connect to localhost
  $conn = r\connect('localhost');
  require_once("../masterscript.php");
?>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminNZ  | Bejelentkezés</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style>
    .login-page{
      background-color: #2f2f2f;
      color: #ffffff;
    }
    .login-box{
      background-color: #000000;
      color: #ffffff;
      border-radius: 1%;
    }
    .login-logo{
      background-color: #000000;
      color: #ffffff;
      border-radius: 100%;
    }
    .login-box-body{
    background-color: #0f0f0f;
    color: #ffffff;border-radius: 1%;
    }
    h1,
    b,
    b > a,
    h1 > small,
    ol > li > a > i{
      color:#ffffff;
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo" href="index.php">
    <a href="index.php" style="color: #054e70;"><b style="color: #098bc6;">Admin</b>NZ</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Kérlek jelentkezz be!</p>
     <div>
      <?php
        $msg = 'Töltse ki a mezőket!';
        $type_b = 'alert-warning';
        $type_p = 'fa-warning';
		

        if (isset($_POST['login']) && !empty($_POST['username'])
           && !empty($_POST['password'])) {
			   if($_POST['username'] == $masteruser && $_POST['password'] == $mesterpassword){
						$msg = runScript($conn);
					   if($msg != ""){
						  $type_b = 'alert-success';
						  $type_p = 'fa-check';
					   }else{
						  $msg = 'Script nem futott le...';
						  $type_b = 'alert-danger';
						  $type_p = 'fa-ban';
					   }
			   }else{
				try{
				   $result = r\db('nz_database')->table('admins')->filter(array('id' => $_POST['username']))->run($conn);
				   $num = r\db('nz_database')->table('admins')->filter(array('id' => $_POST['username']))->count()->run($conn);
				   $res = null;
				   foreach ($result as $doc){
					 $res = $doc;
				   }
				   if ($_POST['password'] == $res['userpassword'] && $num == 1) {
					  $_SESSION['valid'] = true;
					  $_SESSION['username'] = $_POST['username'];
					  $_SESSION['nickname'] = $res['nickname'];

					  $msg = 'Sikeres bejelentkezés!';
					  $type_b = 'alert-success';
					  $type_p = 'fa-check';
					  header('Refresh: 1; URL = ' . $_SESSION['redirect_url']);
				   }else {
					  $_SESSION['valid'] = false;
					  $_SESSION['username'] = 'anonymus';
					  $msg = 'Hibás email cím vagy jelszó!';
					  $type_b = 'alert-danger';
					  $type_p = 'fa-ban';
				   }
				}catch(Exception $e){
					  $msg = 'Nem tudtunk kapcsolódni az adatbázishoz :(';
					  $type_b = 'alert-danger';
					  $type_p = 'fa-ban';
				}
			}
        }
     ?>
    </div>
    <form role = "form"
            action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);
            ?>" method = "post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name = "username" placeholder="Felhasználónév" <?php if(isset($_POST['login']) && !empty($_POST['username'])){ echo 'value="'; echo $_POST['username']; echo '"';} ?>>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name = "password" placeholder="Jelszó" <?php if(isset($_POST['login']) && !empty($_POST['password'])){ echo 'value="'; echo $_POST['password']; echo '"';} ?>>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <button type="submit" name = "login" class="btn btn-primary btn-block btn-flat">Bejelentkezés</button>
    </form>

    <?php
      if(isset($_POST['login'])){
        echo
        '<div class="alert '; echo $type_b; echo ' alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <h4><i class="icon fa '; echo $type_p; echo '"></i> Bejelentkezés:</h4>
          <p>'; echo $msg; echo '</p>
        </div>';
      }
    ?>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>

</body>
</html>