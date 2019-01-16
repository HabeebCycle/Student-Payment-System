<?php
include_once("header.php");

if(isset($_POST['mosedt'])){
	$name = mysqli_real_escape_string($connection,$_POST['name']);
	$email = mysqli_real_escape_string($connection,$_POST['email']);
	$phone = mysqli_real_escape_string($connection,$_POST['phone']);

	$id = $_POST['mosid'];
	mysqli_query($connection,"update student set name='$name',phone='$phone',email='$email' where id=$id");
	echo "<script>alert('Profile updated successfully');</script>";

}elseif(isset($_POST['mosdel'])){
	$id = $_SESSION['uid'];
	$pass = mysqli_fetch_array(mysqli_query($connection,"select password from student where id=$id"))['password'];
	$pass1 = mysqli_real_escape_string($connection,$_POST['pass1']);
	$pass2 = mysqli_real_escape_string($connection,$_POST['pass2']);
	$pass3 = mysqli_real_escape_string($connection,$_POST['pass3']);

	if($pass == $pass1){
		if($pass2 == $pass3){
			if(strlen($pass2) > 5){
				mysqli_query($connection,"update student set password='$pass2' where id=$id");
				echo "<script>alert('Password updated successfully');</script>";
			}else{
				$cor = "ERROR!: Password must be upto <b>6 characters</b> or more";
			}
		}else{
			$cor = "ERROR!: New password not matched. Check and try again.";
		}
	}else{
		$cor = "ERROR!: Old password is not correct. Check and try again.";
	}

}
?>
<div class="right_col" role="main">
          <div class="">
		  <div class="clearfix"></div>

            <div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>User Profile</h2>
					&nbsp;&nbsp;<?php if(isset($cor)){ ?><center>
				  <div style="text-align:center;width:50%;background-color:#ccc;border:2px #f00 solid; color:#a0a; font-family:times new roman; text-size:25px">
					<?php echo $cor; ?>
				  </div></center>
			  <?php } ?>
                    <ul class="nav navbar-right panel_toolbox">
						<li>
							<button class="btn btn-success btn-xs" data-toggle="modal" data-target=".edt-cem-sm"><i class="fa fa-plus-circle"><b>Edit Profile</b></i></button>
						</li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <h3 class="text-muted font-13 m-b-30" >
                      Your profile information.
                    </h3>

					  <?php
						$qnx = mysqli_query($connection,"select * from student where id=".$_SESSION['uid']);
						$dn = mysqli_fetch_array($qnx);

					  ?>
                         User Name:<input type="text" name="name" value="<?php echo $dn['username']; ?>" class="form-control" disabled>
						 Password:<input type="text" name="name" value="******" class="form-control" disabled>
						 Full Name:<input type="text" name="name" value="<?php echo $dn['name']; ?>" class="form-control" disabled>
						 Email Address:<input type="text" name="name" value="<?php echo $dn['email']; ?>" class="form-control" disabled>
						 Phone Number:<input type="text" name="name" value="<?php echo $dn['phone']; ?>" class="form-control" disabled>
						 <br/><br/>
						 <center>
						 <button class="btn btn-danger btn-xs" data-toggle="modal" data-target=".del-cem-sm">Change Password</button>
						 </center>

							<div class="modal fade edt-cem-sm" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:15px;text-align:justify;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h4 class="modal-title" id="myModalLabel2">Edit Profile </h4>
										</div>
										<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
											<div class="modal-body">
												<h5>Fill all the fields</h5>
												Full Name:<input type="text" name="name" value="<?php echo $dn['name']; ?>" class="form-control" required="">
												Email Address<input type="text" name="email" value="<?php echo $dn['email']; ?>" class="form-control" required="">
												Phone Number<input type="text" name="phone" value="<?php echo $dn['phone']; ?>"  class="form-control"  required="">
												<div class="ln_solid"></div>
												<input type="hidden" name="mosedt" value="TRUE" />
												<input type="hidden" name="mosid" value="<?php echo $dn['id']; ?>" />
											</div>
											<div class="modal-footer">
											  <button type="submit" value="submit" name="submit" class="btn btn-primary submit" onclick="">Save</button>
											  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="modal fade del-cem-sm" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:18px;text-align:justify;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h4 class="modal-title" id="myModalLabel2">Change Password </h4>
										</div>
										<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
											<div class="modal-body">
												<h5></h5>
												<p style="color:purple;">Complete this form</p>
												Old Password<input type="password" name="pass1" placeholder="Enter your old password" class="form-control" required="">
												New Password<input type="password" name="pass2" placeholder="Enter your new password" class="form-control" required="">
												Repeat New Password<input type="password" name="pass3" placeholder="Repeat your new password"  class="form-control"  required="">
												<div class="ln_solid"></div>
												<input type="hidden" name="mosdel" value="TRUE" />
											</div>
											<div class="modal-footer">
											  <button type="submit" value="submit" name="submit" class="btn btn-primary submit" onclick="">Change Password</button>
											  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
											</div>
										</form>
									</div>
								</div>
							</div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

<?php include_once("footer.php"); ?>

  </body>
</html>
