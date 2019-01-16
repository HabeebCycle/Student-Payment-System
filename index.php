<?php
require_once ('../config/config.php');
if(isset($_SESSION['islog']) or isset($_SESSION['slog'])){
	unset($_SESSION['islog'], $_SESSION['usertype'], $_SESSION['email'], $_SESSION['uname'], $_SESSION['uid'], $_SESSION['name'], $_SESSION['phone']);
	if(isset($_SESSION['slog'])){
		unset($_SESSION['slog'],$_SESSION['country']);
	}
	if(isset($_SESSION['is_user'])){
		unset($_SESSION['is_user']);
	}
}else{

if (isset($_POST['logform'])) {
    // Initialize a session:

    if (empty($_POST['username'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $username = mysqli_real_escape_string($connection,$_POST['username']);
    }


    if (empty($_POST['password'])) {
        $f = 'fz1';
    } else {
        $password = $_POST['password'];
    }

    if (empty($f)){
		$req = mysqli_query($connection,"select * from admin where username='".$username."'");
		$dn = mysqli_fetch_array($req);
		if(($dn['password']==$password) and mysqli_num_rows($req)>0){
			if($dn['deleted']==0 and $dn['obd']==0){
				$_SESSION['uname'] = $username;
				$_SESSION['uid'] = $dn['id'];
				$_SESSION['name'] = $dn['name'];
				$_SESSION['usertype'] = $dn['usertype'];
				$_SESSION['email'] = $dn['email'];
				$_SESSION['phone'] = $dn['phone'];
				$_SESSION['islog'] = true;
				if($dn['usertype']==1 or $dn['usertype']==3 or $dn['usertype']==4){
					header('Location: admin');
				}elseif($dn['usertype']==2){
					header('Location: teach');
				}
			}else{
				$f = 'fz3';
				header('Location: ?erz='.$f);
			}
		}else{
			$req = mysqli_query($connection,"select * from student where username='".$username."'");
			$dn = mysqli_fetch_array($req);
			if(($dn['password']==$password) and mysqli_num_rows($req)>0){
				if($dn['deleted']==0){
					$_SESSION['uname'] = $username;
					$_SESSION['uid'] = $dn['id'];
					$_SESSION['name'] = $dn['name'];
					$_SESSION['usertype'] = 5;
					$_SESSION['email'] = $dn['email'];
					$_SESSION['phone'] = $dn['phone'];
					$_SESSION['country'] = $dn['country'];
					$_SESSION['slog'] = true;
					$_SESSION['islog'] = true;
					header('Location: student');

				}else{
					$f = 'fz3';
					header('Location: ?erz='.$f);
				}
			}else{
				$f = 'fz2';
				header('Location: ?erz='.$f);
			}
		}
	}else{
		header('Location: ?erz='.$f);
	}

}elseif(isset($_POST['signform'])){
	$username = mysqli_real_escape_string($connection,$_POST['username']);
	$email = mysqli_real_escape_string($connection,$_POST['email']);
	$password = $_POST['password'];
	$cpassword = $_POST['cpassword'];
	$gender = $_POST['gender'];
	$fname = $_POST['fname'];
	$phone = $_POST['phone'];
	$country = $_POST['country'];
	$info = mysqli_real_escape_string($connection,$_POST['info']);

	$dreg = date('d-m-Y',time());

	if(strlen($password)>=6){
		if ($password == $cpassword){
			$cem = mysqli_num_rows(mysqli_query($connection,"select username from student where username='$username'"));
			if($cem==0){
				$cet = mysqli_num_rows(mysqli_query($connection,"select username from admin where username='$username'"));
				if($cet==0){
					mysqli_query($connection,"insert into student (name,username,password,gender,email,phone,country,info,dreg) values ('$fname','$username','$password','$gender','$email','$phone','$country','$info','$dreg')");
					$f = 'fz7';
					header('Location: ?erz='.$f.'#signin');
				}else{
					$f = 'fz6';
					header('Location: ?erz='.$f.'#signup');
				}
			}else{
				$f = 'fz6';
				header('Location: ?erz='.$f.'#signup');
			}
		}else{
			$f = 'fz4';
			header('Location: ?erz='.$f.'#signup');
		}
	}else{
		$f = 'fz5';
		header('Location: ?erz='.$f.'#signup');
	}
}else{
@$msg=$_GET['erz'];
if(isset($msg)){
	if($msg == 'fz0'){
		$msg_err = 'ERROR! Username field is empty.';
	}elseif($msg == 'fz1'){
		$msg_err = 'ERROR! Password field is empty.';
	}elseif($msg == 'fz3'){
		$msg_err = 'ERROR! You have been banned from log in. Contact the administrator.';
	}elseif($msg == 'fz4'){
		$msg_err = 'ERROR! Password not the same. Check and try again';
	}elseif($msg == 'fz5'){
		$msg_err = 'ERROR! Your password must be more than 6 characters.';
	}elseif($msg == 'fz6'){
		$msg_err = 'ERROR! Username already exists, use another username.';
	}elseif($msg == 'fz7'){
		$msg_suc = 'Registration was successful. You can now login.';
	}else{
		$msg_err = 'ERROR! You have entered wrong username or password.';
	}
}
}}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Erudite Millennium :: Portal </title>

    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="login">

<!-- /Preloader -->
    <div class="">
	  <h1 style='font-family:Andalus;text-align:center;'> Erudite Millennium </h1>
	<div>
	<a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form name="login" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
              <h1>Login to portal</h1>
			  <?php if(isset($msg_err)){ ?>
				  <div style="text-align:center;background-color:pink;border:2px #f00 solid; color:#f00; font-family:times new roman; text-size:25px">
					<?php echo $msg_err; ?>
				  </div>
			  <?php } ?>
			  <?php if(isset($msg_suc)){ ?>
				  <div style="text-align:center;background-color:lightgreen;border:2px #00f solid; color:darkblue; font-family:times new roman; text-size:25px">
					<?php echo $msg_suc; ?>
				  </div>
			  <?php } ?>
              <div>
                <input type="text" name="username" class="form-control" placeholder="Username" required="" />
              </div>
              <div>
                <input type="password" name="password" class="form-control" placeholder="Password" required="" />
              </div>
              <div>
				<input type="hidden" name="logform" value="TRUE" />
                <button class="btn btn-default submit" name="submit" type="submit" value="Submit">Log in</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator"><p class="">Forgot password? send mail to info@eruditemillennium.com</p>
                <p class="change_link">New student?
                  <a href="#signup" class="to_register"><b> Sign up here </b></a> | <a href="../" class=""> Home </a>
                </p>

                <div class="clearfix"></div>
                <br />

                <div>
                  <a href="http://www.eruditemillennium.com">Erudite Millennium</a> &copy; 2017
                </div>
              </div>
            </form>
          </section>
        </div>

		<div id="register" class="animate form registration_form">
          <section class="login_content">
            <form name="register" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
              <h1>Create Account</h1>
			  <?php if(isset($msg_err)){ ?>
				  <div style="text-align:center;background-color:pink;border:2px #f00 solid; color:#f00; font-family:times new roman; text-size:25px">
					<?php echo $msg_err; ?>
				  </div>
			  <?php } ?>
			  <?php if(isset($msg_suc)){ ?>
				  <div style="text-align:center;background-color:lightgreen;border:2px #f00 solid; color:#00f; font-family:times new roman; text-size:25px">
					<?php echo $msg_suc; ?>
				  </div>
			  <?php } ?>
			  <div class="row">
				  <div class="col-xs-12 col-md-6">
				    <input type="text" name="username" class="form-control" placeholder="Username" required="" />
				  </div>
				  <div class="col-xs-12 col-md-6">
				    <input type="email" name="email" class="form-control" placeholder="E-mail" required="" />
				  </div>
			  </div>
			  <div class="row">
				  <div class="col-xs-12 col-md-6">
				    <input type="password" name="password" class="form-control" placeholder="Login Password" required="" />
				  </div>
				  <div class="col-xs-12 col-md-6">
					<input type="password" name="cpassword" class="form-control" placeholder="Confirm Password" required="" />
				  </div>
			  </div>
			  <div class="row">
				  <div class="col-xs-12 col-md-6">
				    <input type="text" name="fname" class="form-control" placeholder="Full Name" required="" />
				  </div>
				  <div class="col-xs-12 col-md-6">
					<input type="radio" name="gender" value="1" required="">&nbsp;Male&nbsp;&nbsp;&nbsp;
					<input type="radio" name="gender" value="2" required="">&nbsp;Female
				  </div>
			  </div>
			  <div class="row">
				  <div class="col-xs-12 col-md-6">
				    <input type="text" name="phone" class="form-control" placeholder="Phone Number" required="" />
				  </div>
				  <div class="col-xs-12 col-md-6">
					<input type="text" name="country" class="form-control" placeholder="Country" required="" />
				  </div>
			  </div>
              <div class="row">
				  <div class="col-xs-12">
					<textarea name="info" placeholder="Write any other information about yourself" class="form-control"></textarea>
				  </div>
			  </div><br/>
			  <div class="row">
              <div class="col-xs-12">
				<input type="hidden" name="signform" value="TRUE" />
                <button class="btn btn-default submit" name="submit" type="submit" value="Submit">Sign up</button>
              </div>
			  </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">Already a member ?
                  <a href="#signin" class="to_register"><b> Log in </b></a>
                </p>

                <div class="clearfix"></div>
                <br />

                <div>
                  <a href="http://www.eruditemillennium.com">Erudite Millennium</a> &copy; 2017
                </div>
              </div>
            </form>
          </section>
        </div>

      </div>
	  </div>
    </div>
  </body>
</html>
