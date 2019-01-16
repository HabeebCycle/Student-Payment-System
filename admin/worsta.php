<?php
include_once("header.php");

if (isset($_POST['memform'])) {
    // Initialize a session:

    if (empty($_POST['name'])) {//if the address supplied is empty
        $f = 'fz0';
    } else {
        $name = mysqli_real_escape_string($connection,$_POST['name']);
    }
	if (empty($_POST['phone'])) {//if the address supplied is empty
        $f = 'fz0';
    } else {
        $phone = mysqli_real_escape_string($connection,$_POST['phone']);
    }
	if (empty($_POST['address'])) {//if the address supplied is empty
        $f = 'fz0';
    } else {
        $address = mysqli_real_escape_string($connection,$_POST['address']);
    }
	if (empty($_POST['post'])) {//if the address supplied is empty
        $f = 'fz0';
    } else {
        $post = mysqli_real_escape_string($connection,$_POST['post']);
    }
	if ($_POST['dept'] == '-1') {
        $cor = "Please select the department"; $f = 'fz0';
    } else {
        $dept = mysqli_real_escape_string($connection,$_POST['dept']);
    }
	$gender = mysqli_real_escape_string($connection,$_POST['gender']);

	if (empty($f)){
		mysqli_query($connection,"insert into staff (name,sex,phone,address,position,dept) values ('$name',$gender,'$phone','$address','$post',$dept)");
		echo "<script>alert('New staff registered successfully');</script>";
	}
}elseif(isset($_POST['memedt'])){
	// Initialize a session:

    if (empty($_POST['name'])) {//if the address supplied is empty
        $f = 'fz0';
    } else {
        $name = mysqli_real_escape_string($connection,$_POST['name']);
    }
	if (empty($_POST['phone'])) {//if the address supplied is empty
        $f = 'fz0';
    } else {
        $phone = mysqli_real_escape_string($connection,$_POST['phone']);
    }
	if (empty($_POST['address'])) {//if the address supplied is empty
        $f = 'fz0';
    } else {
        $address = mysqli_real_escape_string($connection,$_POST['address']);
    }
	if (empty($_POST['post'])) {//if the address supplied is empty
        $f = 'fz0';
    } else {
        $post = mysqli_real_escape_string($connection,$_POST['post']);
    }
	$dept = mysqli_real_escape_string($connection,$_POST['dept']);
	$gender = mysqli_real_escape_string($connection,$_POST['gender']);
	if (empty($f)){
		$id = $_POST['memid'];
		mysqli_query($connection,"update staff set name='$name',sex='$gender',phone='$phone',address='$address',position='$post',dept=$dept where id=$id");
		echo "<script>alert('Staff info updated successfully');</script>";
	}
}elseif(isset($_POST['memdel'])){
	$id = $_POST['memid'];
	$name = $_POST['memidn'];
	mysqli_query($connection,"update staff set deleted=1 where id=$id");
	echo "<script>alert('Staff $name deleted successfully');</script>";
}
?>
<div class="right_col" role="main">
          <div class="">
		  <div class="clearfix"></div>

            <div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>List of Erudite Staffs<small>Details</small></h2>
					&nbsp;&nbsp;<?php if(isset($cor)){ ?><center>
				  <div style="text-align:center;width:30%;background-color:#ccc;border:2px #f00 solid; color:#a0a; font-family:times new roman; text-size:25px">
					<?php echo $cor; ?>
				  </div></center>
			  <?php } ?><?php if($_SESSION['usertype']!=1){ ?>
                    <ul class="nav navbar-right panel_toolbox">
						<li>
							<button class="btn btn-success btn-xs" data-toggle="modal" data-target=".new-cem-sm"><i class="fa fa-plus-circle"><b>Add New Staff</b></i></button>
						</li>
                    </ul>
			  <?php } ?>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <p class="text-muted font-13 m-b-30" >
                      List of staff under Erudite. Click 'Add New Staff' button above to register a new staff. Click on the 'Edit' button beside each staff details to edit staff's details.
                    </p>


<div class="modal fade new-cem-sm" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:15px;text-align:justify;">
		<div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			  </button>
			  <h4 class="modal-title" id="myModalLabel2">Register a new staff</h4>
			</div>
			<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<div class="modal-body">
					<h5>Fill all the fields</h5>
					<input type="text" name="name" placeholder="Staff Name" class="form-control" required="">
					Gender: &nbsp;<input type="radio" name="gender" value="1" required="">&nbsp;Male&nbsp;&nbsp;
					<input type="radio" name="gender" value="2" required="">&nbsp;Female
					<input type="text" name="phone" placeholder="Phone" class="form-control" required="">
					<input type="text" name="address" placeholder="Address" class="form-control" required="">
					<input type="text" name="post" placeholder="Position/Job" class="form-control" required="">
					<select name="dept" class="form-control" onChange=""><option value="-1">Select working department</option>
						<?php
						$qns = mysqli_query($connection,"SELECT * FROM department");
						while($dns = mysqli_fetch_array($qns)){ ?>
						<option value="<?php echo $dns['id']; ?>"><?php echo $dns['name']; ?></option>
						<?php } ?>
					</select>
					<div class="ln_solid"></div>
					<input type="hidden" name="memform" value="TRUE" />
				</div>
				<div class="modal-footer">
				  <button type="submit" value="submit" name="submit" class="btn btn-primary submit" onclick="">Add Staff</button>
				  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>


                    <table id="datatable-responsive" class="table table-striped jambo_table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Staff name</th>
                          <th>Gender</th>
                          <th>Phone number</th>
                          <th>Home Address</th>
						  <th>Department</th>
                          <th>Job/Position</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
					  <?php
						$qnx = mysqli_query($connection,"select * from staff where deleted=0");
						while($dn = mysqli_fetch_array($qnx)){
							$tr1 = mysqli_fetch_array(mysqli_query($connection,"select name from department where id=".$dn['dept']));
							$tr2 = $dn['sex']==1?"Male":"Female";
					  ?>
                        <tr>
                          <td><?php echo $dn['name']; ?></td>
						  <td><?php echo $tr2 ?></td>
                          <td><?php echo $dn['phone']; ?></td>
                          <td><?php echo $dn['address']; ?></td>
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
										  <h4 class="modal-title" id="myModalLabel2">Edit staff <?php echo $dn['name']; ?></h4>
										</div>
										<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
											<div class="modal-body">
												<h5>Fill all the fields</h5>
												Name:<input type="text" name="name" value="<?php echo $dn['name']; ?>" class="form-control" required="">
												Gender: &nbsp;<input type="radio" name="gender" value="1" <?php echo $dn['sex']==1?'checked':''; ?> required="">&nbsp;Male&nbsp;&nbsp;
												<input type="radio" name="gender" value="2" <?php echo $dn['sex']==2?'checked':''; ?> required="">&nbsp;Female<br/>
												Phone:<input type="text" name="phone" value="<?php echo $dn['phone']; ?>" class="form-control" required="">
												Address:<input type="text" name="address" value="<?php echo $dn['address']; ?>" class="form-control" required="">
												Post Held:<input type="text" name="post" value="<?php echo $dn['position']; ?>" class="form-control" required="">
												Department<select name="dept" class="form-control" onChange="">
													<?php
													$qns = mysqli_query($connection,"SELECT * FROM department");
													while($dns = mysqli_fetch_array($qns)){ ?>
													<option value="<?php echo $dns['id']; ?>" <?php echo $dn['dept']==$dns['id']?'selected':''; ?>><?php echo $dns['name']; ?></option>
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
										  <h4 class="modal-title" id="myModalLabel2">Delete staff <?php echo $dn['name']; ?></h4>
										</div>
										<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
											<div class="modal-body">
												<h5></h5>
												<p style="color:purple;">Are you sure to delete staff <?php echo $dn['name']; ?></p>
												<div class="ln_solid"></div>
												<input type="hidden" name="memdel" value="TRUE" />
												<input type="hidden" name="memid" value="<?php echo $dn['id']; ?>" />
												<input type="hidden" name="memidn" value="<?php echo $dn['name']; ?>" />
											</div>
											<div class="modal-footer">
											  <button type="submit" value="submit" name="submit" class="btn btn-primary submit" onclick="">Delete Staff</button>
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
