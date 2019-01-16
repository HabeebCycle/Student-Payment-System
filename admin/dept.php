<?php
include_once("header.php");

if (isset($_POST['cemform'])) {
    // Initialize a session:

    if (empty($_POST['cemname'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $cemname = mysqli_real_escape_string($connection,$_POST['cemname']);
    }
	if (empty($_POST['descr'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $descr = mysqli_real_escape_string($connection,$_POST['descr']);
    }
	$hod = mysqli_real_escape_string($connection,$_POST['hod']);
	//if($hod == -1){
	//	$hod == 0;
	//}

	if (empty($f)){
		$cem = mysqli_num_rows(mysqli_query($connection,"select name from department where name='$cemname'"));
		if($cem>0){
			$cor = "Department name already exist, use another name.";
		}else{
			mysqli_query($connection,"insert into department (name,descr,hod) values ('$cemname','$descr',$hod)");
			echo "<script>alert('New Department registered successfully');</script>";
		}
	}
}elseif(isset($_POST['cemedt'])){
	// Initialize a session:

    if (empty($_POST['cemname'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $cemname = mysqli_real_escape_string($connection,$_POST['cemname']);
    }
	if (empty($_POST['descr'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $descr = mysqli_real_escape_string($connection,$_POST['descr']);
    }
	if ($_POST['hod'] == '-1') {//if the email supplied is empty
        $cor = "Please select the Head of Department"; $f = 'fz0';
    } else {
        $hod = mysqli_real_escape_string($connection,$_POST['hod']);
    }
	if (empty($f)){
		$id = $_POST['cemid'];
		$name = $_POST['cemidn'];
		$cem = mysqli_num_rows(mysqli_query($connection,"select name from department where name='$cemname' and name!='$name'"));
		if($cem>0){
			$cor = "Department Name already exist, use another name.";
		}else{
			mysqli_query($connection,"update department set name='$cemname',descr='$descr',hod=$hod where id=$id");
			echo "<script>alert('Department updated successfully');</script>";
		}
	}
}elseif(isset($_POST['cemdel'])){
	$id = $_POST['cemid'];
	$name = $_POST['cemidn'];
	$cem1 = mysqli_num_rows(mysqli_query($connection,"select * from members where deleted=0 and dept=$id"));
	$cem2 = mysqli_num_rows(mysqli_query($connection,"select * from staff where dept=$id"));
	if($cem1 > 0){
		$cor = "Department can not be deleted due to its records in the members page. Please clear its record first.";
	}elseif($cem2 > 0){
		$cor = "Department can not be deleted due to its records in the staff page. Please clear its record first.";
	}else{
		mysqli_query($connection,"delete from department where id=$id");
		echo "<script>alert('Department $name deleted successfully');</script>";
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
                    <h2>List of Departments<small>Details</small></h2>
					&nbsp;&nbsp;<?php if(isset($cor)){ ?><center>
				  <div style="text-align:center;width:30%;background-color:#ccc;border:2px #f00 solid; color:#a0a; font-family:times new roman; text-size:25px">
					<?php echo $cor; ?>
				  </div></center>
			  <?php } ?><?php if($_SESSION['usertype']!=1){ ?>
                    <ul class="nav navbar-right panel_toolbox">
						<li>
							<button class="btn btn-success btn-xs" data-toggle="modal" data-target=".new-cem-sm"><i class="fa fa-plus-circle"><b>Add New Department</b></i></button>
						</li>
                    </ul>
			  <?php } ?>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <p class="text-muted font-13 m-b-30">
                      List of department available under Erudite. Click 'Add New Department' button above to register a new department. Click on the 'Edit' button beside each department to edit department. Each of the department name should be unique otherwise it won't accept the name.
                    </p>


<div class="modal fade new-cem-sm" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:15px;text-align:justify;">
		<div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			  </button>
			  <h4 class="modal-title" id="myModalLabel2">Register a new department</h4>
			</div>
			<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<div class="modal-body">
					<h5>Fill all the fields</h5>
					<input type="text" name="cemname" placeholder="Department Name" class="form-control" required="">
					<textarea name="descr" placeholder="Description of the department" class="form-control" required=""></textarea>
					<select name="hod" class="form-control"><option value="-1">Select Head of Department</option>
						<?php
						$qns = mysqli_query($connection,"SELECT * FROM members where deleted=0");
						while($dns = mysqli_fetch_array($qns)){ ?>
						<option value="<?php echo $dns['id']; ?>"><?php echo $dns['name']; ?></option>
						<?php } ?>
					</select>
					<div class="ln_solid"></div>
					<input type="hidden" name="cemform" value="TRUE" />
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
                          <th>Department name</th>
                          <th>Head</th>
						  <th>Staff</th>
                          <th>Description</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
					  <?php
						$qnx = mysqli_query($connection,"select * from department");
						while($dn = mysqli_fetch_array($qnx)){
							if($dn['hod']>0){
								$tr1 = mysqli_fetch_array(mysqli_query($connection,"select name from members where id=".$dn['hod']));
								$hodd = $tr1['name'];
							}else{
								$hodd = 'Attach HoD';
							}
							$tr2 = mysqli_num_rows(mysqli_query($connection,"select * from staff where dept = ".$dn['id']));

					  ?>
                        <tr>
                          <td><?php echo $dn['name']; ?></td>
                          <td><?php echo $hodd; ?></td>
                          <td><?php echo $tr2; ?></td>
                          <td><?php echo wordwrap(substr($dn['descr'],0,80).' ...',50,"<br>\n"); ?></td>
                          <td>
							<button class="btn btn-success btn-xs" data-toggle="modal" data-target=".det-cem-sm-<?php echo $dn['id']; ?>">Details</button>
							<?php if($_SESSION['usertype']!=1){ ?>
							<button class="btn btn-warning btn-xs" data-toggle="modal" data-target=".edt-cem-sm-<?php echo $dn['id']; ?>">Edit</button>
							<?php } ?>
							<?php if($_SESSION['usertype']==3){ ?>
							<button class="btn btn-danger btn-xs" data-toggle="modal" data-target=".del-cem-sm-<?php echo $dn['id']; ?>">Delete</button>
							<?php } ?>
						  </td>
                        </tr>
						<div class="modal fade det-cem-sm-<?php echo $dn['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:16px;text-align:center;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h3 class="modal-title" id="myModalLabel2"><?php echo $dn['name']; ?> Details </h3>
										</div>
										<div class="modal-body">
										<h5></h5>
											<p style="color:darkblue;"><?php echo $dn['name']; ?> <br/><?php echo $dn['descr']; ?>.<br/>It has total number of <?php echo $tr2; ?> staff with <?php echo $hodd; ?> as the head.</p>

											<div class="ln_solid"></div>
										</div>
										<div class="modal-footer">
										  <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
										</div>
									</div>
								</div>
							</div>
							<div class="modal fade edt-cem-sm-<?php echo $dn['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:15px;text-align:justify;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h4 class="modal-title" id="myModalLabel2">Edit <?php echo $dn['name']; ?></h4>
										</div>
										<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
											<div class="modal-body">
												<h5>Fill all the fields</h5>
												Name:<input type="text" name="cemname" value="<?php echo $dn['name']; ?>" class="form-control" required="">
												Description of the department<textarea name="descr" class="form-control" required=""><?php echo $dn['descr']; ?></textarea>
												Head of Department<select name="hod" class="form-control"><option value="<?php echo $dn['hod']; ?>" selected=selected><?php echo $hodd; ?></option>
													<?php
													$qns = mysqli_query($connection,"SELECT * FROM members where id != ".$dn['hod']);
													while($dns = mysqli_fetch_array($qns)){ ?>
													<option value="<?php echo $dns['id']; ?>"><?php echo $dns['name']; ?></option>
													<?php } ?>
												</select>
												<div class="ln_solid"></div>
												<input type="hidden" name="cemedt" value="TRUE" />
												<input type="hidden" name="cemid" value="<?php echo $dn['id']; ?>" />
												<input type="hidden" name="cemidn" value="<?php echo $dn['name']; ?>" />
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
												<p style="color:purple;">Are you sure to delete <?php echo $dn['name']; ?></p>
												<div class="ln_solid"></div>
												<input type="hidden" name="cemdel" value="TRUE" />
												<input type="hidden" name="cemid" value="<?php echo $dn['id']; ?>" />
												<input type="hidden" name="cemidn" value="<?php echo $dn['name']; ?>" />
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
