<?php
include_once("header.php");

if (isset($_POST['porform'])) {
    // Initialize a session:

	$username = mysqli_real_escape_string($connection,$_POST['username']);
    $name = mysqli_real_escape_string($connection,$_POST['name']);
	$email = mysqli_real_escape_string($connection,$_POST['email']);
	$password = mysqli_real_escape_string($connection,$_POST['password']);
	$phone = mysqli_real_escape_string($connection,$_POST['phone']);
	$info = mysqli_real_escape_string($connection,$_POST['info']);

	if ($_POST['usertype'] == '-1') {
        $cor = "Please select the user type"; $f = 'fz0';
    } else {
        $user = mysqli_real_escape_string($connection,$_POST['usertype']);
    }
	if (empty($f)){
		$cem = mysqli_num_rows(mysqli_query($connection,"select username from admin where username='$username'"));
		if($cem>0){
			$cor = "UserName $username already exist, use another username.";
		}else{
			$cem = mysqli_num_rows(mysqli_query($connection,"select username from student where username='$username'"));
			if($cem>0){
				$cor = "UserName $username already exist, use another username.";
			}else{
				mysqli_query($connection,"insert into admin (username,name,password,email,phone,info,usertype) values ('$username','$name','$password','$email','$phone','$info',$user)");
				echo "<script>alert('New portal user registered successfully');</script>";
			}
		}
	}
}elseif(isset($_POST['porapp'])){
	$id = $_POST['porid'];
	$whc = $_POST['whc'];
	mysqli_query($connection,"update admin set obd=$whc where id=$id");
}elseif(isset($_POST['poredt'])){
	$username = mysqli_real_escape_string($connection,$_POST['username']);
    $name = mysqli_real_escape_string($connection,$_POST['name']);
	$email = mysqli_real_escape_string($connection,$_POST['email']);
	$password = mysqli_real_escape_string($connection,$_POST['password']);
	$phone = mysqli_real_escape_string($connection,$_POST['phone']);
	$user = mysqli_real_escape_string($connection,$_POST['usertype']);
	$info = mysqli_real_escape_string($connection,$_POST['info']);

	$id = $_POST['porid'];
	$uname = $_POST['poridn'];
	$cem = mysqli_num_rows(mysqli_query($connection,"select username from admin where username='$username' and username!='$uname'"));
		if($cem>0){
			$cor = "UserName $username already exist, use another username.";
		}else{
			$cem = mysqli_num_rows(mysqli_query($connection,"select username from student where username='$username' and username!='$uname'"));
			if($cem>0){
				$cor = "UserName $username already exist, use another username.";
			}else{
				if($_SESSION['usertype']==3){
					mysqli_query($connection,"update admin set username='$username',info='$info',name='$name',phone='$phone',password='$password',email='$email',usertype=$user where id=$id");
				}else{
					mysqli_query($connection,"update admin set username='$username',info='$info',name='$name',phone='$phone',email='$email',usertype=$user where id=$id");
				}
				echo "<script>alert('User info updated successfully');</script>";
			}
		}

}elseif(isset($_POST['pordel'])){
	$id = $_POST['porid'];
	$name = $_POST['poridn'];
	mysqli_query($connection,"update admin set deleted=1 where id=$id");
	echo "<script>alert('User $name deleted successfully');</script>";
}
?>
<div class="right_col" role="main">
          <div class="">
		  <div class="clearfix"></div>

            <div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>List of Portal Users<small>Details</small></h2>
					&nbsp;&nbsp;<?php if(isset($cor)){ ?><center>
				  <div style="text-align:center;width:30%;background-color:#ccc;border:2px #f00 solid; color:#a0a; font-family:times new roman; text-size:25px">
					<?php echo $cor; ?>
				  </div></center>
			  <?php } ?><?php if($_SESSION['usertype']!=1){ ?>
                    <ul class="nav navbar-right panel_toolbox">
						<li>
							<button class="btn btn-success btn-xs" data-toggle="modal" data-target=".new-cem-sm"><i class="fa fa-plus-circle"><b>Add New User</b></i></button>
						</li>
                    </ul>
			  <?php } ?>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <p class="text-muted font-13 m-b-30" >
                      List of users under Erudite portal. Click 'Add New User' button above to register a new user. Click on the 'Edit' button beside each user details to edit user's details.
                    </p>


<div class="modal fade new-cem-sm" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:15px;text-align:justify;">
		<div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			  </button>
			  <h4 class="modal-title" id="myModalLabel2">Register a new user</h4>
			</div>
			<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<div class="modal-body">
					<h5>Fill all the fields</h5>
					<input type="text" name="username" placeholder="Username" class="form-control" required="">
					<input type="password" name="password" placeholder="Password" class="form-control" required="">
					<input type="text" name="name" placeholder="Full Name" class="form-control" required="">
					<input type="email" name="email" placeholder="Email Address" class="form-control" required="">
					<input type="text" name="phone" placeholder="Phone Number" class="form-control" required="">
					<select name="usertype" class="form-control">
						<option value="-1">Select user type</option>
						<option value="1">Moderate User</option>
						<option value="2">Facilitator</option><?php if($_SESSION['usertype']==3){ ?>
						<option value="3">Super User</option><?php } ?>
					</select>
					<textarea name="info" placeholder="Write any other information/comment about the user" class="form-control"></textarea>
					<div class="ln_solid"></div>
					<input type="hidden" name="porform" value="TRUE" />
				</div>
				<div class="modal-footer">
				  <button type="submit" value="submit" name="submit" class="btn btn-primary submit" onclick="">Add User</button>
				  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>


                    <table id="datatable-responsive" class="table table-striped jambo_table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Username</th>
						  <th>Fullname</th>
						  <th>Email</th>
                          <th>Phone No.</th>
						  <th>Password</th>
						  <th>User Type</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
					  <?php
						$qnx = mysqli_query($connection,"select * from admin where deleted=0");
						while($dn = mysqli_fetch_array($qnx)){

					  ?>
                        <tr <?php if($_SESSION['uname']==$dn['username']){echo 'bgcolor=lightyellow';} ?>>
                          <td><?php echo $dn['username']; ?></td>
                          <td><?php echo $dn['name']; ?></td>
                          <td><?php echo $dn['email']; ?></td>
                          <td><?php echo $dn['phone']; ?></td>
						  <td bgcolor='lightyellow'><font color='darkblue'><?php if($dn['usertype']==1 or ($_SESSION['usertype']==3 and $dn['usertype']!=3)){echo $dn['password'];}else{echo '********';} ?></font></td>
						  <td><?php if($dn['usertype']==3){echo 'Super User';}elseif($dn['usertype']==2){echo 'Facilitator';}else{echo 'Moderate User';} ?></td>
						  <td><?php echo $dn['obd']==0?'<font color=darkgreen>Active</font>':'<font color=darkred>Banned</font>'; ?></td>
						  <td>
							<?php if($_SESSION['usertype']!=1){ ?>
							<?php if($dn['usertype']==1 or ($_SESSION['usertype']==3 and $dn['usertype']!=3)){ ?>
							<button class="btn btn-warning btn-xs" data-toggle="modal" data-target=".app-cem-sm-<?php echo $dn['id']; ?>"><?php echo $dn['obd']==1?'LiftBan':'Ban'; ?></button>
							<button class="btn btn-info btn-xs" data-toggle="modal" data-target=".edt-cem-sm-<?php echo $dn['id']; ?>">Edit</button>
							<?php }} ?>
							<?php if($_SESSION['usertype']==3 and $dn['usertype']!=3){ ?>
							<button class="btn btn-danger btn-xs" data-toggle="modal" data-target=".del-cem-sm-<?php echo $dn['id']; ?>">Delete</button
							<?php } ?>
						   </td>
                        </tr>
						<div class="modal fade app-cem-sm-<?php echo $dn['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:18px;text-align:justify;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h4 class="modal-title" id="myModalLabel2">User Ban <?php echo $dn['name']; ?></h4>
										</div>
										<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
											<div class="modal-body">
												<h5></h5>
												<p style="color:purple;">Are you sure to <?php echo $dn['obd']==0?'ban ':'lift ban on ' ?> <?php echo $dn['name']; ?></p>
												<div class="ln_solid"></div>
												<input type="hidden" name="porapp" value="TRUE" />
												<input type="hidden" name="porid" value="<?php echo $dn['id']; ?>" />
												<input type="hidden" name="whc" value="<?php echo $dn['obd']==0?1:0; ?>" />
											</div>
											<div class="modal-footer">
											  <button type="submit" value="submit" name="submit" class="btn btn-primary submit" onclick=""><?php echo $dn['obd']==0?'Ban':'LiftBan on'; ?> User</button>
											  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="modal fade edt-cem-sm-<?php echo $dn['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:15px;text-align:justify;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h4 class="modal-title" id="myModalLabel2">Edit user <?php echo $dn['username']; ?> details</h4>
										</div>
										<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
											<div class="modal-body">
												<h5>Fill all the fields</h5>
												Username:<input type="text" name="username" value="<?php echo $dn['username']; ?>" class="form-control" required="">
												Full Name:<input type="text" name="name" value="<?php echo $dn['name']; ?>" class="form-control" required="">
												E-mail Address:<input type="email" name="email" value="<?php echo $dn['email']; ?>" class="form-control" required="">
												Phone Number<input type="text" name="phone" value="<?php echo $dn['phone']; ?>"  class="form-control" required="">
												Password:<input type="password" name="password" value="<?php if($_SESSION['usertype']==3){echo $dn['password'];}else{echo '********';} ?>" class="form-control" required="">
												User Type:<select name="usertype" class="form-control"><option value="<?php echo $dn['usertype']; ?>" selected=selected>
												<?php if($dn['usertype']==3){echo 'Super User';}elseif($dn['usertype']==2){echo 'Facilitator';}else{echo 'Moderate User';} ?></option>
													<option value="1">Moderate User</option>
													<option value="2">Facilitator</option><?php if($_SESSION['usertype']==3){ ?>
													<option value="3">Super User</option><?php } ?>
												</select>
												Other information/comment<textarea name="info" class="form-control"><?php echo $dn['info']; ?></textarea>
												<div class="ln_solid"></div>
												<input type="hidden" name="poredt" value="TRUE" />
												<input type="hidden" name="porid" value="<?php echo $dn['id']; ?>" />
												<input type="hidden" name="poridn" value="<?php echo $dn['username']; ?>" />
											</div>
											<div class="modal-footer">
											  <button type="submit" value="submit" name="submit" class="btn btn-primary submit" onclick="">Save</button>
											  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="modal fade del-cem-sm-<?php echo $dn['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:18px;text-align:justify;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h4 class="modal-title" id="myModalLabel2">Delete user <?php echo $dn['username']; ?></h4>
										</div>
										<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
											<div class="modal-body">
												<h5></h5>
												<p style="color:purple;">Are you sure to delete user <?php echo $dn['username']; ?></p>
												<div class="ln_solid"></div>
												<input type="hidden" name="pordel" value="TRUE" />
												<input type="hidden" name="porid" value="<?php echo $dn['id']; ?>" />
												<input type="hidden" name="poridn" value="<?php echo $dn['username']; ?>" />

											</div>
											<div class="modal-footer">

											  <button type="submit" value="submit" name="submit" class="btn btn-primary submit" onclick="">Delete User</button>
											  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						<?php } ?>
                      </tbody>
                    </table>


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
