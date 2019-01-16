<?php
include_once("header.php");

if (isset($_POST['cemform'])) {
    // Initialize a session:

    if (empty($_POST['title'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $title = mysqli_real_escape_string($connection,$_POST['title']);
    }
	$agenda = mysqli_real_escape_string($connection,$_POST['agenda']);
	//$ddte = "";
	if (empty($_POST['date'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $dater = mysqli_real_escape_string($connection,$_POST['date']);// dd/mm/yyyy
		if(strlen($dater)==10 and strpos($dater,'/')==2 and strrpos($dater,'/')==5){
			$d1 = explode("/",$dater)[0];$d2 = explode("/",$dater)[1];$d3 = explode("/",$dater)[2];
			if(strlen($d1)==2 and is_numeric($d1)){
				if(strlen($d2)==2 and is_numeric($d2)){
					if(strlen($d3)==4 and is_numeric($d1)){
						$date = $dater;
					}
				}
			}
		}
		if(empty($date)){
			$f = 'fz0';$cor = "Date format is wrong use dd/mm/yyyy format";
		}
    }
	if (empty($_POST['tyme'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $tymer = mysqli_real_escape_string($connection,$_POST['tyme']);// HH:MM
		if(strlen($tymer)==5 and strpos($tymer,':')==2){
			$d1 = explode(":",$tymer)[0];$d2 = explode(":",$tymer)[1];
			if(strlen($d1)==2 and is_numeric($d1)){
				if(strlen($d2)==2 and is_numeric($d2)){
					$tyme = '/'.$d1.'/'.$d2;
				}
			}
		}
		if(empty($tyme)){
			$f = 'fz0';$cor = "Time format is wrong use 24hrs HH:MM format (e.g. 17:45)";
		}
    }

	if ($_POST['course'] == '-1') {//if the email supplied is empty
        $cor = "Please select the course"; $f = 'fz0';
    } else {
        $course = mysqli_real_escape_string($connection,$_POST['course']);
    }
	if ($_POST['teacher'] == '-1') {//if the email supplied is empty
        $cor = "Please select the course facilitator"; $f = 'fz0';
    } else {
        $teacher = mysqli_real_escape_string($connection,$_POST['teacher']);
    }
	if (empty($f)){
		//mktime(hour,minute,second,month,day,year);
		list($d,$m,$y,$hh,$mm)=explode('/',$date.$tyme);
		$rdate = mktime($hh,$mm,0,$m,$d,$y);
		mysqli_query($connection,"insert into lessons (title,agenda,date,course,teacher) values ('$title','$agenda','$rdate','$tyme',$course,$teacher)");
		echo "<script>alert('New lesson created successfully');</script>";
	}
}elseif(isset($_POST['cemedt'])){
	// Initialize a session:

    if (empty($_POST['title'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $title = mysqli_real_escape_string($connection,$_POST['title']);
    }
	$agenda = mysqli_real_escape_string($connection,$_POST['agenda']);
	if (empty($_POST['date'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $dater = mysqli_real_escape_string($connection,$_POST['date']);// dd/mm/yyyy
		if(strlen($dater)==10 and strpos($dater,'/')==2 and strrpos($dater,'/')==5){
			$d1 = explode("/",$dater)[0];$d2 = explode("/",$dater)[1];$d3 = explode("/",$dater)[2];
			if(strlen($d1)==2 and is_numeric($d1)){
				if(strlen($d2)==2 and is_numeric($d2)){
					if(strlen($d3)==4 and is_numeric($d1)){
						$date = $dater;
					}
				}
			}
		}
		if(empty($date)){
			$f = 'fz0';$cor = "Date format is wrong use dd/mm/yyyy format";
		}
    }
	if (empty($_POST['tyme'])) {//if the email supplied is empty
        $f = 'fz0';
    } else {
        $tymer = mysqli_real_escape_string($connection,$_POST['tyme']);// HH:MM
		if(strlen($tymer)==5 and strpos($tymer,':')==2){
			$d1 = explode(":",$tymer)[0];$d2 = explode(":",$tymer)[1];
			if(strlen($d1)==2 and is_numeric($d1)){
				if(strlen($d2)==2 and is_numeric($d2)){
					$tyme = '/'.$d1.'/'.$d2;
				}
			}
		}
		if(empty($tyme)){
			$f = 'fz0';$cor = "Time format is wrong use 24hrs HH:MM format (e.g. 17:45)";
		}
    }
	if ($_POST['course'] == '-1') {//if the email supplied is empty
        $cor = "Please select the course"; $f = 'fz0';
    } else {
        $course = mysqli_real_escape_string($connection,$_POST['course']);
    }
	if ($_POST['teacher'] == '-1') {//if the email supplied is empty
        $cor = "Please select the course facilitator"; $f = 'fz0';
    } else {
        $teacher = mysqli_real_escape_string($connection,$_POST['teacher']);
    }
	if (empty($f)){
		$id = $_POST['cemid'];
		list($d,$m,$y,$hh,$mm)=explode('/',$date.$tyme);
		$rdate = mktime($hh,$mm,0,$m,$d,$y);
		mysqli_query($connection,"update lessons set title='$title',agenda='$agenda',course='$course',date='$rdate',teacher=$teacher where id=$id");
		echo "<script>alert('Lesson updated successfully');</script>";
	}
}elseif(isset($_POST['cemdel'])){
	$id = $_POST['cemid'];
	mysqli_query($connection,"delete from lessons where id=$id");
	echo "<script>alert('Lesson deleted successfully');</script>";
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
		//document.mosquer.date.value = document.mosquer.date.value + ele.value;
	}
}
function place(val){
	document.mosquer.date.placeholder = "Enter the date in "+ val +" currency";
}
</script>

<div class="right_col" role="main">
          <div class="">
		  <div class="clearfix"></div>

            <div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>List of Lessons<small>Details</small></h2>
					&nbsp;&nbsp;<?php if(isset($cor)){ ?><center>
				  <div style="text-align:center;width:30%;background-color:#ccc;border:2px #f00 solid; color:#a0a; font-family:times new roman; text-size:25px">
					<?php echo $cor; ?>
				  </div></center>
			  <?php } ?><?php if($_SESSION['usertype']!=1){ ?>
                    <ul class="nav navbar-right panel_toolbox">
						<li>
							<button class="btn btn-success btn-xs" data-toggle="modal" data-target=".new-cem-sm"><i class="fa fa-plus-circle"><b>Add New Lesson</b></i></button>
						</li>
                    </ul>
			  <?php } ?>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <p class="text-muted font-13 m-b-30">
                      List of lessons available under Erudite courses. Click 'Add New Lesson' button above to register a new lesson. Click on the 'Edit' button beside each lesson to edit it.
                    </p>


<div class="modal fade new-cem-sm" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:15px;text-align:justify;">
		<div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			  </button>
			  <h4 class="modal-title" id="myModalLabel2">Register a new lesson</h4>
			</div>
			<form class="form-horizontal form-label-left" name="mosquer" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<div class="modal-body">
					<h5>Fill all the fields</h5>
					<select name="course" class="form-control" <!--onChange="createLot(this.value);"-->><option value="-1">Select course</option>
						<?php
						$qns = mysqli_query($connection,"SELECT * FROM courses where deleted=0");
						while($dns = mysqli_fetch_array($qns)){ ?>
						<option value="<?php echo $dns['id']; ?>"><?php echo $dns['code'].' - '.$dns['name']; ?></option>
						<?php } ?>
					</select>
					<!--
					<script type="text/javascript">
						function createLot(cemvalue){
							if(cemvalue == '-1'){
								removeAll();
								return false;
							}
							var url = "poplist.php?act=lott&idr="+cemvalue;
							if (window.XMLHttpRequest) {
								req = new XMLHttpRequest();
							}else if (window.ActiveXObject) {
								req = new ActiveXObject("Microsoft.XMLHTTP");
							}
							if(req){
								req.open("GET",url, true);
								req.onreadystatechange = compute;
								req.send(null);
							}else{
								//removeAll();
							}
						}

						function compute(){
							if (req.readyState == 4) {
								if (req.status == 200) {
									var tol = req.responseXML.getElementsByTagName("cordinate");
									buildOption1(tol[0].firstChild.data);
								}
							}
						}

						function buildOption1(vals){
							document.getElementById("cordd").value = vals;
						}
						function removeAll(){
							document.getElementById("cordd").value = "Course Facilitator";
						}
					</script>
					-->
					<select name="teacher" class="form-control"><option value="-1">Select lesson facilitator</option>
						<?php
						$qns = mysqli_query($connection,"SELECT * FROM admin where usertype=2 and deleted=0");
						while($dns = mysqli_fetch_array($qns)){ ?>
						<option value="<?php echo $dns['id']; ?>"><?php echo $dns['name']; ?></option>
						<?php } ?>
					</select>
					<input type="text" name="title" placeholder="Lesson Title" class="form-control" required=""><br/>
					<textarea name="agenda" placeholder="Write any Agenda (Optional)" class="form-control"></textarea><br/>
					<input type="text" name="date" placeholder="Lesson Date (dd/mm/yyyy)" class="form-control" required="">
					<input type="text" name="tyme" placeholder="Lesson Time (hh:mm (e.g. 15:30))" class="form-control" required="">
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
                          <th>Title</th>
						  <th>Course</th>
                          <th>Facilitator</th>
                          <th>Date</th>
                          <th>Status</th>
                          <th>Resources</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
					  <?php
						$qnx = mysqli_query($connection,"select * from lessons order by id desc");
						while($dn = mysqli_fetch_array($qnx)){
							$tr1 = mysqli_fetch_array(mysqli_query($connection,"select name from admin where usertype=2 and id=".$dn['teacher']));
							$tr2 = mysqli_fetch_array(mysqli_query($connection,"select name,code from courses where id = ".$dn['course']));

					  ?>
                        <tr>
                          <td><?php echo $dn['title']; ?></td>
                          <td><?php echo $tr2['code']; ?></td>
						  <td><?php echo $tr1['name']; ?></td>
                          <td><?php echo date('D d M, Y h:i A',$dn['date']); ?></td>
						  <td><?php echo ($dn['start']==0?"PENDING":($dn['duration']==0?"ON GOING":"DONE")); ?></td>
						  <td><?php echo ($dn['duration']>0?"<a href='../classroom/res/lessons/".$dn['flip_url']."' target='_blank' class='btn btn-success btn-xs'>Flip</a><a href='../classroom/res/lessons/".$dn['chat_url']."' target='_blank' class='btn btn-info btn-xs'>Chat</a>":"Not Available"); ?></td>
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
								<div class="modal-dialog modal-xs" style="color:#000;font-family:Andalus;font-size:16px;text-align:justify;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h3 class="modal-title" id="myModalLabel2"><?php echo $dn['title']; ?> Details </h3>
										</div>
										<div class="modal-body">
										<h5></h5>
											<p style="color:darkblue;">Lesson <?php echo $dn['title']; ?> <br/>AGENDA:<br/><?php echo $dn['agenda']; ?>.<br/>It is going to be taken by <?php echo $tr1['name']; ?> on <?php echo date('D d M, Y h:i A',$dn['date']); ?>. The lesson resources can be downloaded at<br/>
											<?php echo $dn['duration']>0?"<a href='../classroom/res/lessons/".$dn['flip_url']."' target='_blank' class='btn btn-success btn-xs'>Flip</a><a href='../classroom/res/lessons/".$dn['chat_url']."' target='_blank' class='btn btn-info btn-xs'>Chat</a>":"Download link not available"; ?>
											</p>

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
										  <h4 class="modal-title" id="myModalLabel2">Edit Lesson</h4>
										</div>
										<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
											<div class="modal-body">
												<h5>Fill all the fields</h5>
												Course<select name="course" class="form-control" <!--onChange="createLot(this.value);"-->>
						<?php
						$qns = mysqli_query($connection,"SELECT * FROM courses where deleted=0");
						while($dns = mysqli_fetch_array($qns)){ ?>
						<option value="<?php echo $dns['id']; ?>" <?php echo $dn['course']==$dns['id']?'selected':''; ?>><?php echo $dns['code'].' - '.$dns['name']; ?></option>
						<?php } ?>
					</select><!--
					<script type="text/javascript">
						var glb = 0;
						function createLot(cemvalue){
							glb = cemvalue;
							if(cemvalue == '-1'){
								removeAll();
								return false;
							}
							var url = "poplist.php?act=lott&idr="+cemvalue;
							if (window.XMLHttpRequest) {
								req = new XMLHttpRequest();
							}else if (window.ActiveXObject) {
								req = new ActiveXObject("Microsoft.XMLHTTP");
							}
							if(req){
								req.open("GET",url, true);
								req.onreadystatechange = compute;
								req.send(null);
							}else{
								//removeAll();
							}
						}

						function compute(){
							if (req.readyState == 4) {
								if (req.status == 200) {
									var tol = req.responseXML.getElementsByTagName("cordinate");
									buildOption1(tol[0].firstChild.data);
								}
							}
						}

						function buildOption1(vals){
							document.getElementById("cordd").value = vals;
						}
						function removeAll(){
							document.getElementById("cordd").value = "Course Facilitator";
						}
					</script> -->


					Facilitator<select name="teacher" class="form-control">
					<option value="<?php echo $dn['teacher']; ?>" selected=selected><?php echo $tr1['name']; ?></option>
						<?php
						$qns = mysqli_query($connection,"SELECT * FROM admin where usertype=2 and id != '".$dn['teacher']."'");
						while($dns = mysqli_fetch_array($qns)){ ?>
						<option value="<?php echo $dns['id']; ?>"><?php echo $dns['name']; ?></option>
						<?php } ?>
					</select>
					Lesson Title<input type="text" name="title" value="<?php echo $dn['title']; ?>" class="form-control" required=""><br/>
					Agenda <textarea name="agenda" class="form-control"><?php echo $dn['agenda']; ?></textarea><br/>
					Lesson Date (dd/mm/yyyy)<input type="text" name="date" value="<?php echo date('d/m/Y',$dn['date']); ?>" class="form-control" required="">
					Lesson Time (hh:mm)<input type="text" name="tyme" value="<?php echo date('H:i',$dn['date']); ?>" class="form-control" required="">
												<div class="ln_solid"></div>
												<input type="hidden" name="cemedt" value="TRUE" />
												<input type="hidden" name="cemid" value="<?php echo $dn['id']; ?>" />
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
										  <h4 class="modal-title" id="myModalLabel2">Delete Lesson</h4>
										</div>
										<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
											<div class="modal-body">
												<h5></h5>
												<p style="color:purple;">Are you sure to delete <?php echo $dn['title']; ?></p>
												<div class="ln_solid"></div>
												<input type="hidden" name="cemdel" value="TRUE" />
												<input type="hidden" name="cemid" value="<?php echo $dn['id']; ?>" />
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
