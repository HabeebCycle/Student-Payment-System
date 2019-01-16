<?php
include_once("header.php");

if (isset($_POST['cemform'])) {
    // Initialize a session:

    if (empty($_POST['cemname'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $cemname = mysqli_real_escape_string($connection,$_POST['cemname']);
    }
	if (empty($_POST['code'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $code = strtoupper(mysqli_real_escape_string($connection,$_POST['code']));
    }
	if (empty($_POST['descr'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $descr = mysqli_real_escape_string($connection,$_POST['descr']);
    }
	if (empty($_POST['price'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $price = mysqli_real_escape_string($connection,$_POST['price']);
    }
	if ($_POST['deno'] == '-1') {//if the email supplied is empty
        $cor = "Please select the currency denomination"; $f = 'fz0';
    } else {
        $deno = mysqli_real_escape_string($connection,$_POST['deno']);
    }
	$sub = mysqli_real_escape_string($connection,$_POST['gender']);
	if (empty($f)){
		$cem = mysqli_num_rows(mysqli_query($connection,"select code from courses where code='$code'"));
		if($cem>0){
			$cor = "Course code already exist, use another code.";
		}else{
			mysqli_query($connection,"insert into courses (name,code,descr,price,deno,sub) values ('$cemname','$code','$descr','$price','$deno',$sub)");
			echo "<script>alert('New Course registered successfully');</script>";
		}
	}
}elseif(isset($_POST['cemedt'])){
	// Initialize a session:

    if (empty($_POST['cemname'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $cemname = mysqli_real_escape_string($connection,$_POST['cemname']);
    }
	if (empty($_POST['code'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $code = strtoupper(mysqli_real_escape_string($connection,$_POST['code']));
    }
	if (empty($_POST['descr'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $descr = mysqli_real_escape_string($connection,$_POST['descr']);
    }
	if (empty($_POST['price'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $price = mysqli_real_escape_string($connection,$_POST['price']);
    }
	if ($_POST['deno'] == '-1') {//if the email supplied is empty
        $cor = "Please select the currency denomination"; $f = 'fz0';
    } else {
        $deno = mysqli_real_escape_string($connection,$_POST['deno']);
    }
	$sub = mysqli_real_escape_string($connection,$_POST['gender']);
	if (empty($f)){
		$id = $_POST['cemid'];
		$name = $_POST['cemidn'];
		$cem = mysqli_num_rows(mysqli_query($connection,"select code from courses where code='$code' and code!='$name' and deleted=0"));
		if($cem>0){
			$cor = "Course code already exist, use another code.";
		}else{
			mysqli_query($connection,"update courses set name='$cemname',code='$code',descr='$descr',deno='$deno',price='$price',sub=$sub where id=$id");
			echo "<script>alert('Course updated successfully');</script>";
		}
	}
}elseif(isset($_POST['cemdel'])){
	$id = $_POST['cemid'];
	$name = $_POST['cemidn'];
	$cem1 = mysqli_num_rows(mysqli_query($connection,"select * from lessons where course=$id"));
	if($cem1 > 0){
		$cor = "Course can not be deleted due to its records in the lesson page. Please clear its record first.";
	}else{
		mysqli_query($connection,"update courses set deleted=1 where id=$id");
		echo "<script>alert('Course $name deleted successfully');</script>";
	}
}
?>

<script type="text/javascript">
function isnum(ele){
    var r=/\D$/i;
    if(r.test(ele.value)){
        alert("This Field only allows a whole digit.");
        ele.value="";
        ele.focus();
    }else{
		//document.mosquer.price.value = document.mosquer.price.value + ele.value;
	}
}
function place(val){
	document.mosquer.price.placeholder = "Enter the price in "+ val +" currency";
}
</script>

<div class="right_col" role="main">
          <div class="">
		  <div class="clearfix"></div>

            <div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>List of Courses<small>Details</small></h2>
					&nbsp;&nbsp;<?php if(isset($cor)){ ?><center>
				  <div style="text-align:center;width:30%;background-color:#ccc;border:2px #f00 solid; color:#a0a; font-family:times new roman; text-size:25px">
					<?php echo $cor; ?>
				  </div></center>
			  <?php } ?><?php if($_SESSION['usertype']!=1){ ?>
                    <ul class="nav navbar-right panel_toolbox">
						<li>
							<button class="btn btn-success btn-xs" data-toggle="modal" data-target=".new-cem-sm"><i class="fa fa-plus-circle"><b>Add New Course</b></i></button>
						</li>
                    </ul>
			  <?php } ?>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <p class="text-muted font-13 m-b-30">
                      List of courses available under Erudite. Click 'Add New Course' button above to register a new course. Click on the 'Edit' button beside each course to edit it. Each of the course code should be unique otherwise it won't accept the code.
                    </p>


<div class="modal fade new-cem-sm" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:15px;text-align:justify;">
		<div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			  </button>
			  <h4 class="modal-title" id="myModalLabel2">Register a new course</h4>
			</div>
			<form class="form-horizontal form-label-left" name="mosquer" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<div class="modal-body">
					<h5>Fill all the fields</h5>
					<input type="text" name="cemname" placeholder="Course Name" class="form-control" required="">
					<input type="text" name="code" placeholder="Course Code" class="form-control" required=""><br/>
					<textarea name="descr" placeholder="Description of the course" class="form-control" required=""></textarea>	<br/>
					<select name="deno" class="form-control" onselect="place(this.value);"><option value="-1">Select price denomination</option>
						<option value="&#8358;">&#8358; Nigerian Naira</option>
						<option value="$">$ US Dollar</option>
						<option value="&pound;">&pound; GB Pound</option>
						<option value="&euro;">&euro; European Euro</option>
						<option value="&#8373;">&#8373; Ghanian Cedi</option>
						<option value="&#8377;">&#8377; Indian Rupee</option>
					</select>
					<input type="text" name="price" placeholder="Price in currency above" class="form-control" onkeyup="isnum(this)" autocomplete='off' required="">
					Subscription: &nbsp;<input type="radio" name="gender" value="0" checked required="">&nbsp;Once&nbsp;&nbsp;
					<input type="radio" name="gender" value="1" required="">&nbsp;Monthly
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
                          <th>Course Name</th>
                          <th>Course Code</th>
                          <th>Price</th>
                          <th>Lessons</th>
                          <th>Facilitators</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
					  <?php
						$qnx = mysqli_query($connection,"select * from courses where deleted=0");
						while($dn = mysqli_fetch_array($qnx)){
							$fac = mysqli_query($connection, "select distinct teacher from lessons where course=".$dn['id']);
							$tr2 = mysqli_num_rows(mysqli_query($connection,"select * from lessons where course = ".$dn['id']));

					  ?>
                        <tr>
                          <td><?php echo $dn['name']; ?></td>
                          <td><?php echo $dn['code']; ?></td>
                          <td><?php echo $dn['deno'].' '.$dn['price'].' '.($dn['sub']==0?'Once':'Monthly'); ?></td>
                          <td><?php echo $tr2; ?></td>
                          <td><button class="btn btn-info btn-xs" data-toggle="modal" data-target=".fac-cem-sm-<?php echo $dn['id']; ?>">View</button></td>
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
						<div class="modal fade fac-cem-sm-<?php echo $dn['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-xs" style="color:#000;font-family:Andalus;font-size:16px;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h3 class="modal-title" id="myModalLabel2"><?php echo $dn['name']; ?> Facilitators </h3>
										</div>
										<div class="modal-body">
										<?php
										$output = "";
										while($rr = mysqli_fetch_array($fac)){
											$fk = mysqli_fetch_array(mysqli_query($connection,"select name,info from admin where usertype=2 and id=".$rr['teacher']));
											$output = $output."<b>".$fk['name']."</b><br/>".$fk['info']."<br/><br/>";
										}
										echo "<p>".$output."</p>";
									    ?>
										<div class="ln_solid"></div>
										</div>
										<div class="modal-footer">
										  <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
										</div>
									</div>
								</div>
							</div>
						<div class="modal fade det-cem-sm-<?php echo $dn['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-xs" style="color:#000;font-family:Andalus;font-size:16px;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h3 class="modal-title" id="myModalLabel2"><?php echo $dn['name']; ?> Details </h3>
										</div>
										<div class="modal-body">
										<h5></h5>
											<p style="color:darkblue;">Course <?php echo $dn['name']; ?> <br/><?php echo $dn['descr']; ?>.<br/><br/>It has total number of <?php echo $tr2; ?> lessons.</p>

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
												Code:<input type="text" name="code" value="<?php echo $dn['code']; ?>" class="form-control" required=""><br/>
												Description of the course<textarea name="descr" class="form-control" required=""><?php echo $dn['descr']; ?></textarea>
												<select name="deno" class="form-control" >
													<option value="&#8358;" <?php echo $dn['deno']=='&#8358;'?'selected=selected':''; ?>>&#8358; Nigerian Naira</option>
													<option value="$" <?php echo $dn['deno']=='$'?'selected=selected':''; ?>>$ US Dollar</option>
													<option value="&pound;" <?php echo $dn['deno']=='&pound;'?'selected=selected':''; ?>>&pound; GB Pound</option>
													<option value="&euro;" <?php echo $dn['deno']=='&euro;'?'selected=selected':''; ?>>&euro; European Euro</option>
													<option value="&#8373;" <?php echo $dn['deno']=='&#8373;'?'selected=selected':''; ?>>&#8373; Ghanian Cedi</option>
													<option value="&#8377;" <?php echo $dn['deno']=='&#8377;'?'selected=selected':''; ?>>&#8377; Indian Rupee</option>
												</select>
												Price<input type="text" name="price" value="<?php echo $dn['price']; ?>" class="form-control" required="">
												Subscription: &nbsp;<input type="radio" name="gender" value="0" <?php echo $dn['sub']==0?'checked':''; ?> required="">&nbsp;Once&nbsp;&nbsp;<input type="radio" name="gender" value="1" <?php echo $dn['sub']==1?'checked':''; ?> required="">&nbsp;Monthly<br/>
												<div class="ln_solid"></div>
												<input type="hidden" name="cemedt" value="TRUE" />
												<input type="hidden" name="cemid" value="<?php echo $dn['id']; ?>" />
												<input type="hidden" name="cemidn" value="<?php echo $dn['code']; ?>" />
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
