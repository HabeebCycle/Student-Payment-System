<?php
include_once("header.php");

if (isset($_POST['memform'])) {
    // Initialize a session:

    if (empty($_POST['name'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $name = mysqli_real_escape_string($connection,$_POST['name']);
    }
	if (empty($_POST['phone'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $phone = mysqli_real_escape_string($connection,$_POST['phone']);
    }
	if (empty($_POST['email'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $email = mysqli_real_escape_string($connection,$_POST['email']);
    }
	if (empty($_POST['post'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $post = mysqli_real_escape_string($connection,$_POST['post']);
    }
	if ($_POST['dept'] == '-1') {//if the email supplied is empty
        $cor = "Please select the department"; $f = 'fz0';
    } else {
        $dept = mysqli_real_escape_string($connection,$_POST['dept']);
    }
	if (empty($f)){
		mysqli_query($connection,"insert into members (name,phone,email,dept,position) values ('$name','$phone','$email',$dept,'$post')");
		echo "<script>alert('New member registered successfully');</script>";
	}
}elseif(isset($_POST['memedt'])){
	// Initialize a session:

    if (empty($_POST['name'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $name = mysqli_real_escape_string($connection,$_POST['name']);
    }
	if (empty($_POST['phone'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $phone = mysqli_real_escape_string($connection,$_POST['phone']);
    }
	if (empty($_POST['email'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $email = mysqli_real_escape_string($connection,$_POST['email']);
    }
	if (empty($_POST['post'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $post = mysqli_real_escape_string($connection,$_POST['post']);
    }
	$dept = $_POST['dept'];
	if (empty($f)){
		$id = $_POST['memid'];
		mysqli_query($connection,"update members set name='$name',phone='$phone',email='$email',position='$post',dept=$dept where id=$id");
		echo "<script>alert('Member info updated successfully');</script>";
	}
}elseif(isset($_POST['memdel'])){
	$id = $_POST['memid'];
	$name = $_POST['memidn'];
	$cem1 = mysqli_num_rows(mysqli_query($connection,"select * from department where hod=$id"));
	if($cem1 > 0){
		$cor = "Member can not be deleted due to its directing of a department. Please remove from head of department.";
	}else{
		mysqli_query($connection,"update members set deleted=1 where id=$id");
		echo "<script>alert('Member $name deleted successfully');</script>";
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
                    <h2>List of Erudite Members<small>Details</small></h2>
					&nbsp;&nbsp;<?php if(isset($cor)){ ?><center>
				  <div style="text-align:center;width:30%;background-color:#ccc;border:2px #f00 solid; color:#a0a; font-family:times new roman; text-size:25px">
					<?php echo $cor; ?>
				  </div></center>
			  <?php } ?><?php if($_SESSION['usertype']!=1){ ?>
                    <ul class="nav navbar-right panel_toolbox">
						<li>
							<button class="btn btn-success btn-xs" data-toggle="modal" data-target=".new-cem-sm"><i class="fa fa-plus-circle"><b>Add New Member</b></i></button>
						</li>
                    </ul>
			  <?php } ?>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <p class="text-muted font-13 m-b-30" >
                      List of members under Erudite. Click 'Add New Member' button above to register a new member. Click on the 'Edit' button beside each member details to edit member's details.
                    </p>


<div class="modal fade new-cem-sm" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:15px;text-align:justify;">
		<div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			  </button>
			  <h4 class="modal-title" id="myModalLabel2">Register a new member</h4>
			</div>
			<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<div class="modal-body">
					<h5>Fill all the fields</h5>
					<input type="text" name="name" placeholder="Member Name" class="form-control" required="">
					<input type="text" name="phone" placeholder="Phone" class="form-control" required="">
					<input type="text" name="email" placeholder="Email" class="form-control" required="">
					<input type="text" name="post" placeholder="Position/Post Held" class="form-control" required="">
					<select name="dept" class="form-control"><option value="-1">Select department</option>
						<?php
						$qns = mysqli_query($connection,"SELECT * FROM department");
						while($dns = mysqli_fetch_array($qns)){ ?>
						<option value="<?php echo $dns['id']; ?>" title="<?php echo $dns['descr']; ?>"><?php echo $dns['name']; ?></option>
						<?php } ?>
					</select>
					<div class="ln_solid"></div>
					<input type="hidden" name="memform" value="TRUE" />
				</div>
				<div class="modal-footer">
				  <button type="submit" value="submit" name="submit" class="btn btn-primary submit" onclick="">Add Member</button>
				  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>


                    <table id="datatable-responsive" class="table table-striped jambo_table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Member name</th>
                          <th>Phone number</th>
                          <th>Email</th>
						  <th>Department</th>
                          <th>Position</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
					  <?php
						$qnx = mysqli_query($connection,"select * from members where deleted=0");
						while($dn = mysqli_fetch_array($qnx)){
							$tr1 = mysqli_fetch_array(mysqli_query($connection,"select name from department where id=".$dn['dept']));

					  ?>
                        <tr>
                          <td><?php echo $dn['name']; ?></td>
                          <td><?php echo $dn['phone']; ?></td>
                          <td><?php echo $dn['email']; ?></td>
						  <td><?php echo $tr1['name']; ?></td>
                          <td><?php echo $dn['position']; ?></td>
                          <td>
							<?php if($_SESSION['usertype']!=1){ ?>
							<button class="btn btn-warning btn-xs" data-toggle="modal" data-target=".edt-cem-sm-<?php echo $dn['id']; ?>">Edit</button>
							<?php } ?>
							<?php if($_SESSION['usertype']==3){ ?>
							<button class="btn btn-danger btn-xs" data-toggle="modal" data-target=".del-cem-sm-<?php echo $dn['id']; ?>">Delete</button>
							<?php } ?>
						  </td>
                        </tr>
						<div class="modal fade edt-cem-sm-<?php echo $dn['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:15px;text-align:justify;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h4 class="modal-title" id="myModalLabel2">Edit member <?php echo $dn['name']; ?></h4>
										</div>
										<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
											<div class="modal-body">
												<h5>Fill all the fields</h5>
												Name:<input type="text" name="name" value="<?php echo $dn['name']; ?>" class="form-control" required="">
												Phone:<input type="text" name="phone" value="<?php echo $dn['phone']; ?>" class="form-control" required="">
												Email:<input type="text" name="email" value="<?php echo $dn['email']; ?>" class="form-control" required="">
												Post Held:<input type="text" name="post" value="<?php echo $dn['position']; ?>" class="form-control" required="">
												Department<select name="dept" class="form-control"><option value="<?php echo $dn['dept']; ?>" selected=selected><?php echo $tr1['name']; ?></option>
													<?php
													$qns = mysqli_query($connection,"SELECT * FROM department where id != '".$dn['dept']."'");
													while($dns = mysqli_fetch_array($qns)){ ?>
													<option value="<?php echo $dns['id']; ?>"><?php echo $dns['name']; ?></option>
													<?php } ?>
												</select>
												<div class="ln_solid"></div>
												<input type="hidden" name="memedt" value="TRUE" />
												<input type="hidden" name="memid" value="<?php echo $dn['id']; ?>" />
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
										  <h4 class="modal-title" id="myModalLabel2">Delete member <?php echo $dn['name']; ?></h4>
										</div>
										<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
											<div class="modal-body">
												<h5></h5>
												<p style="color:purple;">Are you sure to delete member <?php echo $dn['name']; ?></p>
												<div class="ln_solid"></div>
												<input type="hidden" name="memdel" value="TRUE" />
												<input type="hidden" name="memid" value="<?php echo $dn['id']; ?>" />
												<input type="hidden" name="memidn" value="<?php echo $dn['name']; ?>" />
											</div>
											<div class="modal-footer">
											  <button type="submit" value="submit" name="submit" class="btn btn-primary submit" onclick="">Delete Member</button>
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
