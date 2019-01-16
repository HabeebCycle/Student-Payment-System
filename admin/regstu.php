<?php
include_once("header.php");

if (isset($_POST['funform'])) {
    // Initialize a session:

    $name = mysqli_real_escape_string($connection,$_POST['name']);
	$username = mysqli_real_escape_string($connection,$_POST['username']);
	$password = mysqli_real_escape_string($connection,$_POST['password']);
	$gender = mysqli_real_escape_string($connection,$_POST['gender']);
	$email = mysqli_real_escape_string($connection,$_POST['email']);
	$phone = mysqli_real_escape_string($connection,$_POST['phone']);
	$country = mysqli_real_escape_string($connection,$_POST['country']);
	$info = mysqli_real_escape_string($connection,$_POST['info']);

	$dreg = date('d-m-Y',time());
	if (empty($f)){
		$cem = mysqli_num_rows(mysqli_query($connection,"select username from student where username='$username'"));
		if($cem==0){
			$cet = mysqli_num_rows(mysqli_query($connection,"select username from admin where username='$username'"));
			if($cet==0){
				mysqli_query($connection,"insert into student (name,username,password,gender,email,phone,country,info,dreg) values ('$name','$username','$password','$gender','$email','$phone','$country','$info','$dreg')");
				echo "<script>alert('New student record added successfully');</script>";
			}else{
				$cor = "Username already exist, use another username.";
			}
		}else{
			$cor = "Username already exist, use another username.";
		}
	}
}elseif(isset($_POST['funedt'])){
	$name = mysqli_real_escape_string($connection,$_POST['name']);
	$username = mysqli_real_escape_string($connection,$_POST['username']);
	$password = mysqli_real_escape_string($connection,$_POST['password']);
	$gender = mysqli_real_escape_string($connection,$_POST['gender']);
	$email = mysqli_real_escape_string($connection,$_POST['email']);
	$phone = mysqli_real_escape_string($connection,$_POST['phone']);
	$country = mysqli_real_escape_string($connection,$_POST['country']);
	$info = mysqli_real_escape_string($connection,$_POST['info']);

	if (empty($f)){
		$id = $_POST['funid'];
		$usename = $_POST['funidn'];
		$cem = mysqli_num_rows(mysqli_query($connection,"select username from student where username='$username' and username!='$usename' and deleted=0"));
		if($cem>0){
			$cor = "Username already exist, use another username.";
		}else{
			mysqli_query($connection,"update student set name='$name',username='$username',password='$password',gender='$gender',email='$email',phone='$phone',country='$country',info='$info' where id=$id");
			echo "<script>alert('Student record updated successfully');</script>";
		}
	}
}elseif(isset($_POST['fundel'])){
	$id = $_POST['funid'];
	$name = $_POST['funidn'];
	mysqli_query($connection,"update student set deleted=1 where id=$id");
	echo "<script>alert('Student $name record deleted successfully');</script>";
}
?>
<div class="right_col" role="main">
          <div class="">
		  <div class="clearfix"></div>

            <div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>List of Registered Students<small>Details</small></h2>
					&nbsp;&nbsp;<?php if(isset($cor)){ ?><center>
				  <div style="text-align:center;width:30%;background-color:#ccc;border:2px #f00 solid; color:#a0a; font-family:times new roman; text-size:25px">
					<?php echo $cor; ?>
				  </div></center>
			  <?php } ?><?php if($_SESSION['usertype']!=1){ ?>
                    <ul class="nav navbar-right panel_toolbox">
						<li>
							<button class="btn btn-success btn-xs" data-toggle="modal" data-target=".new-cem-sm"><i class="fa fa-plus-circle"><b>Add New Student</b></i></button>
						</li>
                    </ul>
			  <?php } ?>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <p class="text-muted font-13 m-b-30">
                      List of students registered. Click 'Add New Student' button above to add a new student. Click on the 'Edit' button beside each funneral to edit its details.
                    </p>


<div class="modal fade new-cem-sm" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xs" style="color:#000;font-family:Andalus;font-size:15px;text-align:justify;">
		<div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			  </button>
			  <h4 class="modal-title" id="myModalLabel2">Register a new student <small>Fill all the fields</small></h4>
			</div>
			<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<div class="modal-body">
					<input type="text" name="name" placeholder="Student Name" class="form-control" required="">
					<input type="text" name="username" placeholder="Login Username" class="form-control" required="">
					<input type="password" name="password" placeholder="Login Password" class="form-control" required="">
					Gender: &nbsp;<input type="radio" name="gender" value="1" required="">&nbsp;Male&nbsp;&nbsp;
					<input type="radio" name="gender" value="2" required="">&nbsp;Female
					<input type="email" name="email" placeholder="E-mail Address" class="form-control" required="">
					<input type="text" name="phone" placeholder="Phone Number" class="form-control" required="">
					<input type="text" name="country" placeholder="Country" class="form-control" required="">
					<textarea name="info" placeholder="Write any other information/comment about the student" class="form-control"></textarea>
					<div class="ln_solid"></div>
					<input type="hidden" name="funform" value="TRUE" />
				</div>
				<div class="modal-footer">
				  <button type="submit" value="submit" name="submit" class="btn btn-primary submit" onclick="">Add</button>
				  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>


                    <table id="datatable-responsive" class="table table-striped jambo_table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Student Name</th>
						  <th>Username</th>
						  <th>Password</th>
                          <th>Gender</th>
                          <th>E-mail</th>
                          <th>Phone</th>
                          <th>Country</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
					  <?php
						$qnx = mysqli_query($connection,"select * from student where deleted=0");
						while($dn = mysqli_fetch_array($qnx)){
							$tr2 = $dn['gender']==1?"Male":"Female";
					  ?>
                        <tr>
                          <td><?php echo $dn['name']; ?></td>
						  <th><?php echo $dn['username']; ?></th>
						  <th><?php echo $dn['password']; ?></th>
                          <td><?php echo $tr2 ?></td>
                          <td><?php echo $dn['email']; ?></td>
                          <td><?php echo $dn['phone']; ?></td>
						  <th><?php echo $dn['country']; ?></th>
                          <td>
							<button class="btn btn-info btn-xs" data-toggle="modal" data-target=".det-cem-sm-<?php echo $dn['id']; ?>">Info</button>
							<?php if($_SESSION['usertype']!=1){ ?>
							<button class="btn btn-warning btn-xs" data-toggle="modal" data-target=".edt-cem-sm-<?php echo $dn['id']; ?>">Edit</button>
							<?php } ?>
							<?php if($_SESSION['usertype']==3){ ?>
							<button class="btn btn-danger btn-xs" data-toggle="modal" data-target=".del-cem-sm-<?php echo $dn['id']; ?>">Delete</button>
							<?php } ?>
						  </td>
                        </tr>
						<div class="modal fade det-cem-sm-<?php echo $dn['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-xs" style="color:#000;font-family:Andalus;font-size:18px;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h3 class="modal-title" id="myModalLabel2"><?php echo $dn['name']; ?> Info </h3>
										</div>
										<div class="modal-body">
										<h5></h5>
											<p style="color:darkblue;"><b>Other details are</b><br/><?php echo $dn['info']; ?>.</p>

											<div class="ln_solid"></div>
										</div>
										<div class="modal-footer">
										  <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
										</div>
									</div>
								</div>
							</div>
							<div class="modal fade edt-cem-sm-<?php echo $dn['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-xs" style="color:#000;font-family:Andalus;font-size:15px;text-align:justify;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h4 class="modal-title" id="myModalLabel2">Edit <?php echo $dn['name']; ?></h4>
										</div>
										<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
											<div class="modal-body">
												<h5>Fill all the fields</h5>
												Student Name<input type="text" name="name" value="<?php echo $dn['name']; ?>" class="form-control" required="">
												Login Username<input type="text" name="username" value="<?php echo $dn['username']; ?>" class="form-control" required="">
												Login Password<input type="password" name="password" value="<?php echo $dn['password']; ?>" class="form-control" required="">
												Gender: &nbsp;<input type="radio" name="gender" value="1" <?php echo $dn['gender']==1?'checked':''; ?> required="">&nbsp;Male&nbsp;&nbsp;<input type="radio" name="gender" value="2" <?php echo $dn['gender']==2?'checked':''; ?> required="">&nbsp;Female<br/>
												E-mail Address<input type="email" name="email" value="<?php echo $dn['email']; ?>" class="form-control" required="">
												Phone Number<input type="text" name="phone" value="<?php echo $dn['phone']; ?>" class="form-control" required="">
												Country<input type="text" name="country" value="<?php echo $dn['country']; ?>" class="form-control" required="">
												Other information/comment<textarea name="info" class="form-control"><?php echo $dn['info']; ?></textarea>
												<div class="ln_solid"></div>
												<input type="hidden" name="funedt" value="TRUE" />
												<input type="hidden" name="funid" value="<?php echo $dn['id']; ?>" />
												<input type="hidden" name="funidn" value="<?php echo $dn['username']; ?>" />
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
										  <h4 class="modal-title" id="myModalLabel2">Delete <?php echo $dn['name']; ?></h4>
										</div>
										<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
											<div class="modal-body">
												<h5></h5>
												<p style="color:purple;">Are you sure to delete <?php echo $dn['name']; ?> record?</p>
												<div class="ln_solid"></div>
												<input type="hidden" name="fundel" value="TRUE" />
												<input type="hidden" name="funid" value="<?php echo $dn['id']; ?>" />
												<input type="hidden" name="funidn" value="<?php echo $dn['name']; ?>" />
											</div>
											<div class="modal-footer">
											  <button type="submit" value="submit" name="submit" class="btn btn-primary submit" onclick="">Delete</button>
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
